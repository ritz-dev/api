<?php

namespace Tests\Feature\Role;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Laravel\Passport\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowRoleTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/roles/show';

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

    protected function authenticateUserWithPermission(array $permissions = ['view roles']): array
    {
        $user = User::factory()->create();
        $user->role()->associate(Role::where('slug', '10000000000000000000000000000000000')->first())->save();
        $token = $user->createToken('TestToken')->accessToken;
        return [$user, $token];
    }

    public function test_show_role()
    {
        [$user, $token] = $this->authenticateUserWithPermission();
        $role = Role::first();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'slug' => $role->slug,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['slug' => $role->slug])
                 ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'slug',
                        'name',
                        'permissions',
                    ],
                 ]);
    }

    public function test_nonexistent_slug()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

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

    // public function test_user_has_no_permission()
    // {
    //     $user = User::factory()->create();
    //     $role = Role::factory()->create();
    //     $user->role_slug = $role->slug;
    //     $user->save();

    //     $token = $user->createToken('TestToken')->accessToken;

    //     $response = $this->withToken($token)->postJson($this->endpoint, [
    //         'slug' => $role->slug,
    //     ]);

    //     $response->assertStatus(403);
    // }

    public function test_unauthenticated()
    {
        $role = Role::first();

        $response = $this->postJson($this->endpoint, [
            'slug' => $role->slug,
        ]);

        $response->assertStatus(401);
    }

    public function test_sql_injection_is_sanitized()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'slug' => "' OR 1=1 --",
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'validation_error',
                     'errors' => [
                         'slug' => ['The selected slug is invalid.'],
                     ],
                 ]);
    }

    public function test_missing_slug_returns_validation_error()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['slug']);
    }

    public function test_soft_deleted_role()
    {
        [$user, $token] = $this->authenticateUserWithPermission();
        $role = Role::first();
        $role->delete();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'slug' => $role->slug,
        ]);

        $response->assertStatus(404);
    }
}
