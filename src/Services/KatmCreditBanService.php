<?php

namespace Mkb\KatmSdkLaravel\Services;

use Mkb\KatmSdkLaravel\Enums\KatmApiEndpointEnum;
use Mkb\KatmSdkLaravel\Enums\KatmAuthEnum;
use RuntimeException;


class KatmCreditBanService extends KatmAuthService
{

    public function creditBanActive(array $payload): array
    {
        $this->auth();
        $resp = $this->post(KatmApiEndpointEnum::CreditBanActive->value, $payload, KatmAuthEnum::AuthBearer->value);

        if (!($resp['success'] ?? false)) {
            $msg = (string)($resp['error']['errMsg'] ?? 'Credit ban active muvaffaqiyatsiz.');
            throw new RuntimeException($msg, 400);
        }

        return $resp;
    }

    public function creditBanStatus(array $payload): array
    {
        $this->auth();
        $resp = $this->post(KatmApiEndpointEnum::CreditBanStatus->value, $payload, KatmAuthEnum::AuthBearer->value);

        if (!($resp['success'] ?? false)) {
            $msg = (string)($resp['error']['errMsg'] ?? 'Credit ban status muvaffaqiyatsiz.');
            throw new RuntimeException($msg, 400);
        }

        return $resp;
    }

}