<?php

namespace Tests\Feature\Permission;

use Tests\TestCase;
use App\Models\User;
use App\Models\Permission;
use Laravel\Passport\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/permissions';

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

    public function test_permissions_list_successfully_returns_data()
    {
        $token = $this->authenticate();
        Permission::factory()->count(5)->create();

        $response = $this->withToken($token)->postJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_permissions_list_has_expected_structure()
    {
        $token = $this->authenticate();
        Permission::factory()->create(['name' => 'View Users']);

        $response = $this->withToken($token)->postJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [['slug', 'name', 'description']],
            'total'
        ]);
    }

    public function test_permissions_list_sorted_and_filtered()
    {
        $token = $this->authenticate();
        Permission::factory()->create(['name' => 'Zebra']);
        Permission::factory()->create(['name' => 'Alpha']);

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'orderBy' => 'name',
            'sortedBy' => 'asc',
            'search' => ''
        ]);

        $response->assertStatus(200);
        $names = array_column($response->json('data'), 'name');
        $this->assertEquals(['Alpha', 'Zebra'], array_slice($names, 0, 2));
    }

    public function test_permissions_list_requires_authentication()
    {
        $response = $this->postJson($this->endpoint);

        $response->assertStatus(401);
    }

    public function test_permissions_list_with_pagination()
    {
        $token = $this->authenticate();
        Permission::factory()->count(30)->create();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'skip' => 2,
            'limit' => 10,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(10, count($response->json('data')));
    }

    public function test_permissions_list_with_invalid_input_returns_422()
    {
        $token = $this->authenticate();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'orderBy' => 'invalid_column',
            'sortedBy' => 'notasc',
        ]);

        $response->assertStatus(422);
    }
}
