<?php

namespace Tests\Feature\Http\Middleware;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContentSecurityPolicyHeadersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sets_content_security_policy_headers()
    {
        $response = $this->get('/');

        $response->assertHeader('Content-Security-Policy');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
    }
}
