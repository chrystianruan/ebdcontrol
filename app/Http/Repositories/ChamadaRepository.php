<?php

namespace App\Http\Repositories;

use App\Models\Chamada;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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

    public function findByCreatedAtAndCongregacao(int $congregacao, string $date) : ?Collection {
        return Chamada::where('congregacao_id', $congregacao)
            ->whereDate('created_at', $date)
            ->get();
    }

    public function teste() {
        $admin = "teste";
        return $admin;
    }

    public function getSumOfChamadasFindByMesOrYear(int $mes = null, int $ano = null) : ?Collection {
        $relatorios = Chamada::selectRaw('sum(matriculados) as matriculados, sum(presentes) as presentes, sum(visitantes) as visitantes, sum(biblias) as biblias, sum(revistas) as revistas, created_at')
            ->where('congregacao_id', auth()->user()->congregacao_id);
        if ($mes) {
            $relatorios = $relatorios->whereMonth('created_at', $mes);
        }
        if ($ano) {
            $relatorios = $relatorios->whereYear('created_at', $ano);
        }

        $relatorios = $relatorios->orderBy('id', 'desc')
            ->groupBy(DB::raw('CAST(created_at AS DATE)'))
            ->get();

        return $relatorios;
    }

    public function getSumOfChamadasFindByCreatedAt(string $date) : ?Chamada {
        $relatorios = Chamada::selectRaw('sum(matriculados) as matriculados, sum(presentes) as presentes, sum(visitantes) as visitantes, sum(biblias) as biblias, sum(revistas) as revistas, created_at')
            ->where('congregacao_id', auth()->user()->congregacao_id)
            ->whereDate('created_at', $date)
            ->orderBy('id', 'desc')
            ->groupBy(DB::raw('CAST(created_at AS DATE)'))
            ->first();

        return $relatorios;
    }

}
