<?php

namespace Tests\Feature\Role;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Laravel\Passport\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteRoleTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/roles/delete';

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
        // $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);
    }

    protected function authenticateWithPermission(array $permissions = ['delete roles']): array
    {
        $user = User::factory()->create();
        $user->role()->associate(Role::where('slug', '10000000000000000000000000000000000')->first())->save();
        $user->save();

        $token = $user->createToken('TestToken')->accessToken;
        return [$user, $token];
    }

    public function test_role_can_be_deleted_successfully()
    {
        [$user, $token] = $this->authenticateWithPermission();

        $role = Role::where('slug', '!=', '10000000000000000000000000000000000')->first();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'slug' => $role->slug,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'success','message' => 'Role deleted successfully.']);

        $this->assertSoftDeleted('roles', ['slug' => $role->slug]);
    }

    public function test_deletion_fails_with_missing_slug()
    {
        [$user, $token] = $this->authenticateWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, []);

        $response->assertStatus(422)->assertJsonValidationErrors(['slug']);
    }

    public function test_fails_for_non_existent_role()
    {
        [$user, $token] = $this->authenticateWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
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

    public function test_requires_authentication()
    {
        $role = Role::first();

        $response = $this->postJson($this->endpoint, [
            'slug' => $role->slug,
        ]);

        $response->assertStatus(401);
    }

    // public function test_deletion_requires_proper_permission()
    // {
    //     $user = User::factory()->create();
    //     $role = Role::first();

    //     $token = $user->createToken('TestToken')->accessToken;

    //     $roleToDelete = Role::create([
    //         'name' => 'Role to Delete',
    //         'permissions' => ['users.view', 'users.edit'],
    //     ]);

    //     $response = $this->withToken($token)->postJson($this->endpoint, [
    //         'slug' => $roleToDelete->slug,
    //     ]);

    //     $response->assertStatus(403);
    // }

    public function test_super_admin_role_cannot_be_deleted()
    {
        [$user, $token] = $this->authenticateWithPermission();

        $role = Role::where('name', 'Super Admin')->first();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'slug' => $role->slug,
        ]);

        $response->assertStatus(403)
                 ->assertJsonFragment(['message' => 'Cannot delete super admin role']);
    }
}
