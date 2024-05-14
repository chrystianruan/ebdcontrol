<?php

namespace App\Http\Repositories;

use App\Models\PresencaPessoa;

class PresencaRepository
{
    public function findByPessoaIdAndToday(int $pessoaId) :  ?PresencaPessoa {
        return PresencaPessoa::where('pessoa_id', $pessoaId)
            ->whereDate('created_at', date('Y-m-d'))
            ->first();
    }
}
