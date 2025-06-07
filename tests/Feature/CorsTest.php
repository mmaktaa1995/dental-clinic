<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CorsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function handlesPreflightRequests()
    {
        $allowedOrigin = config('cors.allowed_origins')[0] ?? 'http://localhost:3000';

        $response = $this->withHeaders([
            'Origin' => $allowedOrigin,
            'Access-Control-Request-Method' => 'POST',
            'Access-Control-Request-Headers' => 'Content-Type, Authorization',
        ])->options('/api/login');

        // Preflight requests should return 204 No Content
        $response->assertStatus(204)
                ->assertHeader('Access-Control-Allow-Origin', $allowedOrigin)
                ->assertHeader('Access-Control-Allow-Methods')
                ->assertHeader('Access-Control-Allow-Headers');
    }

    /** @test */
    public function doesNotAllowPreflightRequestsFromUnauthorizedOrigins()
    {
        $response = $this->withHeaders([
            'Origin' => 'https://unauthorized-domain.com',
            'Access-Control-Request-Method' => 'GET',
            'Access-Control-Request-Headers' => 'Content-Type, Authorization',
        ])->options('/api/user');

        // Should not have CORS headers for unauthorized origins
        $this->assertFalse($response->headers->has('Access-Control-Allow-Origin'));
    }

    /** @test */
    public function includesCorsHeadersInActualRequests()
    {
        $allowedOrigin = config('cors.allowed_origins')[0] ?? 'http://localhost:3000';

        $response = $this->withHeaders([
            'Origin' => $allowedOrigin,
        ])->get('/api/user');

        // Should include CORS headers in the response
        $response->assertHeader('Access-Control-Allow-Origin', $allowedOrigin);
    }

    /** @test */
    public function includesExposedHeadersInResponse()
    {
        $allowedOrigin = config('cors.allowed_origins')[0] ?? 'http://localhost:3000';

        $response = $this->withHeaders([
            'Origin' => $allowedOrigin,
        ])->get('/api/user');

        // Should include exposed headers in the response
        $response->assertHeader('Access-Control-Expose-Headers');

        // Get the exposed headers
        $exposedHeaders = $response->headers->get('Access-Control-Expose-Headers');
        $this->assertNotEmpty($exposedHeaders, 'No exposed headers found in the response');

        // Convert to array and check for specific headers
        $exposedHeadersArray = array_map('trim', explode(',', $exposedHeaders));
        $this->assertContains(
            'X-RateLimit-Limit',
            $exposedHeadersArray,
            'X-RateLimit-Limit not found in exposed headers'
        );
        $this->assertContains(
            'X-RateLimit-Remaining',
            $exposedHeadersArray,
            'X-RateLimit-Remaining not found in exposed headers'
        );
    }
}
