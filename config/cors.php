<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */


    /*
    |--------------------------------------------------------------------------
    | CORS Paths
    |--------------------------------------------------------------------------
    |
    | This array contains the paths that should be accessible via CORS.
    | These paths will be prefixed with 'api/' by default.
    |
    */
    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed HTTP Methods
    |--------------------------------------------------------------------------
    |
    | This array contains the HTTP methods that are allowed when making cross-origin
    | requests. The wildcard * allows all methods. You can specify specific methods
    | like ['GET', 'POST', 'OPTIONS'] to be more restrictive.
    |
    */
    'allowed_methods' => [
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE',
        'OPTIONS',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins (Domains)
    |--------------------------------------------------------------------------
    |
    | This array contains the domains that are allowed to make cross-origin
    | requests. In production, you should specify the exact domains instead of using '*'
    | for better security.
    |
    | Format: ['https://example.com', 'https://api.example.com']
    |
    | You can also use environment variables:
    | - APP_URL is automatically included
    | - CORS_ALLOWED_ORIGINS can be a comma-separated list of domains
    |
    */
    'allowed_origins' => array_filter(array_merge(
        [
            env('APP_URL', 'http://localhost'),
            'http://localhost:3000', // Common frontend development port
            'http://127.0.0.1:3000',
        ],
        explode(',', env('CORS_ALLOWED_ORIGINS', ''))
    )),

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins Patterns
    |--------------------------------------------------------------------------
    |
    | Here you may specify regular expression patterns for allowed origins.
    | This is useful when you need to allow multiple subdomains.
    |
    | Example patterns:
    | - '^https?://(.*\.)?example\.com$'  # Matches example.com and all subdomains
    | - '^http://localhost(:[0-9]+)?$'     # Matches localhost with any port
    |
    */
    'allowed_origins_patterns' => [
        // Example: '^https?://(.*\.)?yourdomain\.com$',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | This array contains the headers that are allowed when making cross-origin
    | requests. It's recommended to specify only the headers you need.
    |
    */
    'allowed_headers' => [
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
        'Accept',
        'Origin',
        'DNT',
        'User-Agent',
        'If-Modified-Since',
        'Cache-Control',
        'X-Socket-Id',
    ],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    |
    | This array contains the headers that are exposed to the browser. These
    | headers will be included in the response when making cross-origin requests.
    |
    */
    'exposed_headers' => [
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
        'X-RateLimit-Limit',
        'X-RateLimit-Remaining',
        'X-RateLimit-Reset',
    ],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    |
    | This value sets the Access-Control-Max-Age header which specifies how long
    | the results of a preflight request can be cached. In seconds.
    |
    | Default: 2 hours (7200 seconds)
    | Set to 0 to disable caching (not recommended for production)
    |
    */
    'max_age' => env('CORS_MAX_AGE', 60 * 60 * 2), // 2 hours

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    |
    | This value determines if the browser should include credentials (cookies,
    | authorization headers, etc.) when making cross-origin requests.
    |
    | When set to true, you must also set 'allowed_origins' to specific domains
    | and not use wildcard '*' for origins.
    |
    */
    'supports_credentials' => env('CORS_SUPPORTS_CREDENTIALS', false),
];
