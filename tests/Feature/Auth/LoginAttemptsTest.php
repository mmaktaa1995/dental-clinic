<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginAttemptsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function locksAccountAfterTooManyFailedAttempts()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('correct-password'),
        ]);

        // Clear any existing rate limiter cache
        $throttleKey = $this->throttleKey('test@example.com', '127.0.0.1');
        RateLimiter::clear($throttleKey);

        // First 5 attempts should fail with 422
        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson('/api/v1/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);

            $response->assertStatus(422);
            $response->assertJson([
                'message' => 'بيانات الاعتماد هذه غير متطابقة مع البيانات المسجلة لدينا.',
                'errors' => [
                    'email' => ['بيانات الاعتماد هذه غير متطابقة مع البيانات المسجلة لدينا.']
                ]
            ]);
        }

        // 6th attempt should be rate limited with 429
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429);
        $response->assertJson([
            'message' => 'عدد كبير جدا من محاولات الدخول. يرجى المحاولة مرة أخرى بعد 900 ثانية.',
            'errors' => [
                'email' => ['عدد كبير جدا من محاولات الدخول. يرجى المحاولة مرة أخرى بعد 900 ثانية.']
            ]
        ]);
    }

    /** @test */
    public function resetsAttemptsAfterSuccessfulLogin()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('correct-password'),
        ]);

        // Try to login with wrong password twice
        for ($i = 0; $i < 2; $i++) {
            $response = $this->postJson('/api/v1/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);
            $response->assertStatus(422);
        }

        // Now login with correct password
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'correct-password',
        ]);

        $response->assertStatus(200);

        // Verify login attempts were reset
        $throttleKey = $this->throttleKey('test@example.com', '127.0.0.1');
        $this->assertEquals(0, RateLimiter::attempts($throttleKey));
    }

    /**
     * Get the throttle key for the given email and IP.
     */
    protected function throttleKey(string $email, string $ip): string
    {
        return 'login:' . sha1($email . '|' . $ip);
    }
}
