<?php

namespace Mkb\KatmSdkLaravel\Test\Feature;

use Mkb\KatmSdkLaravel\Facades\Katm;
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
}
