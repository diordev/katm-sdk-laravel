<?php

namespace Mkb\KatmSdkLaravel\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use Mkb\KatmSdkLaravel\Providers\KatmSdkServiceProvider;
use Mkb\KatmSdkLaravel\Facades\Katm as KatmFacade;
use Spatie\LaravelData\LaravelDataServiceProvider;

use Mkb\KatmSdkLaravel\Test\Enums\InitClientField;
use Mkb\KatmSdkLaravel\Test\Enums\CreditBanActiveField;
use Mkb\KatmSdkLaravel\Test\Enums\CreditBanStatusField;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            KatmSdkServiceProvider::class,
            LaravelDataServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Katm' => KatmFacade::class,
        ];
    }

    /**
     * Test muhiti:
     * - Cache: array (RAM)
     * - config('katm'): ServiceProvider merge qilgan holicha (o‘zgarmas)
     */
    protected function defineEnvironment($app): void
    {
        // Cache’ni RAMga — testlar izolyatsiya qilinadi, disk/redis talab qilinmaydi
        $app['config']->set('cache.default', 'array');
        $app['config']->set('cache.stores.array', ['driver' => 'array']);

        // Muhim: katm config’iga TEGMAYMIZ — real katm.php + .env/.env.testing ishlaydi
        // (KatmSdkServiceProvider::class mergeConfigFrom(...) qiladi.)
        $this->assertKatmConfigLoaded($app);
    }

    /** config/katm.php haqiqatdan merge bo‘lganini tekshirib, erta fail ko‘rsatadi */
    private function assertKatmConfigLoaded($app): void
    {
        $cfg = (array) $app['config']->get('katm', []);
        if ($cfg === []) {
            $this->fail('config("katm") bo‘sh. ServiceProvider mergeConfigFrom ishlamagan yoki katm.php topilmadi.');
        }
    }

    /* ---------- Qulay helperlar (enumlardan to‘g‘ridan-to‘g‘ri) ---------- */

    protected function initClientPayload(): array
    {
        return InitClientField::defaults();
    }

    protected function creditBanActivatePayload(): array
    {
        return CreditBanActiveField::defaults();
    }

    protected function creditBanStatusPayload(): array
    {
        return CreditBanStatusField::defaults();
    }
}
