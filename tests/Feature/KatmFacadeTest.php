<?php

namespace Mkb\KatmSdkLaravel\Test\Feature;

use Mkb\KatmSdkLaravel\Facades\Katm;
use Mkb\KatmSdkLaravel\Test\TestCase;

class KatmFacadeTest extends TestCase
{

    public function test_login()
    {
        $dto = Katm::login();
        $this->assertTrue($dto->isSuccess(), $dto->errorMessage() ?? 'Login failed');
    }

    public function test_init_client()
    {
        $dto = Katm::init($this->initClientPayload());
        // dd($dto);
        $this->assertTrue($dto->isSuccess(), $dto->errorMessage() ?? 'Init failed');
    }

    public function test_credit_ban_activate()
    {
        $dto = Katm::creditBan($this->creditBanActivatePayload(), $this->initClientPayload());
        // dd($dto);
        $this->assertTrue($dto->isSuccess(), $dto->errorMessage() ?? 'Logout failed');
    }

    public function test_credit_ban_status()
    {
        $dto = Katm::banStatus($this->creditBanStatusPayload());
        // dd($dto);
        $this->assertTrue($dto->isSuccess(), $dto->errorMessage() ?? 'Logout failed');
    }
}
