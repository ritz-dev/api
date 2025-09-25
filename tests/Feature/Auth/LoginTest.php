<?php

namespace Tests\Feature\Auth;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{   
    use RefreshDatabase;
    protected string $endpoint = 'gateway/login'; // Update if different

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('login');

        if (!Client::where('personal_access_client', true)->exists()) {
            $this->artisan('passport:client', [
                '--personal' => true,
                '--name' => 'Personal Access Client',
                '--no-interaction' => true,
            ])->run();
        }

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    public function test_valid_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson($this->endpoint, [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['slug', 'token', 'permissions', 'role']);
    }

    public function test_missing_email()
    {
        $response = $this->postJson($this->endpoint, [
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_missing_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson($this->endpoint, [
            'email' => $user->email,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_invalid_email_format()
    {
        $response = $this->postJson($this->endpoint, [
            'email' => 'invalid-email-format',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_non_existent_email()
    {
        $response = $this->postJson($this->endpoint, [
            'email' => 'nonexistent@example.com', // Email not in DB
            'password' => 'anyPassword123',
        ]);

        $response->assertStatus(401); // Unauthorized
        $response->assertJson([
            'message' => 'Invalid credentials', // Customize based on your API response
        ]);
    }
    
    public function test_account_locked()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'status' => 'inactive', // Assuming you have a status field
        ]);

        $response = $this->postJson($this->endpoint, [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(403)
            ->assertJson([
            'message' => 'Account is locked.',
        ]);
    }

    public function test_sql_injection_attempt()
    {
        $response = $this->postJson($this->endpoint, [
            'email' => "superadmin@example.com",
            'password' => "' OR '1'='1",
        ]);

        $response->assertStatus(401) // Expect invalid credentials
                ->assertJson([
                    'message' => 'Invalid credentials',
                ]);
    }

    public function test_rate_limiting_on_login()
    {
        // Simulate multiple failed attempts
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson($this->endpoint, [
                'email' => 'wrong@example.com',
                'password' => 'invalid-password',
            ]);
        }

        // Expect 429 Too Many Requests on the last attempt
        $response->assertStatus(429)
            ->assertJson([
            'message' => 'Too many login attempts. Please try again later.',
        ]);
    }

    public function test_incorrect_password()
    {
        // Attempt login with wrong password
        $response = $this->postJson($this->endpoint, [
            'email' => 'superadmin@example.com',
            'password' => 'wrong-password',
        ]);

        // Expect 401 Unauthorized
        $response->assertStatus(401)
                ->assertJson([
                    'message' => 'Invalid credentials', // adjust to your actual response
                ]);
    }

    public function test_case_insensitive_email_login()
    {
        $user = User::factory()->create([
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Attempt login with uppercase version of the email
        $response = $this->postJson($this->endpoint, [
            'email' => 'SUPERADMIN@EXAMPLE.COM',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'slug', 'token', 'permissions', 'role'
                ]);
    }

    public function test_password_too_short()
    {
        $response = $this->postJson($this->endpoint, [
            'email'    => 'superadmin@example.com',
            'password' => '123', // too short
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['password']);
    }

}
