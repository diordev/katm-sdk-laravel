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
    protected KatmAuthService $auth;
    protected ?string $token = null;

    public function __construct(?KatmAuthService $auth = null)
    {
        $this->auth = $auth ?? new KatmAuthService();
        $this->token = $this->auth->currentToken(); // cache’dan bo‘lsa ham oladi
    }

    /** Ichki: tokenni olish va sync qilish */
    protected function ensureToken(): KatmResponseData
    {
        // Allaqachon bor bo‘lsa — OK
        if ($this->token) {
            return KatmResponseData::fromApi([
                'data'    => ['accessToken' => $this->token],
                'error'   => null,
                'success' => true,
            ]);
        }

        // Yo‘q bo‘lsa — login
        $dto = $this->auth->login();
        if ($dto->isSuccess()) {
            $this->token = $dto->token();
        }

        return $dto;
    }

    /** Tashqi: login */
    public function login(): KatmResponseData
    {
        $dto = $this->auth->login();
        if ($dto->isSuccess()) {
            $this->token = $dto->token();
        }
        return $dto;
    }

    /** Tashqi: init-client */
    public function init(array $payload): KatmResponseData
    {
        $tokenDto = $this->ensureToken();
        if (!$tokenDto->isSuccess()) {
            return $tokenDto;
        }

        $svc = (new KatmInitClientService())->withBearer($this->token);
        return $svc->init($payload);
    }

    /**
     * Tashqi: credit ban activate
     * $activatePayload — /client/credit/ban/activate body
     * $initPayload     — /auth/init-client body (doimiy ishlatamiz, init DTO orqali tekshiramiz)
     */
    public function creditBan(array $activatePayload, array $initPayload): KatmResponseData
    {
        $tokenDto = $this->ensureToken();
        if (!$tokenDto->isSuccess()) {
            return $tokenDto;
        }

        // 1) doim init qilib olamiz (siz so‘ragancha initAndGetClientId yo‘q)
        $initSvc = (new KatmInitClientService())->withBearer($this->token);
        $initDto = $initSvc->init($initPayload);
        if (!$initDto->isSuccess()) {
            return $initDto;
        }

        // 2) activate
        $banSvc = (new KatmCreditBanService())->withBearer($this->token);
        return $banSvc->creditBan($activatePayload, $initPayload);
    }

    /** Tashqi: credit ban status */
    public function banStatus(array $payload): KatmResponseData
    {
        $tokenDto = $this->ensureToken();
        if (!$tokenDto->isSuccess()) {
            return $tokenDto;
        }

        $banSvc = (new KatmCreditBanService())->withBearer($this->token);
        return $banSvc->banStatus($payload);
    }
}
