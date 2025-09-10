<?php

namespace Mkb\KatmSdkLaravel\Providers;

use Illuminate\Support\ServiceProvider;
use Mkb\KatmSdkLaravel\KatmManager;

class KatmSdkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Config merge (agar hali mavjud bo‘lsa)
        $this->mergeConfigFrom(__DIR__ . '/../../config/katm.php', 'katm');

        // Manager singleton
        $this->app->singleton('katm.manager', function ($app) {
            return new KatmManager();
        });
    }

    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/katm.php' => config_path('katm.php'),
        ], 'katm-config');
    }
}
