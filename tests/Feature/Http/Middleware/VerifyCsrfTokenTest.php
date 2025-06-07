<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class VerifyCsrfTokenTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }

    /** @test */
    public function generatesCsrfToken()
    {
        // Get CSRF token from Sanctum's endpoint
        $response = $this->get('/sanctum/csrf-cookie');

        // Should receive XSRF-TOKEN cookie
        $xsrfCookie = $response->headers->getCookies()[0] ?? null;
        $this->assertNotNull($xsrfCookie);
        $this->assertEquals('XSRF-TOKEN', $xsrfCookie->getName());
        $this->assertNotEmpty($xsrfCookie->getValue());
    }

    /** @test */
    public function allowsApiRequestsWithoutCsrfToken()
    {
        // Simulate an API request with JSON content type
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // API routes should be allowed without CSRF token
        // Will return 401 if credentials are wrong, but not 419 (CSRF error)
        $this->assertNotEquals(419, $response->status());
    }

    /** @test */
    public function allowsRequestsWithXsrfTokenHeader()
    {
        // First, get CSRF token from Sanctum's endpoint
        $response = $this->get('/sanctum/csrf-cookie');
        $xsrfToken = $response->headers->getCookies()[0]->getValue();

        // Simulate a request with X-XSRF-TOKEN header
        $response = $this->withHeaders([
            'X-XSRF-TOKEN' => $xsrfToken,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // Should be allowed
        $response->assertStatus(200);
    }

    /** @test */
    public function allowsRequestsWithCsrfTokenCookie()
    {
        // First, get CSRF token from Sanctum's endpoint
        $response = $this->get('/sanctum/csrf-cookie');
        $xsrfCookie = $response->headers->getCookies()[0];

        // Simulate a request with XSRF-TOKEN cookie
        $response = $this->withCookie($xsrfCookie->getName(), $xsrfCookie->getValue())
            ->postJson('/api/v1/login', [
                'email' => 'test@example.com',
                'password' => 'password',
            ]);

        // Should be allowed
        $response->assertStatus(200);
    }

    /** @test */
    public function requiresCsrfTokenForWebForms()
    {
        // Create a test route with CSRF protection explicitly enabled
        $router = $this->app['router'];

        $router->post('/test-csrf', function () {
            return response()->json(['message' => 'CSRF check passed']);
        })->middleware([
            'web', // This includes session and CSRF middleware
            'auth' // Require authentication to ensure we're testing web routes
        ]);

        // Create a test user
        $user = \App\Models\User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Try to access the route without CSRF token - should fail
        $response = $this->withoutExceptionHandling()
            ->post('/test-csrf');

        // Should be a 419 status code for CSRF token mismatch
        $response->assertStatus(419);
    }
}
