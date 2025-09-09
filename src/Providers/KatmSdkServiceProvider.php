<?php

namespace Mkb\KatmSdkLaravel\Providers;

use Illuminate\Support\ServiceProvider;
use Mkb\KatmSdkLaravel\KatmManager;

class KatmSdkServiceProvider extends ServiceProvider
{
   /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/katm.php', 'katm'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/katm.php' => config_path('katm.php'),
        ], 'katm-config');

        $this->app->singleton(KatmManager::class, function () {
            return new KatmManager();
        });
    }
}
