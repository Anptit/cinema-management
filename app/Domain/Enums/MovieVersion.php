<?php

namespace App\Domain\Enums;

enum MovieVersion : string
{
    case SUBTITLED_2D = '2d subtitled';
    case DUBBED_2D = '2d dubbed';
    case SUBTITLED_3D = '3d subtitled';
    case DUBBED_3D = '3d dubbed';
    public static function values()
    {
        return [
            self::SUBTITLED_2D->value,
            self::DUBBED_2D->value,
            self::SUBTITLED_3D->value,
            self::DUBBED_3D->value
        ];
    }
}