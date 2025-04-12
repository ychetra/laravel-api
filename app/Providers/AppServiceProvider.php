<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register any custom services or bindings here
        // Example:
        // $this->app->bind(SomeInterface::class, SomeImplementation::class);
    }

    public function boot()
    {
        // Set the default string length for compatibility with older MySQL versions
        Schema::defaultStringLength(191);

        // Example of conditional logic based on environment
        if (app()->environment('production')) {
            // You could add production-specific logic here
            Schema::defaultStringLength(255);
        }

        // You can also add other bootstrapping logic like:
        // - Publish config or assets
        // - Register custom event listeners, etc.
    }
}
