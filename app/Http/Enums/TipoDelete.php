<?php

namespace App\Http\Enums;

enum TipoDelete : int
{
    case FORCE = 1;
    case SOFT = 2;
}
