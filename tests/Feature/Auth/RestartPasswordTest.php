<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RestartPasswordTest extends TestCase
{
    use RefreshDatabase;
    protected string $endpoint = 'gateway/reset-password';

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('reset-password:');

        if (!Client::where('personal_access_client', true)->exists()) {
            $this->artisan('passport:client', [
                '--personal' => true,
                '--name' => 'Personal Access Client',
                '--no-interaction' => true,
            ])->run();
        }


        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    protected function generateResetToken(User $user): string
    {
        return Password::createToken($user);
    }

    public function test_valid_token_and_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $token = $this->generateResetToken($user);

        $response = $this->postJson($this->endpoint, [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['message']);
    }

    public function test_missing_token()
    {
        $response = $this->postJson($this->endpoint, [
            'email' => 'admin@example.com',
            'password' => 'adminpassword',
            'password_confirmation' => 'adminpassword',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['token']);
    }

    public function test_missing_email()
    {
        $response = $this->postJson($this->endpoint, [
            'token' => 'dummy',
            'password' => 'adminpassword',
            'password_confirmation' => 'adminpassword',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_missing_password()
    {
        $response = $this->postJson($this->endpoint, [
            'token' => 'dummy',
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    public function test_password_confirmation_mismatch()
    {
        $response = $this->postJson($this->endpoint, [
            'token' => 'dummy',
            'email' => 'user@example.com',
            'password' => 'adminpassword',
            'password_confirmation' => 'adminpasswordd',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    public function test_invalid_or_expired_token()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson($this->endpoint, [
            'token' => 'expired-token',
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_invalid_email_format()
    {
        $response = $this->postJson($this->endpoint, [
            'token' => 'token',
            'email' => 'bad-email-format',
            'password' => 'adminpassword',
            'password_confirmation' => 'adminpassword',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_weak_password()
    {
        $response = $this->postJson($this->endpoint, [
            'token' => 'token',
            'email' => 'user@example.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    public function test_reuse_of_token_should_fail()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $token = $this->generateResetToken($user);

        // First use - should succeed
        $this->postJson($this->endpoint, [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword1!',
            'password_confirmation' => 'NewPassword1!',
        ])->assertStatus(200);

        // Reuse same token - should fail
        $this->postJson($this->endpoint, [
            'token' => $token,
            'email' => $user->email,
            'password' => 'AnotherPass1!',
            'password_confirmation' => 'AnotherPass1!',
        ])->assertStatus(422)
          ->assertJsonValidationErrors(['email']);
    }

    public function test_sql_injection_attempt()
    {
        $response = $this->postJson($this->endpoint, [
            'token' => "' OR 1=1 --",
            'email' => "' OR 1=1 --",
            'password' => "' OR '1'='1",
            'password_confirmation' => "' OR '1'='1",
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_rate_limit_attempts()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password')
        ]);

        $validToken = $this->generateResetToken($user);

        $resetData = [
            'token' => $validToken,
            'email' => $user->email,
            'password' => 'password!',
            'password_confirmation' => 'password!',
        ];

        // Make 4 failed attempts with invalid token to hit the limit
        for ($i = 0; $i < 3; $i++) {
            $response = $this->postJson($this->endpoint, array_merge($resetData, ['token' => 'invalid-token']));
            $response->assertStatus(422);
        }

        // 5th attempt (even with valid token) should be blocked with 429
        $response = $this->postJson($this->endpoint, $resetData);

        $response->assertStatus(429);
        $response->assertJsonFragment([
            'message' => 'Too many attempts. Please try again',
        ]);
    }

    public function test_long_fields()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $token = $this->generateResetToken($user);
        $passwordgen = Str::random(255);

        $response = $this->postJson($this->endpoint, [
            'token' => $token,
            'email' => $user->email,
            'password' => $passwordgen,
            'password_confirmation' => $passwordgen,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    public function test_response_structure()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);
        $token = $this->generateResetToken($user);

        $response = $this->postJson($this->endpoint, [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'message']);
    }

    public function test_password_actually_updated()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);
        $token = $this->generateResetToken($user);

        $newPassword = 'newPassword123';

        $this->postJson($this->endpoint, [
            'token' => $token,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ])->assertStatus(200);

        $user->refresh();
        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    public function test_old_password_should_be_invalidated()
    {
        $oldPassword = 'oldpassword';
        $newPassword = 'newPassword123';

        $user = User::factory()->create([
            'password' => bcrypt($oldPassword),
        ]);

        $token = $this->generateResetToken($user);

        $this->postJson($this->endpoint, [
            'token' => $token,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ])->assertStatus(200);

        $user->refresh();
        $this->assertFalse(Hash::check($oldPassword, $user->password));
    }
}
