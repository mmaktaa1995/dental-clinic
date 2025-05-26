<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class SessionTimeoutTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Set default token expiration for testing
        Config::set('sanctum.expiration', 60); // 60 minutes
        
        // Create a token for the user
        $this->token = $this->user->createToken('test-token')->plainTextToken;
        
        // Set up session for testing
        $this->withSession([
            '_token' => 'test-token',
            'password_hash_sanctum' => $this->user->getAuthPassword()
        ]);
    }

/** @test */
    public function it_logs_out_user_after_token_expiration()
    {
        // Set token expiration to 1 minute for testing
        config(['sanctum.expiration' => 1]);
        
        // Create a new token that will expire in 1 minute
        $token = $this->user->createToken('test-token')->plainTextToken;
        
        // Simulate time passing beyond token expiration (2 minutes)
        $this->travel(2)->minutes();
        
        // Try to access a protected route with expired token
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'X-Requested-With' => 'XMLHttpRequest'
        ])->getJson('/api/v1/user');
        
        // Should be unauthorized due to token expiration
        $response->assertUnauthorized();
    }

    /** @test */
    public function it_refreshes_token_before_expiration()
    {
        // Make initial request with the token
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/v1/user');
        
        $response->assertStatus(200);
        
        // Get the refresh token from the response
        $refreshToken = $response->json('refresh_token');
        
        // Simulate time passing (less than token expiration)
        $this->travel(30)->minutes();
        
        // Refresh the token
        $response = $this->postJson('/api/v1/refresh-token', [
            'refresh_token' => $refreshToken
        ]);
        
        $response->assertStatus(200);
        $this->assertNotNull($response->json('access_token'));
        $this->assertNotEquals($this->token, $response->json('access_token'));
    }

    /** @test */
    public function it_handles_expired_refresh_token()
    {
        // Create a refresh token manually
        $refreshToken = $this->user->createToken('refresh-token', ['*'], now()->addMinutes(120))->plainTextToken;
        
        // Simulate time passing beyond refresh token expiration
        $this->travel(121)->minutes();
        
        // Try to refresh with expired token
        $response = $this->postJson('/api/v1/refresh-token', [
            'refresh_token' => $refreshToken
        ]);
        
        $response->assertUnauthorized();
        $this->assertEquals('Unauthenticated.', $response->json('message'));
    }

    /** @test */
    public function it_handles_invalid_tokens()
    {
        // Try to access a protected route with an invalid token
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer invalid.token.here'
        ])->getJson('/api/v1/user');
        
        $response->assertStatus(401);
    }
}
