<?php
//
//namespace Mkb\KatmSdkLaravel\Services;
//
//use InvalidArgumentException;
//use Mkb\KatmSdkLaravel\Enums\KatmSubjectType;
//use Mkb\KatmSdkLaravel\Enums\KatmBanClientEnum;
//use Mkb\KatmSdkLaravel\Responses\KatmResponseData;
//
//class KatmCreditBanService extends KatmAuthService
//{
//    private const EP_ACTIVATE = '/client/credit/ban/activate';
//    private const EP_STATUS   = '/client/credit/ban/status';
//
//    /**
//     * Kredit bo‘yicha ban’ni faollashtirish:
//     * 1) Token yo‘q bo‘lsa login()
//     * 2) KatmInitClientService::init($initPayload) — muvaffaqiyat bo‘lsa davom
//     * 3) /client/credit/ban/activate ga bearer bilan POST
//     */
//    public function creditBan(array $activatePayload, array $initPayload): KatmResponseData
//    {
//        // Validate activatePayload
//        $this->validatePayload($activatePayload);
//
//        // 1) Token yo‘q bo‘lsa — login(); login() Bearer’ni o‘zi o‘rnatadi
//        if (!$this->currentToken()) {
//            $loginDto = $this->login();
//            if (!$loginDto->isSuccess()) {
//                return $loginDto;
//            }
//        }
//
//        // 2) Init (faqat init() ni chaqiramiz, DTO bilan tekshiramiz)
//        $initService = (new KatmInitClientService())->withBearer($this->currentToken());
//        $initDto     = $initService->init($initPayload);
//
//        if (!$initDto->isSuccess()) {
//            // init xatoni aynan o‘zi bilan qaytaramiz
//            return $initDto;
//        }
//
//        // 3) Activate (bearer bilan)
//        $raw = $this->post(self::EP_ACTIVATE, $activatePayload, 'bearer');
//
//        return KatmResponseData::fromApi($raw);
//    }
//
//    /**
//     * Kredit ban statusini tekshirish (bearer bilan POST)
//     */
//    public function banStatus(array $payload): KatmResponseData
//    {
//        if (!$this->currentToken()) {
//            $loginDto = $this->login();
//            if (!$loginDto->isSuccess()) {
//                return $loginDto;
//            }
//        }
//
//        $raw = $this->post(self::EP_STATUS, $payload, 'bearer');
//
//        return KatmResponseData::fromApi($raw);
//    }
//
//    /**
//     * Minimal validatsiya:
//     *  - Barcha required fieldlar bor-yo‘qligi (enum)
//     *  - pSubjectType qiymati KatmSubjectType enumiga mosligi
//     *  - Bo‘sh stringlar rad etiladi
//     */
//    protected function validatePayload(array $payload): void
//    {
//        // 1) Required’lar
//        foreach (KatmBanClientEnum::required() as $field) {
//            if (!array_key_exists($field, $payload)) {
//                throw new InvalidArgumentException("Majburiy maydon yo‘q: {$field}");
//            }
//            if (is_string($payload[$field]) && trim($payload[$field]) === '') {
//                throw new InvalidArgumentException("Maydon bo‘sh bo‘lmasin: {$field}");
//            }
//        }
//
//        // 2) pSubjectType enum tekshiruvi
//        $gender = $payload[KatmBanClientEnum::SUBJECT_TYPE->value] ?? null;
//        $allowed = array_map(fn($c) => $c->value, KatmSubjectType::cases());
//        if (!in_array($gender, $allowed, true)) {
//            $list = implode(', ', $allowed);
//            throw new InvalidArgumentException("pSubjectType noto‘g‘ri. Ruxsat etilgan: {$list}");
//        }
//    }
//}
