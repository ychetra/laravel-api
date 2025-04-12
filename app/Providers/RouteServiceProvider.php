<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
// use Illuminate\Cache\RateLimiting\Limit; // Uncomment if using RateLimiter
// use Illuminate\Http\Request; // Uncomment if using RateLimiter
// use Illuminate\Support\Facades\RateLimiter; // Uncomment if using RateLimiter

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        // Optional: Configure rate limiting if needed
        // RateLimiter::for('api', function (Request $request) {
        //     return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        // });

        // Let's try explicitly defining the routes here again.
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api') // Ensure the prefix is applied
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}