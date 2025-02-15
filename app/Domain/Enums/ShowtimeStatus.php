<?php 

namespace App\Domain\Enums;

enum ShowtimeStatus : string
{
    case NOW_SHOWING = 'now showing';
    case UPCOMING = 'upcoming';
    case SPECIAL = 'special';
    public static function values()
    {
        return [
            self::NOW_SHOWING->value,
            self::UPCOMING->value,
            self::SPECIAL->value
        ];
    }
}