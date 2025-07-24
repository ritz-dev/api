<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/users/show'; // append {slug}

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

    protected function authenticateUser()
    {
        $user = User::factory()->create([
            'password' => bcrypt('securePass123'),
        ]);

        Passport::actingAs($user);
        return $user;
    }

    public function test_user_can_be_fetched_by_slug()
    {
        $this->authenticateUser();

        $user = User::factory()->create();

        $response = $this->postJson($this->endpoint, [
            'slug' => $user->slug,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'slug' => $user->slug,
                     'email' => $user->email,
                 ]);
    }

    public function test_user_not_found()
    {
        $this->authenticateUser();

        $user = User::factory()->create();

        $response = $this->postJson($this->endpoint, [
            'slug' => 'non-existent-slug',
        ]);

        $response->assertStatus(422)
                ->assertJson([
                    'status' => 'validation_error',
                    'errors' => [
                        'slug' => ['The selected slug is invalid.'],
                    ],
                ]);
    }

    public function test_for_soft_deleted_user()
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $deletedUser = User::factory()->create();
        $deletedUser->delete();

        $response = $this->postJson($this->endpoint, [
            'slug' => $deletedUser->slug,
        ]);

        $response->assertStatus(404);
    }

    public function test_requires_slug()
    {
        $this->authenticateUser();

        $user = User::factory()->create();

        $response = $this->postJson($this->endpoint, []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['slug']);
    }

    public function test_requires_authentication()
    {
        $user = User::factory()->create();

        $response = $this->postJson($this->endpoint, [
            'slug' => $user->slug,
        ]);

        $response->assertStatus(401);
    }

    // public function test_forbids_user_without_permission()
    // {
    //     // Optional: Only if permission check is applied
    //     $user = User::factory()->create([
    //         'role_slug' => 'basic-user'
    //     ]);

    //     Sanctum::actingAs($user);

    //     $response = $this->postJson($this->endpoint, [
    //         'slug' => $user->slug,
    //     ]);

    //     $response->assertStatus(403); // if you have RBAC
    // }
}
