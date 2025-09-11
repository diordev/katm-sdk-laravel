<?php

namespace Mkb\KatmSdkLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Mkb\KatmSdkLaravel\Services\KatmAuthService auth()
 */
class Katm extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        // Provider’da shu key bilan singleton bog‘laymiz
        return 'katm.manager';
    }
}
