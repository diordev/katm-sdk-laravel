<?php

namespace Mkb\KatmSdkLaravel\Responses;

use Spatie\LaravelData\Data;

/**
 * KATM umumiy javobi:
 *
 * success holat:
 * {
 *   "data": {...},
 *   "error": false,
 *   "success": true,
 *   "total": null
 * }
 *
 * error holat:
 * {
 *   "data": null,
 *   "error": {"errId":118,"isFriendly":true,"errMsg":"..."},
 *   "success": false
 * }
 */
class KatmResponseData extends Data
{
    public function __construct(
        public object|array|null $data = null,
        public array|object|bool|null $error = null, // KATM: error = false | object
        public bool $success = false,
        public ?int $total = null,
    ) {}

    /** Foydali: xom javobdan DTO yaratish (error=false ni null ga normallashtiradi) */
    public static function fromApi(array $raw): self
    {
        $normalizedError = ($raw['error'] ?? null);
        if ($normalizedError === false) {
            $normalizedError = null;
        }

        return new self(
            data: $raw['data']   ?? null,
            error: $normalizedError,
            success: (bool)($raw['success'] ?? false),
            total: $raw['total']  ?? null,
        );
    }

    /** Muvaffaqiyat: success=true va data mavjud */
    public function isSuccess(): bool
    {
        return $this->success && $this->data !== null;
    }

    /** Qulay: data massiv ko‘rinishida */
    public function getData(): ?array
    {
        return self::toArrayOrNull($this->data);
    }

    /** Xatolik matni (bo‘lsa) */
    public function errorMessage(): ?string
    {
        if ($this->error === null || $this->error === false) {
            return null;
        }

        $arr = self::toArrayOrNull($this->error) ?? [];
        return $arr['errMsg'] ?? $arr['message'] ?? null;
    }

    /** Data ichidan token (accessToken | access_token) */
    public function token(): ?string
    {
        $arr = $this->getData() ?? [];
        return self::extractToken($arr);
    }

    /** Xatolik ID (bo‘lsa) */
    public function errorId(): ?int
    {
        if ($this->error === null || $this->error === false) return null;
        $arr = self::toArrayOrNull($this->error) ?? [];
        return isset($arr['errId']) ? (int)$arr['errId'] : null;
    }

    /** "isFriendly" bayroqchasi (bo‘lsa) */
    public function isFriendly(): ?bool
    {
        if ($this->error === null || $this->error === false) return null;
        $arr = self::toArrayOrNull($this->error) ?? [];
        return isset($arr['isFriendly']) ? (bool)$arr['isFriendly'] : null;
    }

    /** ---------- helpers ---------- */
    private static function toArrayOrNull(object|array|null $val): ?array
    {
        if ($val === null) return null;
        if (is_array($val)) return $val;
        if (is_object($val) && method_exists($val, 'toArray')) return $val->toArray();
        return json_decode(json_encode($val, JSON_UNESCAPED_UNICODE), true);
    }

    private static function extractToken(array $payload): ?string
    {
        if (isset($payload['accessToken']) && is_string($payload['accessToken'])) return $payload['accessToken'];
        if (isset($payload['access_token']) && is_string($payload['access_token'])) return $payload['access_token'];
        if (isset($payload['data']) && is_array($payload['data'])) return self::extractToken($payload['data']);
        return null;
    }
}
