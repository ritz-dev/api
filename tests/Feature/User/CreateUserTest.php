<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/users/store';

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

    public function test_user_can_be_created_successfully()
    {
        $this->authenticateUser();

        $payload = [
            'name'                  => 'John Doee',
            'email'                 => 'john1@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',   // <-- Add this
            'role_slug'             => '10000000000000000000000000000000000',
            'status'                => 'active',        // or appropriate status
        ];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('data.name', 'John Doee')
                 ->assertJsonPath('data.email', 'john1@example.com');

        $this->assertDatabaseHas('users', [
            'email' => 'john1@example.com',
            'status' => 'active',
        ]);
    }

    public function test_user_store_requires_required_fields()
    {
        $this->authenticateUser();

        $response = $this->postJson($this->endpoint, []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                    'name', 'email', 'password', 'role_slug'
                 ]);
    }

    public function test_user_store_requires_unique_email()
    {
        $this->authenticateUser();

        $user = User::factory()->create([
            'name'  => 'John Doe',
            'email' => 'john1@example.com',
            'password' => bcrypt('password123'),
            'role_slug' => '10000000000000000000000000000000000',
        ]);

        $payload = [
            'name'      => 'John Doe',
            'email'     => 'john1@example.com',
            'password'  => 'password1234',
            'password_confirmation' => 'password1234',
            'role_slug' => '10000000000000000000000000000000000',
            'status'  => 'active'
        ];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(422)
                 ->assertJson([
                    "status" => "validation_error",
                    "errors" => [
                        "email"=> [
                            "The email has already been taken."
                        ]
                    ]
                 ]);
        }

    public function test_user_store_requires_valid_email_format()
    {
        $this->authenticateUser();

        $payload = [
            'slug'      => 'jane-doe',
            'name'      => 'Jane Doe',
            'email'     => 'invalid-email',
            'password'  => 'password123',
            'role_slug' => '10000000000000000000000000000000000',
        ];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_user_store_fails_with_invalid_role_slug()
    {
        $this->authenticateUser();

        $payload = [
            'slug'      => 'peter-smith',
            'name'      => 'Peter Smith',
            'email'     => 'peter@example.com',
            'password'  => 'password123',
            'role_slug' => 'nonexistent-role',
        ];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['role_slug']);
    }

    public function test_user_store_sets_default_status_to_active()
    {
        $this->authenticateUser();

        $payload = [
            'slug'      => 'lucy-ray',
            'name'      => 'Lucy Ray',
            'email'     => 'lucy@example.com',
            'password'  => 'password123',
            'password_confirmation' => 'password123',
            'role_slug' => '10000000000000000000000000000000000',
            'status'   => 'active', // Optional, defaults to 'active'
        ];

        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'name' => 'Lucy Ray',
            'status' => 'active',
        ]);
    }
}
