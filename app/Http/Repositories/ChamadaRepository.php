<?php

namespace App\Http\Repositories;

use App\Models\Chamada;
use Carbon\Carbon;
use FontLib\TrueType\Collection;

class ChamadaRepository
{
    public function findByCongregacaoAndMonthAndYearAndGroupByCreatedAt(int $congregacao, int $month, int $year) : ?Collection {
        return Chamada::select('sum(matriculados) as matriculados', 'sum(visitantes) as visitantes)', 'sum(biblias) as biblias', 'sum(revistas) as revistas', 'presentes + visitantes as assist_total')
            ->where('congregacao_id', $congregacao)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('created_at')
            ->get();
    }

    public function getChamadaToday($salaId) : ?Chamada {
        return Chamada::where('id_sala', '=', $salaId)
            ->whereDate('created_at', Carbon::today())
            ->first();
    }

}
