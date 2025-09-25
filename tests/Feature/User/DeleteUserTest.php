<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/users/delete';

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
    
    public function test_user_can_be_deleted_successfully()
    {
        $this->authenticateUser();

        $admin = User::factory()->create(['role_slug' => '10000000000000000000000000000000000']);
        $user  = User::factory()->create();

        $response = $this->actingAs($admin)->postJson($this->endpoint, [
            'slug' => $user->slug,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'User deleted successfully.']);

        $this->assertSoftDeleted('users', ['slug' => $user->slug]);
    }

    public function test_delete_requires_slug()
    {
        $this->authenticateUser();

        $admin = User::factory()->create();

        $response = $this->postJson($this->endpoint, []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['slug']);
    }

    public function test_delete_non_existing_user()
    {
        $this->authenticateUser();

        $admin = User::factory()->create(['role_slug' => '10000000000000000000000000000000000']);

        $response = $this->postJson($this->endpoint, [
            'slug' => 'non-existing-slug',
        ]);

        $response->assertStatus(422); // Validation fails due to `exists` rule
    }

    public function test_unauthenticated_user_cannot_delete()
    {
        $user = User::factory()->create();

        $response = $this->postJson($this->endpoint, [
            'slug' => $user->slug,
        ]);

        $response->assertStatus(401);
    }

    public function test_non_super_admin_cannot_delete_super_admin()
    {
        
        $superAdmin = User::factory()->create(['role_slug' => '10000000000000000000000000000000000']);
        $admin = User::factory()->create(['role_slug' => '10000000000000000000000000000000001']);
        Passport::actingAs($admin);

        $response = $this->postJson($this->endpoint, [
            'slug' => $superAdmin->slug,
        ]);

        $response->assertStatus(403)
                 ->assertJsonFragment([
                     'message' => 'You are not authorized to delete a super-admin user.',
                 ]);
    }
}
