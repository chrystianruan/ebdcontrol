<?php

namespace App\Http\Enums;

enum FuncaoEnum : int
{
    case ALUNO = 1;
    case PROFESSOR = 2;
    case SECRETARIO_CLASSE = 3;
    case SECRETARIO_ADMIN = 4;
    case PROFESSOR_SUBSTITUTO = 5;
    case AUXILIAR_SALA = 6;
}
