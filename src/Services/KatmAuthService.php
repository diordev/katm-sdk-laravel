<?php

namespace Mkb\KatmSdkLaravel\Services;

use Illuminate\Support\Facades\Cache;
use Mkb\KatmSdkLaravel\Enums\KatmApiEndpointEnum;
use Mkb\KatmSdkLaravel\Responses\KatmResponseData;

class KatmAuthService extends AbstractHttpClientService
{
    private const string CACHE_KEY = 'katm_sdk_token';
    protected ?string $token = null;


    protected function auth(): array
    {
        $payload = [
            'login' => $this->username,
            'password' => $this->password
        ];

        $resp = $this->post(KatmApiEndpointEnum::Auth->value, $payload);

        $this->withBearer($resp['data']['accessToken'] ?? null);
        return $resp;
    }

    /**
     * Login: token olish, cache’ga yozish, clientga Bearer qo‘shish.
     * Har doim KatmResponseData qaytaradi.
     */
    public function login(): KatmResponseData
    {
        // 1) Cache’dan bor tokenni ishlatish
        if ($cached = Cache::get(self::CACHE_KEY)) {
            $dto = KatmResponseData::fromApi([
                'data'    => ['accessToken' => $cached],
                'error'   => false,
                'success' => true,
                'total'   => null,
            ]);

            $this->token = $dto->token();
            $this->withBearer($dto->token());

            return $dto;
        }

        // 2) API orqali token olish
        $raw = $this->post('/auth/login', [
            'login'    => $this->username,
            'password' => $this->password,
        ], 'none');

        $dto = KatmResponseData::fromApi($raw);

        // 3) Muvaffaqiyatli bo‘lsa: tokenni saqlash va Bearer sozlash
        if ($dto->isSuccess() && $dto->token()) {
            $this->token = $dto->token();
            Cache::put(self::CACHE_KEY, $dto->token(), now()->addHours(2));
            $this->withBearer($dto->token());
        }

        return $dto;
    }

    /** Hozirgi token (property yoki cache’dan) */
    public function currentToken(): ?string
    {
        return $this->token ?? Cache::get(self::CACHE_KEY);
    }

    /** Ixtiyoriy: tokenni bekor qilish (cache + local) */
    public function forgetToken(): void
    {
        $this->token = null;
        Cache::forget(self::CACHE_KEY);
        // Bearer-ni tozalashni xohlasangiz, withBearer(null) qilishingiz mumkin:
        $this->withBearer(null);
    }
}
