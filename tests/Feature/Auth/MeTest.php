<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MeTest extends TestCase
{
    use RefreshDatabase;
    protected string $endpoint = 'gateway/me'; 
    
    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('me:');

        if (!Client::where('personal_access_client', true)->exists()) {
            $this->artisan('passport:client', [
                '--personal' => true,
                '--name' => 'Personal Access Client',
                '--no-interaction' => true,
            ])->run();
        }


        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }
    
    protected function authenticateUser($password = 'editorpassword')
    {
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        $token = $user->createToken('AdminPanel')->accessToken;

        Passport::actingAs($user);

        return [$user, $password, $token];
    }

    public function test_valid_token_returns_user_data() {
        [$user, $oldPassword, $token] = $this->authenticateUser();

        $response = $this->withHeader('Authorization', "Bearer $token")->postJson($this->endpoint);
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_no_token() 
    {
        $response = $this->postJson($this->endpoint);
        $response->assertStatus(401);
    }

    public function test_expired_token() 
    {
        $expiredToken = 'expired_token_here'; // simulate expired
        $response = $this->withHeader('Authorization', "Bearer $expiredToken")->postJson($this->endpoint);
        $response->assertStatus(401);
    }

    public function test_invalid_token() 
    {
        $response = $this->withHeader('Authorization', 'Bearer invalidtoken')->postJson($this->endpoint);
        $response->assertStatus(401);
    }

    public function test_revoked_token() 
    {
        $user = User::factory()->create();
        $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);

        // Create access token
        $tokenResult = $user->createToken('TestToken');
        $accessToken = $tokenResult->accessToken;

        // Revoke the token
        $tokenResult->token->revoke();

        $response = $this->withHeader('Authorization', "Bearer $accessToken")->postJson($this->endpoint);
        $response->assertStatus(401);
    }

    public function test_response_structure()
    {
        [$user, $oldPassword, $token] = $this->authenticateUser();

        $response = $this->withHeader('Authorization', "Bearer $token")->postJson($this->endpoint);
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'slug',
                        'name',
                        'email',
                        'role',
                        'permissions',
                    ],
                ])
                ->assertJson([
                    'status' => 'success',
                    'message' => 'Your information.',
                    'data' => [
                        'slug' => $user->slug,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role->name ?? null,
                        'permissions' => $user->role->permissions->pluck('name')->toArray() ?? [],
                    ],
                ]);
    }

    public function test_sql_injection_token_rejected() 
    {
        $response = $this->withHeader('Authorization', "Bearer ' OR 1=1 --")->postJson($this->endpoint);
        $response->assertStatus(401); // or 422 if validation
    }

    public function test_rate_limiting_applied()
    {
        // Create and authenticate a user
        $user = User::factory()->create();
        Passport::actingAs($user);

        // Hit the endpoint 10 times (limit)
        for ($i = 0; $i < 10; $i++) {
            $response = $this->postJson($this->endpoint); // assuming 'me' is a GET route
            $response->assertStatus(200);
        }

        // 11th request should be rate limited
        $response = $this->postJson($this->endpoint);
        $response->assertStatus(429);
        $response->assertJsonFragment([
            'message' => 'Too many requests. Please try again',
        ]);
    }

    public function test_response_time_is_fast() 
    {
        Passport::actingAs(User::factory()->create());
        $start = microtime(true);

        $response = $this->postJson($this->endpoint);

        $duration = microtime(true) - $start;
        $response->assertStatus(200);
        $this->assertLessThan(1.0, $duration, "Response took too long: {$duration}s");
    }
}
