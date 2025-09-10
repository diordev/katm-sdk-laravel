<?php

namespace Mkb\KatmSdkLaravel\Responses;

use Spatie\LaravelData\Data;

class KatmJwtResponse extends Data
{
    public function __construct(
        public ?TokenAccessData $data = null,
        public ?KatmErrorData $error = null,
        public bool $success = false,
        public ?int $total = null,   // ba'zi endpointlarda bo‘lmasligi mumkin
        public ?int $code = null     // agar backend code yuborsa (har doim ham bo‘lmasligi mumkin)
    ) {}

    /** Operativ tekshiruv: muvaffaqiyatli va token bor */
    public function isSuccess(): bool
    {
        return $this->success && $this->data !== null;
    }

    /** Qulay o‘qish: access token qiymati (muvaffaqiyat bo‘lsa) */
    public function token(): ?string
    {
        return $this->data?->accessToken;
    }

    /** Qulay o‘qish: xatolik matni (bo‘lsa) */
    public function errorMessage(): ?string
    {
        return $this->error?->errMsg;
    }
}
