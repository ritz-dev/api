<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
// use Laravel\Sanctum\Sanctum;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;
    protected string $endpoint = 'gateway/change-password'; 

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('change-password:');

        if (!Client::where('personal_access_client', true)->exists()) {
            $this->artisan('passport:client', [
                '--personal' => true,
                '--name' => 'Personal Access Client',
                '--no-interaction' => true,
            ])->run();
        }

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    protected function authenticateUser($password = 'password')
    {
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        $token = Password::createToken($user);

        Passport::actingAs($user);

        return [$user, $password, $token];
    }

    public function test_valid_request_changes_password()
    {
        [$user, $oldPassword, $token] = $this->authenticateUser();

        $response = $this->postJson($this->endpoint, [
            'token' => $token,
            'current_password' => $oldPassword,
            'new_password' => 'NewPass123!',
            'new_password_confirmation' => 'NewPass123!',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['message']);
    }

    public function test_missing_fields_returns_validation()
    {
        $this->authenticateUser();

        $response = $this->postJson($this->endpoint, []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['current_password', 'new_password']);
    }

    public function test_incorrect_current_password_fails()
    {
        $this->authenticateUser();

        $response = $this->postJson($this->endpoint, [
            'current_password' => 'WrongPass!',
            'new_password' => 'NewPass123!',
            'new_password_confirmation' => 'NewPass123!',
        ]);

        $response->assertStatus(422)
         ->assertJsonFragment([
             'message' => 'Current password is incorrect.', // with period
             'status' => 'error'
         ]);
    }

    public function test_weak_new_password_rejected()
    {
        $this->authenticateUser();

        $response = $this->postJson($this->endpoint, [
            'current_password' => 'OldPass123!',
            'new_password' => '123',
            'new_password_confirmation' => '123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['new_password']);
    }

    public function test_confirmation_mismatch_fails()
    {
        $this->authenticateUser();

        $response = $this->postJson($this->endpoint, [
            'current_password' => 'OldPass123!',
            'new_password' => 'NewPass123!',
            'new_password_confirmation' => 'NewPass999!',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['new_password']);
    }

    public function test_same_as_current_password_rejected()
    {
        $this->authenticateUser();

        $response = $this->postJson($this->endpoint, [
            'current_password' => 'OldPass123!',
            'new_password' => 'OldPass123!',
            'new_password_confirmation' => 'OldPass123!',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                    'status' => 'validation_error',
                    'errors' => [
                        'new_password' => [
                            'The new password field and current password must be different.'
                        ]
                    ]
                ]);
    }

    public function test_unauthenticated_user_blocked()
    {
        $response = $this->postJson($this->endpoint, [
            'current_password' => 'OldPass123!',
            'new_password' => 'NewPass123!',
            'new_password_confirmation' => 'NewPass123!',
        ]);

        $response->assertStatus(401);
    }

    public function test_sql_injection_attempt_rejected()
    {
        $this->authenticateUser();

        $response = $this->postJson($this->endpoint, [
            'current_password' => "' OR 1=1 --",
            'new_password' => "' OR 1=2 --",
            'new_password_confirmation' => "' OR 1=2 --",
        ]);

        $response->assertStatus(422)
         ->assertJsonFragment(['message' => 'Current password is incorrect.']);
    }

    public function test_rate_limiting_blocks_repeated()
    {
        [$user, $oldPassword, $token]  = $this->authenticateUser();

        for ($i = 0; $i < 4; $i++) {
            $this->postJson($this->endpoint, [
                'token' => $token,
                'current_password' => 'WrongPass!',
                'new_password' => 'NewPass123!',
                'new_password_confirmation' => 'NewPass123!',
            ]);
        }

        $response = $this->postJson($this->endpoint, [
            'current_password' => 'WrongPass!',
            'new_password' => 'NewPass123!',
            'new_password_confirmation' => 'NewPass123!',
        ]);

        $response->assertStatus(422)
         ->assertJsonFragment(['message' => 'Current password is incorrect.']);
    }

    public function test_response_structure_is_valid()
    {
        [$user, $oldPassword] = $this->authenticateUser();

        $response = $this->postJson($this->endpoint, [
            'current_password' => $oldPassword,
            'new_password' => 'AnotherPass123!',
            'new_password_confirmation' => 'AnotherPass123!',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['message']);
    }

    public function test_login_with_new_password_succeeds()
    {
        [$user, $oldPassword] = $this->authenticateUser();

        $this->postJson($this->endpoint, [
            'current_password' => $oldPassword,
            'new_password' => 'SecureNew123!',
            'new_password_confirmation' => 'SecureNew123!',
        ]);

        $this->postJson('gateway/logout');

        $response = $this->postJson('gateway/login', [
            'email' => $user->email,
            'password' => 'SecureNew123!',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    public function test_login_with_old_password_fails()
    {
        [$user, $oldPassword] = $this->authenticateUser();

        $this->postJson($this->endpoint, [
            'current_password' => $oldPassword,
            'new_password' => 'SecureNew123!',
            'new_password_confirmation' => 'SecureNew123!',
        ]);

        $this->postJson('gateway/logout');

        $response = $this->postJson('gateway/login', [
            'email' => $user->email,
            'password' => $oldPassword,
        ]);

        $response->assertStatus(401);
    }
}
