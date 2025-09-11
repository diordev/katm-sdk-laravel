<?php
namespace Mkb\KatmSdkLaravel\Test\Enums;
enum CreditBanStatusField
{
    case Identifier;
    case Subject;

    public function meta(): array
    {
        return match ($this) {
            self::Identifier    => ['key' => 'pIdentifier',     'value' => '30109951220071'],
            self::Subject       => ['key' => 'pSubjectType',    'value' => 2],
        };

    }

    public static function defaults(): array
    {
        $result = [];
        foreach (self::cases() as $case) {
            $meta = $case->meta();
            $result[$meta['key']] = $meta['value'];
        }
        return $result;
    }
}