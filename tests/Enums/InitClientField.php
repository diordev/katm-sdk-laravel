<?php
namespace Mkb\KatmSdkLaravel\Test\Enums;

enum InitClientField
{
    case Pinfl;
    case DocSeries;
    case DocNumber;
    case FirstName;
    case LastName;
    case MiddleName;
    case BirthDate;
    case IssueDocDate;
    case ExpiredDocDate;
    case Gender;
    case DistrictId;
    case ResAddress;
    case RegAddress;
    case Phone;
    case Email;

    public function meta(): array
    {
        return match($this) {
            self::Pinfl          => ['key' => 'pPinfl',          'value' => '30109951220071'],
            self::DocSeries      => ['key' => 'pDocSeries',      'value' => 'AD'],
            self::DocNumber      => ['key' => 'pDocNumber',      'value' => '1623289'],
            self::FirstName      => ['key' => 'pFirstName',      'value' => 'Diyorbek'],
            self::LastName       => ['key' => 'pLastName',       'value' => 'Abdumutalibov'],
            self::MiddleName     => ['key' => 'pMiddleName',     'value' => "Abdumutallib o'g'li"],
            self::BirthDate      => ['key' => 'pBirthDate',      'value' => '1995-09-01'],
            self::IssueDocDate   => ['key' => 'pIssueDocDate',   'value' => '2022-08-05'],
            self::ExpiredDocDate => ['key' => 'pExpiredDocDate', 'value' => '2032-08-04'],
            self::Gender         => ['key' => 'pGender',         'value' => 1],
            self::DistrictId     => ['key' => 'pDistrictId',     'value' => '1715'],
            self::ResAddress     => ['key' => 'pResAddress',     'value' => 'Dalvarzin MFY, 4-Tor X.Niyoziy, 19-uy'],
            self::RegAddress     => ['key' => 'pRegAddress',     'value' => 'Dalvarzin MFY, 4-Tor X.Niyoziy, 19-uy'],
            self::Phone          => ['key' => 'pPhone',          'value' => '+998999371010'],
            self::Email          => ['key' => 'pEmail',          'value' => 'test@examle.com'],
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
