<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

class ForgotPasswordTest extends TestCase
{
    // protected string $endpoint = 'gateway/forgot-password';

    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     RateLimiter::clear('forgot-password');
    // }

    // public function test_valid_email_sends_reset_link(): void
    // {
    //     Notification::fake();
    //     $user = User::factory()->create();

    //     $response = $this->postJson($this->endpoint, ['email' => $user->email]);

    //     $response->assertStatus(200)
    //              ->assertJsonStructure(['message']);

    //     Notification::assertSentTo($user, ResetPassword::class);
    // }

    // public function test_unregistered_email_suppresses_response(): void
    // {
    //     Notification::fake();

    //     $response = $this->postJson($this->endpoint, ['email' => 'unknown@example.com']);

    //     $response->assertStatus(200); // Should not reveal whether user exists
    //     Notification::assertNothingSent();
    // }

    // public function test_invalid_email_format_fails_validation(): void
    // {
    //     $response = $this->postJson($this->endpoint, ['email' => 'not-an-email']);

    //     $response->assertStatus(422)
    //              ->assertJsonValidationErrors(['email']);
    // }

    // public function test_rate_limiting_blocks_repeated_attempts(): void
    // {
    //     $user = User::factory()->create();

    //     for ($i = 0; $i < 10; $i++) {
    //         $this->postJson($this->endpoint, ['email' => $user->email]);
    //     }

    //     $response = $this->postJson($this->endpoint, ['email' => $user->email]);

    //     $response->assertStatus(429); // Too many requests
    // }

    // public function test_sql_injection_input_rejected(): void
    // {
    //     $response = $this->postJson($this->endpoint, [
    //         'email' => "' OR 1=1 --"
    //     ]);

    //     $response->assertStatus(422)
    //              ->assertJsonValidationErrors(['email']);
    // }

    // public function test_response_is_timely(): void
    // {
    //     $user = User::factory()->create();

    //     $start = microtime(true);

    //     $response = $this->postJson($this->endpoint, ['email' => $user->email]);

    //     $duration = microtime(true) - $start;

    //     $this->assertLessThan(2, $duration); // Should respond under 2 seconds
    //     $response->assertStatus(200);
    // }

    // public function test_case_insensitive_email_accepted(): void
    // {
    //     $user = User::factory()->create(['email' => 'User@Example.com']);

    //     $response = $this->postJson($this->endpoint, ['email' => 'user@example.com']);

    //     $response->assertStatus(200);
    // }
    
    // public function test_email_sent_has_expected_structure(): void
    // {
    //     Notification::fake();
    //     $user = User::factory()->create();

    //     $this->postJson($this->endpoint, ['email' => $user->email]);

    //     Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
    //         return isset($notification->token) && Str::length($notification->token) > 10;
    //     });
    // }

    // public function test_response_structure_is_consistent(): void
    // {
    //     $user = User::factory()->create();

    //     $response = $this->postJson($this->endpoint, ['email' => $user->email]);

    //     $response->assertJsonStructure(['message']);
    // }
}
