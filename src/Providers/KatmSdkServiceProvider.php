<?php

namespace Mkb\KatmSdkLaravel\Providers;

use Illuminate\Support\ServiceProvider;
use Mkb\KatmSdkLaravel\KatmManager;
use Mkb\KatmSdkLaravel\Services\KatmCreditBanService;
use Mkb\KatmSdkLaravel\Services\KatmInitClientService;

class KatmSdkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/katm.php', 'katm');

        // Manager singleton
        $this->app->singleton('katm.manager', function ($app) {
            return new KatmManager(
                $app->make(KatmInitClientService::class),
                $app->make(KatmCreditBanService::class),
            );
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
