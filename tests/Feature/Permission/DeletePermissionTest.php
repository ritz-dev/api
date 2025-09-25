<?php

namespace Tests\Feature\Permission;

use Tests\TestCase;
use App\Models\User;
use App\Models\Permission;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeletePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/permissions/delete';

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

    public function test_delete_permission_successfully_soft_deletes()
    {
        $this->authenticateWithRole();

        $permission = Permission::factory()->create(['slug' => 'can-delete-posts']);

        $response = $this->postJson($this->endpoint, [
            'slug' => 'can-delete-posts',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(["message"=>"Permission deleted successfully.","status"=>"success"]);

        $this->assertSoftDeleted('permissions', [
            'slug' => 'can-delete-posts'
        ]);
    }

    public function test_delete_permission_missing_slug_returns_422()
    {
        $this->authenticateWithRole();

        $response = $this->postJson($this->endpoint, []); // no slug

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['slug']);
    }

    public function test_delete_permission_invalid_slug()
    {
        $this->authenticateWithRole();

        $response = $this->postJson($this->endpoint, [
            'slug' => 'non-existent-slug',
        ]);

        $response->assertStatus(422)
                ->assertJson([
                    "status" => 'validation_error',
                    "errors" => [
                        "slug" => [
                            "The selected slug is invalid."
                        ]
                    ]
                ]);
    }

    public function test_delete_permission_requires_authentication()
    {
        $permission = Permission::factory()->create(['slug' => 'secure-action']);

        $response = $this->postJson($this->endpoint, [
            'slug' => 'secure-action',
        ]);

        $response->assertStatus(401);
    }

    // public function test_delete_permission_requires_authorization()
    // {
    //     $this->authenticateWithRole('user'); // non-admin

    //     $permission = Permission::factory()->create(['slug' => 'restricted-permission']);

    //     $response = $this->postJson($this->endpoint, [
    //         'slug' => 'restricted-permission',
    //     ]);

    //     $response->assertStatus(403);
    // }

    public function test_delete_permission_cannot_delete_critical_permissions()
    {
        $this->authenticateWithRole();

        $permission = Permission::factory()->create([
            'slug' => 'system-manage-users', // mark this slug as protected in controller
        ]);

        $response = $this->postJson($this->endpoint, [
            'slug' => 'system-manage-users',
        ]);

        $response->assertStatus(403)
                 ->assertJsonFragment(['message' => 'This permission is protected and cannot be deleted.']);

        $this->assertDatabaseHas('permissions', [
            'slug' => 'system-manage-users',
            'deleted_at' => null,
        ]);
    }
}
