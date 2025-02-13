<?php

namespace App\Domain\Enums;

enum UserRoles : string
{
    case Admin = 'admin';
    case Customer = 'customer';
    public static function values()
    {
        return [
            self::Admin->value,
            self::Customer->value,
        ];
    }
}