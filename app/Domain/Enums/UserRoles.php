<?php

namespace App\Domain\Enums;

enum UserRoles : string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';
    public static function values()
    {
        return [
            self::ADMIN->value,
            self::CUSTOMER->value,
        ];
    }
}