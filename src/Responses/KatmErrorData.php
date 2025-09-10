<?php

namespace Mkb\KatmSdkLaravel\Responses;

use Spatie\LaravelData\Data;

class KatmErrorData extends Data
{
    public function __construct(
        public ?int $errId = null,
        public ?bool $isFriendly = null,
        public ?string $errMsg = null
    ) {}
}
