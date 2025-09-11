<?php

namespace Mkb\KatmSdkLaravel;

use Mkb\KatmSdkLaravel\Responses\KatmResponseData;
use Mkb\KatmSdkLaravel\Services\KatmAuthService;
use Mkb\KatmSdkLaravel\Services\KatmCreditBanService;
use Mkb\KatmSdkLaravel\Services\KatmInitClientService;

/**
 * Barcha servislarni yagona joydan orkestratsiya qiladi.
 * Token oqimini bir joyda ushlab turadi.
 */
class KatmManager
{
    protected KatmAuthService $user;
    protected ?string $token = null;

    public function __construct(?KatmAuthService $auth = null)
    {
        $this->user = $auth ?? new KatmAuthService();
    }

    /** Tashqi: login */
    public function login(): array
    {
        $resp = $this->user->auth();
        if ($resp["success"]) {
            $this->token = $resp['data']['accessToken'];
        }
        return $resp;
    }


}
