<?php

namespace Mkb\KatmSdkLaravel\Enums;

enum KatmAuthEnum: string
{
    case AuthNone = 'none';
    case AuthBasic = 'basic';
    case AuthBearer = 'bearer';
}