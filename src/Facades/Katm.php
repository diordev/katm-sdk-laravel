<?php

namespace Mkb\KatmSdkLaravel\Fasades;

use Illuminate\Support\Facades\Facade;
use Mkb\KatmSdkLaravel\KatmManager;

class Katm extends Facade
{
    protected static function getFacadeAccessor()
    {
        return KatmManager::class;
    }
}
