<?php

namespace Mkb\KatmSdkLaravel\Services;

use Mkb\KatmSdkLaravel\Responses\KatmJwtResponse;

class KatmAuthService extends BaseKatmService
{
    /**
     * /auth/login — Basic auth orqali access token olish.
     * Muvaffaqiyat bo‘lsa, tokenni ichki holatga yozib qo‘yadi (stateful).
     */
    public function login(?string $username = null, ?string $password = null): KatmJwtResponse
    {
        $u = $username ?? $this->username;
        $p = $password ?? $this->password;

        $res = $this->post('/auth/login', [
            'login'    => $u,
            'password' => $p,
        ], auth: 'basic');

        $dto = KatmJwtResponse::from($res);

        if ($dto->isSuccess() && $dto->token()) {
            $this->withBearer($dto->token());
        }

        return $dto;
    }

    /**
     * /auth/init-client — Bearer token bilan foydalanuvchini autentifikatsiya qilish.
     * Token login() chaqirilganda set bo‘ladi yoki tashqaridan withBearer() bilan beriladi.
     */
    public function initClient(array $payload): array
    {
        return $this->post('/auth/init-client', $payload, auth: 'bearer');
    }
}
