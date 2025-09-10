<?php

namespace Mkb\KatmSdkLaravel\Responses;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class TokenAccessData extends Data
{
    public function __construct(
        #[MapInputName('access_token')] // agar API "access_token" yuborsa ham mos tushadi
        public string $accessToken
    ) {}
}
