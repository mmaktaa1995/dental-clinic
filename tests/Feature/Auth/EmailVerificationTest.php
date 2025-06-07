<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function emailVerificationScreenCanBeRendered()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => null,
            'password' => bcrypt('password')
        ]);

        $response = $this->actingAs($user, 'web')
            ->get('/api/v1/email/verify');

        $response->assertStatus(200);
    }

    public function emailCanBeVerified()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => null,
            'password' => bcrypt('password')
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user, 'web')
            ->get($verificationUrl);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(url('/login') . '?verified=1');
    }

    public function emailIsNotVerifiedWithInvalidHash()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => null,
            'password' => bcrypt('password')
        ]);

        // Create a verification URL with an invalid hash
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => 'wrong-hash']
        );

        // Act as the user and try to verify with invalid hash
        $response = $this->actingAs($user, 'web')
            ->get($verificationUrl);

        // The verification should redirect with an error for web requests
        $response->assertStatus(302);
        $response->assertRedirectContains('login?verified=0&error=invalid_token');

        // The user's email should still be unverified
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function emailVerificationNotificationIsSentUponRegistration()
    {
        Notification::fake();

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'testuser',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'type' => 'api',
        ];

        $response = $this->postJson('/api/v1/register', $userData);

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Registration successful. Please check your email to verify your account.',
                    'email_verified' => false,
                    'verification_required' => true,
                ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        Notification::assertSentTo($user, \App\Notifications\VerifyEmailNotification::class);
    }

    public function verifiedUserCanAccessProtectedRoutes()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson('/api/v1/user');

        $response->assertStatus(200);
    }

    public function unverifiedUserCannotAccessProtectedRoutes()
    {
        // Create an unverified user
        $user = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => null,
        ]);

        // Create a token for the unverified user
        $token = $user->createToken('test-token')->plainTextToken;

        // Authenticate the user
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Try to access a protected route that requires verification
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson('/api/v1/teeth');

        // The API should return a 403 status code for unverified users
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Your email address is not verified.',
            'verification_required' => true
        ]);

        // Verify the user is still unverified
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function userCanResendVerificationEmail()
    {
        Notification::fake();

        $user = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => null,
            'password' => bcrypt('Password123!')
        ]);

        $response = $this->actingAs($user, 'api')
            ->postJson('/api/v1/email/resend');

        Notification::assertSentTo($user, \App\Notifications\VerifyEmailNotification::class);
        $response->assertStatus(200);
    }

    public function passwordMustMeetComplexityRequirements()
    {
        // Test with password that doesn't meet complexity requirements
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'testuser',
            'password' => 'simple',
            'password_confirmation' => 'simple',
            'type' => 'api',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);

        // Test with password that meets complexity requirements
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'testuser',
            'password' => 'ComplexPass123!',
            'password_confirmation' => 'ComplexPass123!',
            'type' => 'api',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'username' => 'testuser',
        ]);
    }
}
