<?php

namespace App\Domain\Enums;

enum SeatStatus : string
{
    case EMPTY = 'empty';
    case SELECTING = 'selecting';
    case HOLDING = 'holding';
    case SOLED = 'soled';
    case BOOKED = 'booked';

    public static function value()
    {
        return [
            self::EMPTY->value,
            self::SELECTING->value,
            self::HOLDING->value,
            self::SOLED->value,
            self::BOOKED->value
        ];
    }
}