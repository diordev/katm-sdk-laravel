<?php

namespace Mkb\KatmSdkLaravel\Services;

use Mkb\KatmSdkLaravel\Enums\KatmApiEndpointEnum;
use Mkb\KatmSdkLaravel\Enums\KatmAuthEnum;
use RuntimeException;


class KatmAuthService extends AbstractHttpClientService
{
    public function auth(): array
    {
        $payload = [
            'login' => $this->username,
            'password' => $this->password
        ];
        $resp = $this->post(KatmApiEndpointEnum::Auth->value, $payload, KatmAuthEnum::AuthNone->value);

        if (!($resp['success'] ?? false)) {
            $message = (string)($resp['error']['errMsg'] ?? 'Login muvaffaqiyatsiz.');
            throw new RuntimeException($message, 401);
        }

        $this->withBearer($resp['data']['accessToken'] ?? null);
        return $resp;
    }

}
