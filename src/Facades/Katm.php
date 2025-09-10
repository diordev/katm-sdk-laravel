<?php

namespace Mkb\KatmSdkLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use Mkb\KatmSdkLaravel\KatmManager;

class Katm extends Facade
{
    protected static function getFacadeAccessor()
    {
        return KatmManager::class;
    }
}
