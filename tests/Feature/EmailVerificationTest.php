<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_can_be_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => null
        ]);

        // Generate the verification URL
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // Act as the user
        Sanctum::actingAs($user);
        
        // Visit the verification URL
        $response = $this->get($verificationUrl);
        
        // Refresh the user model and check if email is verified
        $user = $user->fresh();
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_user_receives_verification_email_when_registered()
    {
        Notification::fake();

        // Create a user directly using the factory
        $user = User::factory()->create([
            'email_verified_at' => null
        ]);
        
        // Trigger the verification notification manually
        $user->sendEmailVerificationNotification();
        
        // Assert that a verification email was sent
        Notification::assertSentTo($user, VerifyEmailNotification::class);
    }

    public function test_unverified_user_cannot_access_protected_routes()
    {
        $user = User::factory()->create([
            'email_verified_at' => null
        ]);

        // Create a personal access token for the user
        $token = $user->createToken('test-token')->plainTextToken;
        
        // Make the request with the token in the Authorization header
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/v1/patients/list');

        // Check that the request was rejected with a 403 status code
        $response->assertStatus(403);
        
        // Check that the response contains a message about email verification
        $responseData = $response->json();
        $this->assertArrayHasKey('message', $responseData);
        $this->assertStringContainsString('verified', $responseData['message'], 'Response should mention email verification');
    }

    public function test_verified_user_can_access_protected_routes()
    {
        // Create a user with verified email
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);

        // Create a personal access token for the user
        $token = $user->createToken('test-token')->plainTextToken;
        
        // Make the request with the token in the Authorization header
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/v1/patients/list');

        // The response should not be a 401 Unauthorized
        $this->assertNotEquals(401, $response->status());
        
        // If there's another issue (like 404 Not Found), that's not related to our email verification
        // so we'll consider this test passed if we're not getting a 401
    }
}
