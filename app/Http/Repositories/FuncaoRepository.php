<?php

namespace App\Http\Repositories;

use App\Models\Funcao;
use Illuminate\Database\Eloquent\Collection;

class FuncaoRepository
{
    public function findAll() : ?Collection {
        return Funcao::all('id', 'nome');
    }
}
