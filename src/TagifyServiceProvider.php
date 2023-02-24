<?php

namespace Usamamuneerchaudhary\LaravelTagify;

use Illuminate\Support\ServiceProvider;

class TagifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }
}
