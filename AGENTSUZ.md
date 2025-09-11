# AGENTSUZ.md — Codex uchun yo‘riqnomalar

Ushbu ko‘rsatmalar butun repozitoriy uchun amal qiladi.

## Umumiy ma’lumot
- Loyiha turi: Laravel package (PHP 8.2), PSR-4 bilan `Mkb\\KatmSdkLaravel\\` namespace ostida.
- Testlar: PHPUnit + Orchestra Testbench. `@group live` bilan belgilangan Feature testlar haqiqiy tashqi API’ga murojaat qiladi.
- Kirish nuqtalari: Service Provider — `Mkb\\KatmSdkLaravel\\Providers\\KatmSdkServiceProvider`, Facade — `Mkb\\KatmSdkLaravel\\Facades\\Katm`.

## O‘rnatish
- Dev dependency o‘rnatish va autoload optimallashtirish:
  - `make dump-autoload-install`
- Kod o‘zgarganda autoload yangilash:
  - `make dump-autoload`

## Testlarni ishga tushirish
- Xavfsiz standart (tarmoqsiz):
  - `./vendor/bin/phpunit` (ba’zi Unit testlar ham tarmoqqa chiqishi mumkin; “Tarmoq va Live testlar” bo‘limiga qarang).
- Faqat Live Feature testlar:
  - `make unit-test` (`--group live` bilan). To‘g‘ri credential va tarmoq talab qilinadi.

## Tarmoq va Live testlar
- Testlarga tarmoq chaqiruvini kiritmang, faqat `@group live` bilan aniq belgilangan va environment tekshiruvlari bilan himoyalangan bo‘lsa — ruxsat.
- Unit testlar uchun HTTP’ni mocking/faking qiling. Real HTTP faqat `live` guruhidagi Feature testlarda.
- Yangi live test qo‘shsangiz, credential yetishmasa testni `markTestSkipped(...)` bilan o‘tkazib yuboring.

## Maxfiy ma’lumotlar
- Real credential va sirlarni commit qilmang. Hozirda `tests/TestCase.php` ichida qo‘lda kiritilgan qiymatlar bor — bu amaliyotni takrorlamang. Tahrir kerak bo‘lsa, ularni env orqali boshqaring.
- Token va parollarni log yoki exception matnida chiqarmang.

## Kod yozish uslubi
- PSR-4 va mavjud loyiha uslubiga rioya qiling; fayllar `src/` ichida namespace tuzilmasiga mos joylashsin.
- PSR-12 ni yo‘l-yo‘riq sifatida tuting; loyihada hozirgi uslubni saqlang (global o‘zgarishlar, masalan `declare(strict_types=1)`, faqat butun loyihaga izchil qo‘llaganda).
- Parametr va qaytish turlarini imkon qadar aniq belgilang.
- Ommaviy API’ni ehtiyot qiling (Facade metodlari, manager/service signaturalari). Breaking change kiritmang.
- Ma’noli istisnolar yarating; yangi xatolik turlari uchun `src/Exceptions/` ichida custom exception qo‘shing.

## Paket tuzilmasi
- `src/Providers/KatmSdkServiceProvider.php`: service binding va config publish. Yangi servis qo‘shganda aniq container key bilan singleton bog‘lang.
- `src/Facades/Katm.php`: ommaviy API. Yangi metod qo‘shilsa, facade docblock’ini ham yangilang.
- `config/katm.php`: standart konfiguratsiya. Yangi opsiyalarni shu yerga qo‘shing va `config('katm.key')` orqali o‘qing.
- `src/Services/*`: HTTP mantiq. So‘rov/surovlar va retry shu yerda.
- `src/Responses/*`: DTO’lar (`spatie/laravel-data`). Array bilan ishlashni shu qatlamda markazlashtiring.
- `src/Enums/*`: talab qilinadigan maydonlar va constant’lar.

## HTTP va Retry
- Umumiy mijoz mantiqidan foydalaning: `AbstractHttpClientService` (headers, retries, timeout, proxy).
- Child service ichida base URL, headers yoki retry sozlamalarini takrorlamang; mavjud helper’lar orqali moslang.
- Auth oqimlarini o‘zgartirganda `KatmAuthService` token caching xulqini saqlab qoling.

## Qaramliklar (Dependencies)
- Runtime dependency’larni `require`, test/dev vositalarini `require-dev` ga qo‘shing (`composer.json`).
- Laravel 10–12 bilan moslikni saqlang va dependency’larni minimal ushlab turing.
- O‘zgarishdan so‘ng `make dump-autoload` ishga tushiring.

## Hujjatlar
- Setup, config kalitlari yoki public API o‘zgarsa, `README.md` ni yangilang.
- Yangi config opsiyalari uchun mos env nomlarini ham hujjatlang.

## Versiyalash
- Bu library package. Breaking change bo‘lsa, `composer.json` ichida versiyani oshiring va `README.md` (yoki keyinchalik qo‘shilsa `CHANGELOG`) da qayd eting.

## Nimalar qilinmasin
- Bitta feature/fix ichida aloqasiz refaktorlarni commit qilmang.
- Mualliflik yoki license header’larni qo‘shmang.
- Unit testlarda uzoq ishlaydigan tashqi servislar yoki real tarmoqdan foydalanmang.

## Tezkor qo‘llanma
- Autoload optimize: `make dump-autoload`
- Dev vositalarni o‘rnatish: `make dump-autoload-install`
- PHPUnit ishga tushirish: `./vendor/bin/phpunit`
- Live Feature testlar: `make unit-test` (tarmoq + credential talab)

