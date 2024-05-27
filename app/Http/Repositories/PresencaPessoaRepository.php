<?php

namespace App\Http\Repositories;

use App\Models\PresencaPessoa;
use FontLib\TrueType\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class PresencaPessoaRepository
{
    public function findByPessoaIdAndToday(int $pessoaId) :  ?PresencaPessoa {
        return PresencaPessoa::where('pessoa_id', $pessoaId)
            ->whereDate('created_at', date('Y-m-d'))
            ->first();
    }

    public function findByPessoaId(int $pessoaId) : ?Collection {
        return PresencaPessoa::where('pessoa_id', $pessoaId)
            ->get();
    }

    public function filterPresencas(int $salaId, int $month, int $year, string $createdAt, int $tipoPresenca, int $funcaoId, bool $presente) :  ?Collection {
        $presencas = PresencaPessoa::select('presenca_pessoas.*');
        if ($salaId) {
            $presencas = $presencas->where('sala_id', $salaId);
        }
        if ($createdAt) {
            $presencas = $presencas->whereDate('created_at', $createdAt);
        }
        if ($tipoPresenca) {
            $presencas = $presencas->where('tipo_presenca', $tipoPresenca);
        }
        if ($month && $year) {
            $presencas = $presencas->whereMonth('created_at', $month)
                ->whereYear('created_at', $year);
        }
        if ($funcaoId) {
            $presencas = $presencas->where('funcao_id', $funcaoId);
        }
        if ($presente) {
            $presencas = $presencas->where('presente', $presente);
        }

        return $presencas->get();

    }


}
