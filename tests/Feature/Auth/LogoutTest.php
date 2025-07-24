<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;

class LogoutTest extends TestCase
{
    protected string $endpoint = 'gateway/logout'; 

    protected function authenticateUser()
    {
        $user = User::factory()->create();
        $tokenResult = $user->createToken('TestToken');
        $token = $tokenResult->accessToken;

        Passport::actingAs($user);
        return [$user, $token];
    }

    // public function test_valid_logout()
    // {
    //     [$user, $token] = $this->authenticateUser();

    //     $response = $this->withHeaders([
    //         'Authorization' => 'Bearer ' . $token,
    //     ])->postJson($this->endpoint);

    //     $response->assertStatus(200)
    //             ->assertJsonStructure(['message']);
    // }

    public function test_missing_token()
    {
        $response = $this->postJson($this->endpoint);

        $response->assertStatus(401)
                 ->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    public function test_expired_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer expired_token',
        ])->postJson($this->endpoint);

        $response->assertStatus(401)
                 ->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    // public function test_already_revoked_token()
    // {
    //     [$user, $token] = $this->authenticateUser();

    //     // First logout
    //     $this->withToken($token)->postJson($this->endpoint);

    //     // Second logout with same token
    //     $response = $this->withToken($token)->postJson($this->endpoint);

    //     $response->assertStatus(401)
    //              ->assertJsonFragment(['message' => 'Unauthenticated.']);
    // }

    public function test_invalid_token()
    {
        $response = $this->withToken('fake-invalid-token')->postJson($this->endpoint);

        $response->assertStatus(401)
                 ->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    // public function test_session_token_is_revoked()
    // {
    //     $user = User::factory()->create();
    //     $token = $user->createToken('TestToken')->accessToken;
    //     $tokenId = explode('|', $token, 2)[0];

    //     $this->withToken($token)->postJson($this->endpoint);

    //     $this->assertDatabaseHas('oauth_access_tokens', [
    //         'id' => $tokenId,
    //         'revoked' => true,
    //     ]);
    // }

    // public function test_response_structure()
    // {
    //     $this->authenticate();

    //     $response = $this->postJson($this->endpoint);

    //     $response->assertStatus(200)
    //              ->assertJsonStructure(['message']);
    // }

    // public function test_logout_rate_limiting()
    // {
    //     $user = $this->authenticate();

    //     for ($i = 0; $i < 10; $i++) {
    //         $this->postJson($this->endpoint);
    //     }

    //     $response = $this->postJson($this->endpoint);

    //     if ($response->status() === 429) {
    //         $response->assertStatus(429);
    //     } else {
    //         $this->markTestSkipped('Rate limiting not enforced on /logout route.');
    //     }
    // }

    // public function test_logout_performance()
    // {
    //     $this->authenticate();

    //     $start = microtime(true);

    //     $response = $this->postJson($this->endpoint);

    //     $duration = microtime(true) - $start;

    //     $response->assertStatus(200);
    //     $this->assertLessThan(1.0, $duration, 'Logout took too long: ' . $duration . ' seconds');
    // }
}
