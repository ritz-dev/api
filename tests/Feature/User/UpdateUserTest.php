<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/users/update'; // append {slug}

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

    public function test_user_can_be_updated_successfully()
    {
        $this->authenticateUser();

        $admin = User::factory()->create(['role_slug' => '10000000000000000000000000000000000']);
        $user = User::factory()->create();

        $payload = [
            'slug' => $user->slug, // Reusing existing slug
            'name' => 'Updated Name',
            'email' => 'newemail@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'status' => 'active',
            'role_slug' => '10000000000000000000000000000000000',
        ];

        $this->actingAs($admin)
            ->postJson($this->endpoint,$payload)
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'User updated successfully.',
            ]);
    }

    public function test_update_fails_with_existing_email()
    {
        $this->authenticateUser();

        $admin = User::factory()->create(['role_slug' => '10000000000000000000000000000000000']);
        $user1 = User::factory()->create(['email' => 'a@example.com']);
        $user2 = User::factory()->create(['email' => 'b@example.com']);

        $payload = [
            'slug' => $user2->slug, // Reusing existing slug
            'name' => 'Test',
            'email' => 'a@example.com', // Duplicate
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'active',
            'role_slug' => '10000000000000000000000000000000000',
        ];

        $this->actingAs($admin)
            ->postJson($this->endpoint,$payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_update_fails_with_invalid_email_format()
    {
        $this->authenticateUser();
        $admin = User::factory()->create(['role_slug' => '10000000000000000000000000000000000']);
        $user = User::factory()->create();

        $payload = [
            'slug' => $user->slug, // Reusing existing slug
            'name' => 'Test',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'active',
            'role_slug' => '10000000000000000000000000000000000',
        ];

        $this->actingAs($admin)
            ->postJson($this->endpoint,$payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_update_fails_without_name()
    {
        $this->authenticateUser();
        $admin = User::factory()->create(['role_slug' => '10000000000000000000000000000000000']);
        $user = User::factory()->create();

        $payload = [
            'slug' => $user->slug, // Reusing existing slug
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'active',
            'role_slug' => '10000000000000000000000000000000000',
        ];

        $this->actingAs($admin)
            ->postJson($this->endpoint,$payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_update_fails_with_short_password()
    {
        $this->authenticateUser();
        $admin = User::factory()->create(['role_slug' => '10000000000000000000000000000000000']);
        $user = User::factory()->create();

        $payload = [
            'slug' => $user->slug, // Reusing existing slug
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
            'status' => 'active',
            'role_slug' => '10000000000000000000000000000000000',
        ];

        $this->actingAs($admin)
            ->postJson($this->endpoint,$payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_update_requires_authentication()
    {
        $user = User::factory()->create();

        $payload = [
            'slug' => $user->slug, // Reusing existing slug
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'active',
            'role_slug' => '10000000000000000000000000000000000',
        ];

        $this->postJson($this->endpoint,$payload)
            ->assertStatus(401);
    }

    // public function test_update_fails_without_permission()
    // {
    //     $user = User::factory()->create();
    //     $other = User::factory()->create();

    //     $payload = [
    //         'name' => 'Unauthorized',
    //         'email' => 'unauth@example.com',
    //         'password' => 'password123',
    //         'password_confirmation' => 'password123',
    //         'status' => 'active',
    //         'role_slug' => '10000000000000000000000000000000000',
    //     ];

    //     $this->actingAs($user)
    //         ->postJson($this->endpoint,$payload)
    //         ->assertStatus(403);
    // }

    public function test_update_fails_for_non_existing_user()
    {
        $this->authenticateUser();
        $admin = User::factory()->create(['role_slug' => '10000000000000000000000000000000000']);

        $payload = [
            'slug' => 'non-existing-slug', // Non-existing slug
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'active',
            'role_slug' => '10000000000000000000000000000000000',
        ];

        $this->actingAs($admin)
            ->postJson($this->endpoint,$payload)
            ->assertStatus(422)
            ->assertJson([
                'status' => 'validation_error',
                'errors' => [
                    'slug' => ['The selected slug is invalid.'],
                ],
            ]);
    }

    public function test_update_fails_for_soft_deleted_user()
    {
        $this->authenticateUser();

        $admin = User::factory()->create(['role_slug' => '10000000000000000000000000000000000']);
        $deletedUser = User::factory()->create();
        $deletedUser->delete();

        $payload = [
            'slug' => $deletedUser->slug, // Reusing existing slug
            'name' => 'Deleted User',
            'email' => 'deleted@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'inactive',
            'role_slug' => '10000000000000000000000000000000000',
        ];

        $this->actingAs($admin)
            ->postJson($this->endpoint,$payload)
            ->assertStatus(404);
    }

    public function test_update_partial_fields()
    {
        $this->authenticateUser();
        $admin = User::factory()->create(['role_slug' => '10000000000000000000000000000000000']);
        $user = User::factory()->create();

        $payload = [
            'slug' => $user->slug, // Reusing existing slug
            'name' => 'Partially Updated',
            'email' => $user->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => $user->status,
            'role_slug' => $user->role_slug,
        ];

        $this->actingAs($admin)
            ->postJson($this->endpoint,$payload)
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'User updated successfully.']);
    }

    public function test_update_with_same_data()
    {
        $this->authenticateUser();

        $admin = User::factory()->create(['role_slug' => '10000000000000000000000000000000000']);
        $user = User::factory()->create([
            'name' => 'Same',
            'email' => 'same@example.com',
            'status' => 'active',
            'role_slug' => '10000000000000000000000000000000000',
        ]);

        $payload = [
            'slug' => $user->slug, // Reusing existing slug
            'name' => 'Same',
            'email' => 'same@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'active',
            'role_slug' => '10000000000000000000000000000000000',
        ];

        $this->actingAs($admin)
            ->postJson($this->endpoint,$payload)
            ->assertStatus(200);
    }
}
