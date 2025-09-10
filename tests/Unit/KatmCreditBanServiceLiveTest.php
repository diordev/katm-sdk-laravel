<?php

namespace Mkb\KatmSdkLaravel\Test\Unit;

use Mkb\KatmSdkLaravel\Responses\KatmResponseData;
use Mkb\KatmSdkLaravel\Services\KatmCreditBanService;
use Mkb\KatmSdkLaravel\Test\TestCase;

class KatmCreditBanServiceLiveTest extends TestCase
{

    // /** @group live */
    // public function test_credit_ban_active_with_real_api(): void
    // {
    //     $svc = new KatmCreditBanService();

    //     $dto = $svc->creditBan($this->creditBanActivatePayload(), $this->initClientPayload());

    //     $this->assertInstanceOf(KatmResponseData::class, $dto, 'DTO qaytmadi');

    //     if ($dto->isSuccess()) {
    //         $data = $dto->getData() ?? [];
    //         $this->assertArrayHasKey('resultMessage', $data, 'resultMessage yo‘q');
    //         $this->assertNotEmpty($data['resultMessage'], 'resultMessage bo‘sh bo‘lmasin');
    //     } else {
    //         $this->fail('credit-ban-active muvaffaqiyatsiz: ' . $dto->errorMessage() . ' (errId=' . $dto->errorId() . ')');
    //     }
    // }

    // /** @group live */
    // public function test_credit_ban_status_with_real_api(): void
    // {

    //     $svc = new KatmCreditBanService();

    //     $dto = $svc->banStatus($this->creditBanStatusPayload());

    //     $this->assertInstanceOf(KatmResponseData::class, $dto, 'DTO qaytmadi');

    //     if ($dto->isSuccess()) {
    //         $data = $dto->getData() ?? [];
    //         $this->assertArrayHasKey('resultMessage', $data, 'resultMessage yo‘q');
    //         $this->assertNotEmpty($data['resultMessage'], 'resultMessage bo‘sh bo‘lmasin');
    //     } else {
    //         $this->fail('credit-ban-status muvaffaqiyatsiz: ' . $dto->errorMessage() . ' (errId=' . $dto->errorId() . ')');
    //     }
    // }
}
