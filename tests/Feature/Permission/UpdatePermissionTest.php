<?php

namespace Tests\Feature\Permission;

use Tests\TestCase;
use App\Models\User;
use App\Models\Permission;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/permissions/update';

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

    public function test_update_permission_successfully()
    {
        $this->authenticateWithRole();

        $permission = Permission::factory()->create([
            'name' => 'Edit Posts',
            'description' => 'description'
        ]);

        $response = $this->postJson($this->endpoint, [
            'slug' => $permission->slug,
            'name' => 'Edit Blog Posts',
            'description' => 'Updated description',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Edit Blog Posts']);

        $this->assertDatabaseHas('permissions', [
            'slug' => $permission->slug,
            'name' => 'Edit Blog Posts',
            'description' => 'Updated description',
        ]);
    }

    public function test_update_permission_missing_slug_fails()
    {
        $this->authenticateWithRole();

        $response = $this->postJson($this->endpoint, [
            'name' => 'No Slug',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['slug']);
    }

    public function test_update_permission_invalid_slug()
    {
        $this->authenticateWithRole();

        $response = $this->postJson($this->endpoint, [
            'slug' => 'non-existent-slug',
            'name' => 'Update Attempt',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                "status" => "validation_error",
                "errors" => [
                "slug" => [
                    "The selected slug is invalid."
                ]
            ]]);
    }

    public function test_update_permission_validation_error()
    {
        $this->authenticateWithRole();

        $permission = Permission::factory()->create(['slug' => 'valid-slug']);

        $response = $this->postJson($this->endpoint, [
            'slug' => 'valid-slug',
            'name' => '', // Invalid (required)
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    public function test_update_permission_authentication_required()
    {
        $permission = Permission::factory()->create(['slug' => 'edit-users']);

        $response = $this->postJson($this->endpoint, [
            'slug' => 'edit-users',
            'name' => 'Unauthorized Update',
        ]);

        $response->assertStatus(401);
    }

    // public function test_update_permission_authorization_required()
    // {
    //     $this->authenticateWithRole('user'); // Not admin

    //     $permission = Permission::factory()->create(['slug' => 'delete-comments']);

    //     $response = $this->postJson($this->endpoint, [
    //         'slug' => 'delete-comments',
    //         'name' => 'Try Unauthorized Update',
    //     ]);

    //     $response->assertStatus(403);
    // }
}
