<?php

namespace Mkb\KatmSdkLaravel\Test\Unit;

use Mkb\KatmSdkLaravel\Responses\KatmResponseData;
use Mkb\KatmSdkLaravel\Services\KatmInitClientService;
use Mkb\KatmSdkLaravel\Test\TestCase;

class KatmInitClientServiceLiveTest extends TestCase
{
    // /** @group live */
    // public function test_init_client_with_real_api(): void
    // {
    //     $svc = new KatmInitClientService();

    //     $dto = $svc->init($this->initClientPayload());

    //     $this->assertInstanceOf(KatmResponseData::class, $dto, 'DTO qaytmadi');

    //     if ($dto->isSuccess()) {
    //         $data = $dto->getData() ?? [];
    //         $this->assertArrayHasKey('pClientId', $data, 'pClientId yo‘q');
    //         $this->assertNotEmpty($data['pClientId'], 'pClientId bo‘sh bo‘lmasin');
    //     } else {
    //         $this->fail('init-client muvaffaqiyatsiz: ' . $dto->errorMessage() . ' (errId=' . $dto->errorId() . ')');
    //     }
    // }
}
