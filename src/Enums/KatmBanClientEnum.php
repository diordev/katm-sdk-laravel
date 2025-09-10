<?php

namespace Mkb\KatmSdkLaravel\Enums;

enum KatmBanClientEnum: string
{
    case PINFL              = 'pIdentifier';
    case FULL_NAME          = 'pFullName';
    case BIRTH_DATE         = 'pIdenDate';
    case SUBJECT_TYPE       = 'pSubjectType';

    /**
     * Barcha required fieldlarni olish
     */
    public static function required(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
