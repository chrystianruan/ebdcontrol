<?php

namespace App\Http\Repositories;

use App\Models\Sala;
use Illuminate\Database\Eloquent\Collection;

class SalaRepository
{
    public function findSalasByCongregacaoId(int $congregacaoId) : ?Collection{
        return Sala::select('id', 'nome')
            ->where('id', '>', 2)
            ->where('congregacao_id', $congregacaoId)
            ->get();
    }
}
