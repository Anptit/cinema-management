<?php

namespace App\Domain\Enums;

enum UserGender : string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';
    public static function values()
    {
        return [
            self::MALE->value,
            self::FEMALE->value,
            self::OTHER->value
        ];
    }
}