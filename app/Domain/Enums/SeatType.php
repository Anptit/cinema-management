<?php

namespace App\Domain\Enums;

enum SeatType : string
{
    case NORMAL = 'normal';
    case VIP = 'vip';
    case DOUBLE = 'double';

    public static function valueS()
    {
        return [
            self::NORMAL->value,
            self::VIP->value,
            self::DOUBLE->value
        ];
    }
}