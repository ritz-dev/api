<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    protected string $endpoint = 'gateway/logout'; 

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('logout');

        if (!Client::where('personal_access_client', true)->exists()) {
            $this->artisan('passport:client', [
                '--personal' => true,
                '--name' => 'Personal Access Client',
                '--no-interaction' => true,
            ])->run();
        }

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    protected function authenticateUser()
    {
        $user = User::factory()->create();
        $tokenResult = $user->createToken('TestToken');
        $token = $tokenResult->accessToken;  // string token
        // Passport::actingAs($user);
        return [$user, $token];
    }

    public function test_valid_logout()
    {
        [$user, $token] = $this->authenticateUser();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->endpoint);

        $response->assertStatus(200)
                ->assertJsonStructure(['status', 'message']);
    }

    public function test_missing_token()
    {
        $response = $this->postJson($this->endpoint);

        $response->assertStatus(401)
                 ->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    public function test_logout_with_invalid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalidtoken123',
        ])->postJson($this->endpoint);

        $response->assertStatus(401)
                ->assertJson([
                    'message' => 'Unauthenticated.'
                ]);
    }

    public function test_logout_with_revoked_token()
    {
        [$user, $token] = $this->authenticateUser();

        // Revoke token in database manually
        $tokenId = $user->tokens()->first()->id;
        $user->tokens()->where('id', $tokenId)->update(['revoked' => true]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->endpoint);

        $response->assertStatus(401)
                ->assertJson([
                    'message' => 'Unauthenticated.'
                ]);
    }

    public function test_response_structure()
    {
        [$user, $token] = $this->authenticateUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->endpoint);

        $response->assertStatus(200)
                ->assertJsonStructure(['message']);
    }

    public function test_rate_limiting()
    {
        $key = 'logout:' . request()->ip();
        RateLimiter::clear($key);

        // Simulate multiple logout attempts
        for ($i = 0; $i < 4; $i++) {
            $response = $this->postJson($this->endpoint);
            if ($i < 5) {
                $response->assertStatus(401);
            } else {
                $response->assertStatus(429)
                         ->assertJson(['message' => 'Too many logout attempts. Please try again later.']);
            }
        }
    }
}
