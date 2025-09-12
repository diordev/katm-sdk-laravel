<?php

namespace Mkb\KatmSdkLaravel\Services;

use Mkb\KatmSdkLaravel\Enums\KatmApiEndpointEnum;
use Mkb\KatmSdkLaravel\Enums\KatmAuthEnum;
use RuntimeException;


class KatmInitClientService extends KatmAuthService
{
    public function initClient(array $payload): array
    {
        $this->auth();
        $resp = $this->post(KatmApiEndpointEnum::AuthClient->value, $payload, KatmAuthEnum::AuthBearer->value);

        if (!($resp['success'] ?? false)) {
            $message = (string)($resp['error']['errMsg'] ?? 'Init client muvaffaqiyatsiz.');
            throw new RuntimeException($message, 400);
        }

        return $resp;
    }

}