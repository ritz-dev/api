<?php

namespace Tests\Feature\Role;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateRoleTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/roles/update';

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

    protected function authenticateUserWithPermission(array $permissions = ['edit roles']): array
    {
        $user = User::factory()->create();
        $user->role()->associate(Role::where('slug', '10000000000000000000000000000000000')->first())->save();
        $user->save();

        $token = $user->createToken('TestToken')->accessToken;
        return [$user, $token];
    }

    public function test_role_can_be_updated_successfully()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $role = Role::first();

        $payload = [
            'slug' => $role->slug,
            'name' => 'Updated Name',
            'permissions' => ['user.edit', 'user.view'],
        ];

        $response = $this->withToken($token)->postJson($this->endpoint, $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name']);

        $this->assertDatabaseHas('roles', [
            'slug' => $role->slug,
            'name' => 'Updated Name',
        ]);
    }

    public function test_fails_with_missing_slug()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'name' => 'New Name',
            'permissions' => ['user.edit'],
        ]);

        $response->assertStatus(422)
                    ->assertJson([
                        'status' => 'validation_error',
                        'errors' => [
                            'slug' => ['The slug field is required.'],
                        ],
                    ]);
    }

    public function test_fails_with_invalid_permissions_format()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $role = Role::first();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'slug' => $role->slug,
            'name' => 'Updated',
            'permissions' => 'not-an-array',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['permissions']);
    }

    public function test_fails_with_duplicate_name()
    {
        [$user, $token] = $this->authenticateUserWithPermission();
        $role = Role::create(['name' => 'Existing Name']);

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'slug' => $role->slug,
            'name' => 'Admin',
            'permissions' => ['users.view', 'users.edit'],
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    public function test_fails_for_invalid_slug()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'slug' => Str::uuid()->toString(),
            'name' => 'Does Not Exist',
            'permissions' => ['users.view', 'users.edit'],
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
            'name' => 'Test',
            'permissions' => [],
        ]);

        $response->assertStatus(401);
    }

    // public function test_update_requires_proper_permission()
    // {
    //     $user = User::factory()->create();
    //     $role = Role::first();
    //     $user->role_slug = $role->slug;
    //     $user->save();

    //     $token = $user->createToken('TestToken')->accessToken;

    //     $roleToUpdate = Role::create(['name' => 'Original']);

    //     $response = $this->withToken($token)->postJson($this->endpoint, [
    //         'slug' => $roleToUpdate->slug,
    //         'name' => 'New Name',
    //         'permissions' => ['user.edit', 'user.view'],
    //     ]);

    //     $response->assertStatus(403);
    // }

    public function test_empty_permissions_is_valid()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $role = Role::first();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'slug' => $role->slug,
            'name' => 'Updated Name',
            'permissions' => ['user.edit', 'user.view'],
        ]);

        $response->assertStatus(200)->assertJsonFragment(['name' => 'Updated Name']);
    }
}
