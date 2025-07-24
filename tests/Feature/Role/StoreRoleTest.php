<?php

namespace Tests\Feature\Role;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Laravel\Passport\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreRoleTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/roles/store';

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

    protected function authenticateUserWithPermission(array $permissions = ['create roles']): array
    {
        $user = User::factory()->create();
        $user->role()->associate(Role::where('slug', '10000000000000000000000000000000000')->first())->save();

        $token = $user->createToken('TestToken')->accessToken;
        return [$user, $token];
    }

    public function test_role_can_be_created_with_valid_data()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $payload = [
            'name' => 'Test Role',
            'permissions' => ['view users', 'edit users'],
        ];

        $response = $this->withToken($token)->postJson($this->endpoint, $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                        'status',
                        'message',
                        'data' => [
                            'slug',
                            'name',
                            'permissions' => [
                                '*' => ['slug', 'name'],
                            ],
                        ],
                 ]);

        $this->assertDatabaseHas('roles', ['name' => 'Test Role']);
    }

    public function test_duplicate_name_fails()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'name' => 'Admin',
            'permissions' => [],
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    public function test_invalid_permissions_format_fails()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'name' => 'Invalid Perm Role',
            'permissions' => 'not-an-array',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['permissions']);
    }

    public function test_missing_name_returns_validation_error()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'permissions' => ['users.view', 'users.edit'],
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    public function test_returns_401_if_unauthenticated()
    {
        $response = $this->postJson($this->endpoint, [
            'name' => 'Test',
            'permissions' => [],
        ]);

        $response->assertStatus(401);
    }

    // public function test_returns_403_if_user_has_no_permission()
    // {
    //     $user = User::factory()->create();
    //     $role = Role::factory()->create();
    //     $user->role_slug = $role->slug;
    //     $user->save();

    //     $token = $user->createToken('TestToken')->accessToken;

    //     $response = $this->withToken($token)->postJson($this->endpoint, [
    //         'name' => 'Test Role',
    //         'permissions' => [],
    //     ]);

    //     $response->assertStatus(403);
    // }

    public function test_sql_injection_in_name_is_escaped()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'name' => "'; DROP TABLE roles; --",
            'permissions' => ['view users', 'edit users'],
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('roles', ['name' => "'; DROP TABLE roles; --"]);
    }

    public function test_slug_is_generated_automatically()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'name' => 'Role Slug Test',
            'permissions' => ['view users', 'edit users'],
        ]);

        $response->assertStatus(201);
        $slug = $response->json('slug');
    }

    public function test_long_name_fails_validation()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'name' => str_repeat('A', 256),
            'permissions' => [],
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }
}
