<?php
namespace Mkb\KatmSdkLaravel\Test\Enums;

enum ConfigEnum
{
    case BaseUrl;
    case Username;
    case Password;
    case Timeout;
    case ConnectTimeout;
    case VerifySSL;

    /**
     * Konfiguratsiya kalit nomi (config('katm.*')).
     */
    public function key(): string
    {
        return match ($this) {
            self::BaseUrl        => 'base_url',
            self::Username       => 'username',
            self::Password       => 'password',
            self::Timeout        => 'timeout',
            self::ConnectTimeout => 'connect_timeout',
            self::VerifySSL      => 'verify_ssl',
        };
    }

    /**
     * Standart qiymat (mixed) — turli typelarni qo‘llab-quvvatlaydi.
     */
    public function defaults(): mixed
    {
        return match ($this) {
            // config/katm.php dagi defaultlarga mos
            self::BaseUrl        => 'https://www.example.com/api',
            self::Username       => 'admin',
            self::Password       => 'admin1234',
            self::Timeout        => 10,      // int
            self::ConnectTimeout => 5,       // int
            self::VerifySSL      => false,   // bool
        };
    }
}