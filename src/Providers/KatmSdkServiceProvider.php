<?php

namespace Mkb\KatmSdkLaravel\Providers;

use Illuminate\Support\ServiceProvider;

class KatmSdkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // bind/singleton lar shu yerda
        // $this->app->singleton(\Diordev\KatmSdkLaravel\KatmClient::class, fn() => new KatmClient(...));
    }

    public function boot(): void
    {
        // routes/config/migrations publish qilishni xohlasangiz shu yerda
        // $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        // $this->publishes([__DIR__.'/../../config/katm.php' => config_path('katm.php')], 'katm-config');
    }
}
