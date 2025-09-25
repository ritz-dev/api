<?php

namespace Tests\Feature\Permission;

use Tests\TestCase;
use App\Models\User;
use App\Models\Permission;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StorePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/permissions/store';

    protected function setUp(): void
    {
        parent::setUp();

        if (!Client::where('personal_access_client', true)->exists()) {
            $this->artisan('passport:client', [
                '--personal' => true,
                '--name' => 'Personal Access Client',
                '--no-interaction' => true,
            ])->run();
        }
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    protected function authenticateWithRole(): User
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        return $user;
    }

    public function test_create_permission_successfully()
    {
        $this->authenticateWithRole();

        $payload = [
            'name' => 'Manage Users',
            'description' => 'Can manage user accounts'
        ];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(201)
                ->assertJsonStructure(['data']);

        $this->assertDatabaseHas('permissions', [
            'name' => 'Manage Users',
        ]);
    }

    public function test_create_permission_validation_errors()
    {
        $this->authenticateWithRole();

        $response = $this->postJson($this->endpoint, []);

        $response->assertStatus(422)
                 ->assertJson([
                    'status' => 'validation_error',
                    'errors' => [
                        'name' => ['The name field is required.'],
                    ],
                ]);
    }

    public function test_create_permission_requires_authentication()
    {
        $response = $this->postJson($this->endpoint, [
            'slug' => 'test',
            'name' => 'Test',
        ]);

        $response->assertStatus(401);
    }

    // public function test_create_permission_requires_proper_role()
    // {
    //     $this->authenticateWithRole('user'); // Not admin

    //     $response = $this->postJson($this->endpoint, [
    //         'slug' => 'forbidden',
    //         'name' => 'Forbidden Action',
    //     ]);

    //     $response->assertStatus(403);
    // }

    public function test_create_permission_sql_injection_protection()
    {
        $this->authenticateWithRole();

        $payload = [
            'name' => '<script>alert("hack")</script>',
        ];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('permissions', [
            'name' => '<script>alert("hack")</script>',
        ]);
    }
}
