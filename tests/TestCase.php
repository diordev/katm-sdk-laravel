<?php

namespace Mkb\KatmSdkLaravel\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use Mkb\KatmSdkLaravel\Providers\KatmSdkServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            KatmSdkServiceProvider::class,
            LaravelDataServiceProvider::class, // <— MUHIM
        ];
    }
}
