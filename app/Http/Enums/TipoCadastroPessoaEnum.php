<?php

namespace App\Http\Enums;

enum TipoCadastroPessoaEnum : int {
    case ADMIN = 1;
    case CLASSE = 2;
    case GERAL = 3;
}
