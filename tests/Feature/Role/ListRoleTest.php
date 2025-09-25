<?php

namespace Tests\Feature\Role;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Laravel\Passport\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListRoleTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = 'gateway/roles';

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

    protected function authenticateUserWithPermission($permissions = ['role.view'])
    {
        $user = User::factory()->create();
        $user->role()->associate(Role::where('slug', '10000000000000000000000000000000000')->first())->save();

        $token = $user->createToken('TestToken')->accessToken;

        return [$user, $token];
    }

    public function test_get_all_roles_returns_data_and_total()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint);

        $response->assertStatus(200)
                 ->assertJsonStructure(['data', 'total']);
    }

    public function test_roles_can_be_sorted_by_name_asc()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'orderBy' => 'name',
            'sortedBy' => 'asc',
        ]);

        $response->assertStatus(200);

        $names = array_column($response->json('data'), 'name');
        $this->assertEquals(['Admin', 'Editor'], array_slice($names, 0, 2));
    }

    public function test_roles_can_be_filtered_by_name()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'search' => ['name' => 'Admin']
        ]);

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonFragment(['name' => 'Admin']);
    }

    public function test_roles_search_is_case_insensitive()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'search' => ['name' => 'super admin']
        ]);

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');
    }

    public function test_unmatched_filter_returns_empty_result()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'search' => ['name' => 'NonExistent']
        ]);

        $response->assertStatus(200)
                 ->assertJsonCount(0, 'data')
                 ->assertJson(['total' => 0]);
    }

    public function test_no_token_returns_unauthorized()
    {
        $response = $this->postJson($this->endpoint);

        $response->assertStatus(401);
    }

    public function test_invalid_token_returns_unauthorized()
    {
        $response = $this->withHeader('Authorization', 'Bearer fake-token')
                         ->postJson($this->endpoint);

        $response->assertStatus(401);
    }

    // public function test_user_without_permission_gets_forbidden()
    // {
    //     $user = User::factory()->create();
    //     $token = $user->createToken('TestToken')->accessToken;

    //     $response = $this->withToken($token)->postJson($this->endpoint);

    //     $response->assertStatus(403);
    // }

    public function test_soft_deleted_roles_are_not_returned()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        Role::where(['name' => 'Admin'])->delete();

        $response = $this->withToken($token)->postJson($this->endpoint);

        $names = array_column($response->json('data'), 'name');
        $this->assertNotContains('Admin', $names);
    }

    public function test_sql_injection_does_not_crash()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint, [
            'search' => ['name' => "' OR 1=1 --"]
        ]);

        $response->assertStatus(200);
    }

    public function test_response_structure_matches_expected_format()
    {
        [$user, $token] = $this->authenticateUserWithPermission();

        $response = $this->withToken($token)->postJson($this->endpoint);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data',
                     'total'
                 ]);
    }
}
