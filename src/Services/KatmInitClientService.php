<?php

namespace Mkb\KatmSdkLaravel\Services;

use InvalidArgumentException;
use Mkb\KatmSdkLaravel\Enums\KatmGender;
use Mkb\KatmSdkLaravel\Enums\KatmInitClientEnum;
use Mkb\KatmSdkLaravel\Responses\KatmResponseData;

class KatmInitClientService extends KatmAuthService
{

    private const INIT_CLIENT = '/auth/init-client';
    /**
     * Foydalanuvchini KATM’da init qilish.
     * - Majburiy maydonlar tekshiriladi (enum)
     * - Token bo‘lmasa login() chaqiriladi
     * - Bearer bilan POST yuboriladi
     * - Har doim KatmResponseData qaytaradi
     */
    public function init(array $payload): KatmResponseData
    {
        $this->validatePayload($payload);

        // 1)  Token yo‘q bo‘lsa — login(); login() Bearer’ni o‘zi o‘rnatadi
        if (!$this->currentToken()) {
            $loginDto = $this->login();
            if (!$loginDto->isSuccess()) {
                // Login muvaffaqiyatsiz — shu xatoni yuqoriga DTO sifatida qaytaramiz
                return $loginDto;
            }
        }

        // API chaqiriq (Bearer bilan)
        $raw = $this->post(Self::INIT_CLIENT, $payload, 'bearer');

        return KatmResponseData::fromApi($raw);
    }

    /**
     * Boshqa servicelar uchun: init() natijasidan pClientId ni olish:
     */
    public function initAndGetClientId(array $payload): ?string
    {
        $dto = $this->init($payload);
        if (!$dto->isSuccess()) {
            return null;
        }

        $data = $dto->getData() ?? [];
        // KATM javobida odatda: ["data" => ["pClientId" => "..."]]
        return $data['pClientId'] ?? null;
    }

    /**
     * Minimal validatsiya:
     *  - Barcha required fieldlar bor-yo‘qligi (enum)
     *  - pGender qiymati KatmGender enumiga mosligi
     *  - Bo‘sh stringlar rad etiladi
     */
    protected function validatePayload(array $payload): void
    {
        // 1) Required’lar
        foreach (KatmInitClientEnum::required() as $field) {
            if (!array_key_exists($field, $payload)) {
                throw new InvalidArgumentException("Majburiy maydon yo‘q: {$field}");
            }
            if (is_string($payload[$field]) && trim($payload[$field]) === '') {
                throw new InvalidArgumentException("Maydon bo‘sh bo‘lmasin: {$field}");
            }
        }

        // 2) pGender enum tekshiruvi
        $gender = $payload[KatmInitClientEnum::GENDER->value] ?? null;
        $allowed = array_map(fn($c) => $c->value, KatmGender::cases());
        if (!in_array($gender, $allowed, true)) {
            $list = implode(', ', $allowed);
            throw new InvalidArgumentException("pGender noto‘g‘ri. Ruxsat etilgan: {$list}");
        }
    }
}
