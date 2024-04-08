<?php

namespace App\Http\Repositories;

use App\Models\PessoaSala;
use Illuminate\Database\Eloquent\Collection;

class PessoaSalaRepository
{
    public function getPessoaSala(int $pessoaId) : ?Collection {
        return PessoaSala::where('pessoa_id', $pessoaId)->get();
    }

}
