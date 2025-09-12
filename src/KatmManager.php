<?php

namespace Mkb\KatmSdkLaravel;

use Mkb\KatmSdkLaravel\Services\KatmCreditBanService;
use Mkb\KatmSdkLaravel\Services\KatmInitClientService;

/**
 * Barcha servislarni yagona joydan orkestratsiya qiladi.
 * Token oqimini bir joyda ushlab turadi.
 */
final class KatmManager
{
    public function __construct(
        protected KatmInitClientService $authService,
        protected KatmCreditBanService $creditBanService,
    ) {}

    /** Login — tokenni o‘rnatadi */
    public function login(): array
    {
        return $this->authService->auth();
    }

    /** Fuqaroni KATM servisedan ro'yhatdan o'tkazish — Bearer:'token' bilan ishlaydi */
    public function initClient(array $payload): array
    {
        return $this->authService->initClient($payload);
    }

    /** Fuqaro kredit olishni taqiqlashni activlashtirish — Bearer:'token' bilan ishlaydi */

    public function creditBanActive(array $payload, array $initClientPayload): array
    {
        return $this->creditBanService->creditBanActive($payload, $initClientPayload);
    }

    /** Fuqaro kredit taqiq status bilish — Bearer:'token' bilan ishlaydi */
    public function creditBanStatus(array $payload, array $initClientPayload): array
    {
        return $this->creditBanService->creditBanStatus($payload, $initClientPayload);
    }
}
