<?php

namespace Tests\Feature\Permission;

use Tests\TestCase;
use App\Models\User;
use App\Models\Permission;
use Laravel\Passport\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/permissions/show';

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

    protected function authenticate(): string
    {
        $user = User::factory()->create();
        return $user->createToken('TestToken')->accessToken;
    }

    public function test_permission_show_returns_correct_data()
    {
        $token = $this->authenticate();
        $permission = Permission::factory()->create();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'slug' => $permission->slug,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                    'status' => 'success',
                    'data' => [
                     'slug'        => $permission->slug,
                     'name'        => $permission->name,
                     'description' => $permission->description,
                    ]
                ]);
    }

    public function test_permission_show_requires_authentication()
    {
        $permission = Permission::factory()->create();

        $response = $this->postJson($this->endpoint, [
            'slug' => $permission->slug,
        ]);

        $response->assertStatus(401);
    }

    public function test_permission_show_with_invalid_slug_returns_404()
    {
        $token = $this->authenticate();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'slug' => 'non-existing-slug',
        ]);

        $response->assertStatus(422)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => [
                        'slug' => ['The selected slug is invalid.'],
                    ],
                ]);
    }

    public function test_permission_show_with_missing_slug_returns_422()
    {
        $token = $this->authenticate();

        $response = $this->withToken($token)->postJson($this->endpoint, []);

        $response->assertStatus(422);
    }

    public function test_permission_show_response_has_expected_structure()
    {
        $token = $this->authenticate();
        $permission = Permission::factory()->create();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'slug' => $permission->slug,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'status',
                    'data' => [
                        'slug',
                        'name',
                        'description',
                    ]
                ]);
    }
}
