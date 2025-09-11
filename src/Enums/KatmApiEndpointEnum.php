<?php

namespace Mkb\KatmSdkLaravel\Enums;

enum KatmEndpoitEnum: string
{
    case Auth = '/auth/login';
    case AuthClient = '/auth/init-client';
    case CreditBanActive = '/client/credit/ban/activate';
    case CreditBanStatus = '/client/credit/ban/activate';
}