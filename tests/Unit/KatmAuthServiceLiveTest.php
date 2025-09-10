<?php

namespace Mkb\KatmSdkLaravel\Test\Unit;

use Mkb\KatmSdkLaravel\Responses\KatmResponseData;
use Mkb\KatmSdkLaravel\Services\KatmAuthService;
use Mkb\KatmSdkLaravel\Test\TestCase;

class KatmAuthServiceLiveTest extends TestCase
{

    /**
     * @group unit
     */
    public function test_login_returns_token_and_uses_cache(): void
    {
        $svc = new KatmAuthService();

        $dto = $svc->login();
        $this->assertInstanceOf(KatmResponseData::class, $dto);
        $this->assertTrue($dto->isSuccess(), 'Login muvaffaqiyatsiz: ' . $dto->errorMessage());
        $this->assertNotEmpty($dto->token(), 'Token bo‘sh bo‘lmasin');

        $first = $dto->token();

        // ikkinchi marta cache’dan keladi
        $dto2 = $svc->login();
        $this->assertTrue($dto2->isSuccess());
        $this->assertSame($first, $dto2->token(), 'Cache tokeni mos kelmadi');
    }
}
