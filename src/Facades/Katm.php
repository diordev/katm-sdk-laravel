<?php

namespace Mkb\KatmSdkLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Mkb\KatmSdkLaravel\Responses\KatmResponseData login()
 * @method static \Mkb\KatmSdkLaravel\Responses\KatmResponseData init(array $payload)
 * @method static \Mkb\KatmSdkLaravel\Responses\KatmResponseData creditBan(array $activatePayload, array $initPayload)
 * @method static \Mkb\KatmSdkLaravel\Responses\KatmResponseData banStatus(array $payload)
 */
class Katm extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        // Provider’da shu key bilan singleton bog‘laymiz
        return 'katm.manager';
    }
}
