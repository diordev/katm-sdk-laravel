<?php

namespace Mkb\KatmSdkLaravel\Test\Feature;

use Mkb\KatmSdkLaravel\Facades\Katm;
use Mkb\KatmSdkLaravel\Test\Enums\CreditBanActiveField;
use Mkb\KatmSdkLaravel\Test\Enums\CreditBanStatusField;
use Mkb\KatmSdkLaravel\Test\Enums\InitClientField;
use Mkb\KatmSdkLaravel\Test\TestCase;


class KatmFacadeTest extends TestCase
{

    /**
     * @group live
     */
    public function test_login()
    {
        $resp = Katm::login();
        $this->assertTrue($resp['success'], $resp['error'] ?? 'Login failed');
    }

    /**
     * @group live
     */
    public function test_initClient()
    {
        $resp = Katm::initClient(InitClientField::defaults());
        $this->assertTrue($resp['success'], $resp['error'] ?? 'Init Client failed');
    }

    /**
     * @group live
     */
    public function test_creditBanActive()
    {
        $resp = Katm::creditBanActive(CreditBanActiveField::defaults(), InitClientField::defaults());
        $this->assertTrue($resp['success'], $resp['error'] ?? 'Init Client failed');
    }

    /**
     * @group live
     */
    public function test_creditBanStatus()
    {
        $resp = Katm::creditBanStatus(CreditBanStatusField::defaults(), InitClientField::defaults());
        $this->assertTrue($resp['success'], $resp['error'] ?? 'Init Client failed');
    }


}
