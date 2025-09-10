<?php

namespace Mkb\KatmSdkLaravel\Test\Feature;

use Illuminate\Support\Facades\Http;
use Mkb\KatmSdkLaravel\Services\KatmAuthService;
use Mkb\KatmSdkLaravel\Test\TestCase;

class KatmAuthServiceTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        $app['config']->set('katm', [
            'base_url' => 'https://ucin.infokredit.uz/api',
            'username' => 'mkbank',
            'password' => ']bJ9i405#9GT5', // test qiymat, real emas
            'timeout'  => 10,
            'headers'  => ['Accept' => 'application/json'],
        ]);
    }

    public function test_login_and_init_client_flow(): void
    {
        Http::fake([
            // base_url bilan to'liq match:
            'ucin.infokredit.uz/api/auth/login' => Http::response([
                'data'    => ['accessToken' => 'fake-jwt-token-123'],
                'error'   => null,
                'success' => true,
                'total'   => null,
            ], 200),

            'ucin.infokredit.uz/api/auth/init-client' => Http::response([
                'data'    => ['pClientId' => 'G9900120240319132042'],
                'error'   => null,
                'success' => true,
                'total'   => null,
            ], 200),

            // kutilmagan chaqiruvlarni fail qildiramiz:
            '*' => Http::response(['unexpected' => true], 404),
        ]);

        $service = new KatmAuthService();

        $jwt = $service->login();
        $this->assertTrue($jwt->isSuccess());
        $this->assertSame('fake-jwt-token-123', $jwt->token());

        $payload = [
            'pPinfl'          => '23407590123456',
            'pDocSeries'      => 'AD',
            'pDocNumber'      => '4567890',
            'pFirstName'      => 'Diyorbek',
            'pLastName'       => 'Abdumutalibov',
            'pMiddleName'     => "Abdumutallib o'g'li",
            'pBirthDate'      => '1995-09-01',
            'pIssueDocDate'   => '2022-08-05',
            'pExpiredDocDate' => '2032-08-04',
            'pGender'         => 1,
            'pDistrictId'     => '1715',
            'pResAddress'     => 'Dalvarzin MFY, 4-Tor X.Niyoziy, 19-uy',
            'pRegAddress'     => 'Dalvarzin MFY, 4-Tor X.Niyoziy, 19-uy',
            'pPhone'          => '+998901234567',
            'pEmail'          => 'diordev@icloud.com',
        ];

        $resp = $service->initClient($payload);
        $this->assertTrue($resp['success'] ?? false);
        $this->assertSame('G9900120240319132042', $resp['data']['pClientId'] ?? null);
    }

    public function test_login_error_is_parsed(): void
    {
        Http::fake([
            'ucin.infokredit.uz/api/auth/login' => Http::response([
                'data'    => null,
                'error'   => [
                    'errId'      => 102,
                    'isFriendly' => true,
                    'errMsg'     => 'Пользователь не найден',
                ],
                'success' => false,
                'total'   => null,
            ], 200),
            '*' => Http::response(['unexpected' => true], 404),
        ]);

        $service = new KatmAuthService();
        $jwt = $service->login();

        $this->assertFalse($jwt->isSuccess());
        $this->assertSame('Пользователь не найден', $jwt->errorMessage());
        $this->assertNull($jwt->token());
    }
}
