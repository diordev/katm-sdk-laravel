<?php

namespace Mkb\KatmSdkLaravel\Enums;

enum KatmInitClientEnum: string
{
    case PINFL              = 'pPinfl';
    case DOC_SERIES         = 'pDocSeries';
    case DOC_NUMBER         = 'pDocNumber';
    case FIRST_NAME         = 'pFirstName';
    case LAST_NAME          = 'pLastName';
    case MIDDLE_NAME        = 'pMiddleName';
    case BIRTH_DATE         = 'pBirthDate';
    case ISSUE_DOC_DATE     = 'pIssueDocDate';
    case EXPIRED_DOC_DATE   = 'pExpiredDocDate';
    case GENDER             = 'pGender';
    case DISTRICT_ID        = 'pDistrictId';
    case RES_ADDRESS        = 'pResAddress';
    case REG_ADDRESS        = 'pRegAddress';
    case PHONE              = 'pPhone';
    case EMAIL              = 'pEmail';

    /**
     * Barcha required fieldlarni olish
     */
    public static function required(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
