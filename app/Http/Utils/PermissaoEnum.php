<?php

namespace App\Http\Utils;

enum PermissaoEnum : int
{
    case SUPERMASTER = 1;
    case MASTER = 2;
    case ADMIN = 3;
    case CLASSE = 4;
    case COMUM = 5;

    public static function labels(): array
    {
        return [
            self::SUPERMASTER->value => 'Supermaster',
            self::MASTER->value => 'Master',
            self::ADMIN->value => 'Admin',
            self::CLASSE->value => 'Classe',
            self::COMUM->value => 'Comum',
        ];
    }
}
