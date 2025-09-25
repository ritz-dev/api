<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListUserTest extends TestCase
{
    use RefreshDatabase;
    
    protected string $endpoint = 'gateway/users';

    protected function setUp(): void
    {
        parent::setUp();

        // Check if personal access client exists, else create it
        if (!Client::where('personal_access_client', true)->exists()) {
            $this->artisan('passport:client', [
                '--personal' => true,
                '--name' => 'Personal Access Client',
                '--no-interaction' => true,
            ])->run();
        }

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    protected function authenticateUser($withPermission = true)
    {
        $user = User::factory()->create([
            'password' => bcrypt('editorpassword')
        ]);

        // Attach permission logic here if needed
        // e.g. $user->givePermissionTo('view-users');
        if ($withPermission) {
            Passport::actingAs($user);
        } else {
            Passport::actingAs($user);
        }
        return $user;
    }

    public function test_authenticated_user_can_list_users()
    {
        $this->authenticateUser();

        User::factory()->count(5)->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson($this->endpoint);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['slug', 'name', 'email', 'status', 'role_slug','role']
                     ]
                 ]);
    }

    public function test_unauthenticated_user_cannot_list_users()
    {
        $response = $this->postJson($this->endpoint);
        $response->assertStatus(401);
    }

    // public function test_user_without_permission_cannot_list_users()
    // {
    //     $this->authenticateUser(false);

    //     $response = $this->postJson($this->endpoint);
    //     $response->assertStatus(403); // Or whatever you return for lack of permission
    // }

    public function test_response_contains_paginated_users()
    {
        $this->authenticateUser();

        User::factory()->count(50)->create();

        $response = $this->postJson($this->endpoint, [
            'skip' => 0,
            'limit' => 10,
        ]);

        $response->assertStatus(200)
                 ->assertJsonCount(10, 'data');
    }

    public function test_user_list_prevents_sql_injection()
    {
        $this->authenticateUser();

        $response = $this->postJson($this->endpoint, [
            'search' => "'; DROP TABLE users; --"
        ]);

        $response->assertStatus(200); // Should not crash or succeed in injection
    }

    public function test_user_list_is_sorted_and_filtered()
    {
        $this->authenticateUser();

        // Create users specifically for this test
        User::factory()->create(['name' => 'Zebra', 'password' => bcrypt('password123')]);
        User::factory()->create(['name' => 'Alpha', 'password' => bcrypt('password123')]);

        // Call the user listing API with sorting params
        $response = $this->postJson($this->endpoint, [
            'orderBy' => 'name',
            'sortedBy' => 'asc',
        ]);

        $response->assertStatus(200);

        // Extract all user names from response
        $names = array_column($response->json('data'), 'name');

        // Filter only the users we care about
        $filteredNames = array_values(array_filter($names, function ($name) {
            return in_array($name, ['Alpha', 'Zebra']);
        }));

        // Assert the filtered names are exactly ['Alpha', 'Zebra'] in order
        $this->assertEquals(['Alpha', 'Zebra'], $filteredNames);
    }
}
