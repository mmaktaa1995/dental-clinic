<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicyHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only set CSP for HTML responses
        if (method_exists($response, 'header')) {
            // $csp = [
            //     "default-src 'self';",
            //     "script-src 'self' 'unsafe-inline' 'unsafe-eval' https:;",
            //     "style-src 'self' 'unsafe-inline' https:;",
            //     "img-src 'self' data: https:;",
            //     "font-src 'self' data: https:;",
            //     "connect-src 'self' https:;",
            //     "frame-src 'self';",
            //     "object-src 'none';",
            //     "base-uri 'self';",
            //     "form-action 'self';",
            //     "frame-ancestors 'none';",
            //     "block-all-mixed-content';",
            //     "upgrade-insecure-requests;",
            // ];

            // $response->headers->set(
            //     'Content-Security-Policy',
            //     implode(' ', $csp)
            // );

            // Additional security headers
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'DENY');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        }

        return $response;
    }
}
