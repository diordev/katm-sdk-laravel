<?php

namespace Mkb\KatmSdkLaravel\Services;

use Mkb\KatmSdkLaravel\Enums\KatmApiEndpointEnum;
use Mkb\KatmSdkLaravel\Enums\KatmAuthEnum;

class KatmAuthService extends AbstractHttpClientService
{
    public function auth(): array
    {
        $payload = [
            'login' => $this->username,
            'password' => $this->password
        ];

        $resp = $this->post(KatmApiEndpointEnum::Auth->value, $payload, KatmAuthEnum::AuthNone->value);
        $this->withBearer($resp['data']['accessToken'] ?? null);
        return $resp;
    }

}
