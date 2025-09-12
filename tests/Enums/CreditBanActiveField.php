<?php
namespace Mkb\KatmSdkLaravel\Test\Enums;

enum CreditBanActiveField
{
    case Identifier;
    case FullName;
    case BirthDate;
    case Subject;


    public function meta(): array
    {
        return match ($this) {
            self::Identifier    => ['key' => 'pIdentifier',     'value' => '30109951225171'],
            self::FullName      => ['key' => 'pFullName',       'value' => "Abdumutalibov Diyorbek Abdumutallib o'g'li"],
            self::BirthDate     => ['key' => 'pIdenDate',       'value' => '1995-09-01'],
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