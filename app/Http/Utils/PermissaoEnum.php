<?php

namespace App\Http\Utils;

enum PermissaoEnum : int
{
    case SUPERMASTER = 1;
    case MASTER = 2;
    case ADMIN = 3;
    case CLASSE = 4;
    case COMUM = 5;
}
