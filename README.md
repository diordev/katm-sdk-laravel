# katm-sdk-laravel uchun yo‘riqnomalar

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
- Avval phpunit.xml ichidagi malumot tolring env ishlashi uchun
```xml
<php>
        <!-- live env (misol) -->
        <env name="KATM_BASE_URL" value='https://example.com/api'/>
        <env name="KATM_USERNAME"  value='admin'/>
        <env name="KATM_PASSWORD"  value="admin123"/>
</php>
```
- Xavfsiz standart (tarmoqsiz):
    - `./vendor/bin/phpunit` (ba’zi Unit testlar ham tarmoqqa chiqishi mumkin; “Unit va Feature testlar” bo‘limiga qarang).
- Faqat Live Feature testlar:
    - `make unit-test` (`--group live` bilan). To‘g‘ri credential va tarmoq talab qilinadi.


## Tezkor qo‘llanma
- Autoload optimize: `make dump-autoload`
- Dev vositalarni o‘rnatish: `make dump-autoload-install`
- phpunit.xml fayli o'zgartirish.
```xml
<php>
        <!-- live env (misol) -->
        <env name="KATM_BASE_URL" value='https://example.com/api'/>
        <env name="KATM_USERNAME"  value='admin'/>
        <env name="KATM_PASSWORD"  value="admin123"/>
</php>
```
- PHPUnit ishga tushirish: `./vendor/bin/phpunit`
- Live Feature testlar: `make unit-test` (tarmoq + credential talab)


