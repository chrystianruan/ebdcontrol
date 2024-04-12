<?php

namespace App\Http\Repositories;

use App\Models\PessoaSala;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PessoaSalaRepository
{
    public function getSalasOfPessoa(int $pessoaId) : ?Collection {
        return PessoaSala::select('pessoa_salas.id', DB::raw('salas.id as sala_id'), DB::raw('funcaos.id as funcao_id'))
            ->where('pessoa_id', $pessoaId)
            ->join('salas', 'salas.id', '=', 'pessoa_salas.sala_id')
            ->join('funcaos', 'funcaos.id', '=', 'pessoa_salas.funcao_id')
            ->get();
    }

}
