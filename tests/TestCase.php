<?php

namespace Mkb\KatmSdkLaravel\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use Mkb\KatmSdkLaravel\Providers\KatmSdkServiceProvider;
use Mkb\KatmSdkLaravel\Facades\Katm as KatmFacade;
use Spatie\LaravelData\LaravelDataServiceProvider;

abstract class TestCase extends Orchestra
{
    /** <<< REAL parametrlar (hardcoded) — ehtiyot bo‘ling! >>> */
    protected const BASE_URL  = 'https://ucin.infokredit.uz/api';
    protected const USERNAME  = 'mkbank';
    protected const PASSWORD  = ']bJ9i405#9GT5';

    protected const INIT_CLIENT_PAYLOAD = [
        'pPinfl'          => '30109951220071',
        'pDocSeries'      => 'AD',
        'pDocNumber'      => '1623289',
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
        'pPhone'          => '+998999371010',
        'pEmail'          => 'diordev@icloud.com',
    ];

    protected const CREDIT_BAN_ACTIVATE_PAYLOAD = [
        'pIdentifier'  => '30109951220071',
        'pFullName'    => "Abdumutalibov Diyorbek Abdumutallib o'g'li",
        'pIdenDate'    => '1995-09-01',
        'pSubjectType' => 2,
    ];

    protected const CREDIT_BAN_STATUS_PAYLOAD = [
        'pIdentifier'  => '30109951220071',
        'pSubjectType' => 2,
    ];

    /**
     * Paket providerlari.
     */
    protected function getPackageProviders($app)
    {
        return [
            KatmSdkServiceProvider::class,
            LaravelDataServiceProvider::class,
        ];
    }

    /**
     * Facade alias’lar.
     */
    protected function getPackageAliases($app)
    {
        return [
            'Katm' => KatmFacade::class,
        ];
    }

    /**
     * Test muhiti konfiguratsiyasi:
     * - Cache: array driver (RAM)
     * - KATM config: inline (env’siz)
     */
    protected function defineEnvironment($app): void
    {
        // Cache’ni RAM’da ushlaymiz (shu PHP jarayonida ishlaydi)
        $app['config']->set('cache.default', 'array');
        $app['config']->set('cache.stores.array', ['driver' => 'array']);

        // KATM config
        $app['config']->set('katm', [
            'base_url' => static::BASE_URL,
            'username' => static::USERNAME,
            'password' => static::PASSWORD,
            'timeout'  => 25,
            'headers'  => ['Accept' => 'application/json'],
            'retry'    => ['times' => 2, 'sleep_ms' => 300, 'when' => [429, 500, 502, 503, 504]],
        ]);
    }

    /* ---------- Qulay helperlar ---------- */

    /**
     * Init-client uchun tayyor payload (deep copy).
     */
    protected function initClientPayload(): array
    {
        return static::INIT_CLIENT_PAYLOAD;
    }

    /**
     * Credit-ban activate uchun tayyor payload (deep copy).
     */
    protected function creditBanActivatePayload(): array
    {
        return static::CREDIT_BAN_ACTIVATE_PAYLOAD;
    }

    /**
     * Credit-ban status uchun tayyor payload (deep copy).
     */
    protected function creditBanStatusPayload(): array
    {
        return static::CREDIT_BAN_STATUS_PAYLOAD;
    }
}
