<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware(['api', 'set_language'])
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            // Load test routes only in local environment
            if (app()->environment('local')) {
                require base_path('routes/test-routes.php');
            }
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        // General API rate limiting (applied to all API routes)
        RateLimiter::for('api', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(100)->by('user:' . $request->user()->id)
                : Limit::perMinute(30)->by($request->ip());
        });

        // Stricter rate limiting for authentication endpoints
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // Rate limiting for file uploads
        RateLimiter::for('uploads', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(20)->by('user:' . $request->user()->id)
                : Limit::perMinute(5)->by($request->ip());
        });
    }
}
