<?php

namespace Mkb\KatmSdkLaravel\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use Mkb\KatmSdkLaravel\Providers\KatmSdkServiceProvider;
use Mkb\KatmSdkLaravel\Facades\Katm as KatmFacade;
use Spatie\LaravelData\LaravelDataServiceProvider;


abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            KatmSdkServiceProvider::class,
            LaravelDataServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Katm' => KatmFacade::class,
        ];
    }
}
