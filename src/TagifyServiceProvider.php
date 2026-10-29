<?php

declare(strict_types=1);

namespace Usamamuneerchaudhary\LaravelTagify;

use Illuminate\Support\ServiceProvider;

class TagifyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }
}
