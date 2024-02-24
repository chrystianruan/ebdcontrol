<?php

namespace App\Http\Repositories;

use App\Models\ChamadaDiaCongregacao;

class ChamadaDiaCongregacaoRepository
{
    public function haveChamadaDayPerDateAndCongregacao(int $congregacaoId, string $date) : bool  {
        $chamadaDay = ChamadaDiaCongregacao::where("congregacao_id", $congregacaoId)
            ->where("date", $date)
            ->first();

        if ($chamadaDay) {
            return true;
        }

        return false;
    }

    public function findChamadasLiberadasByCongregacaoAndMonth(int $congregacaoId, int $month) {
        return ChamadaDiaCongregacao::selectRaw("id, DATE_FORMAT(date, '%d/%m') as date")
            ->where('congregacao_id', $congregacaoId)
            ->whereMonth('date', '=', $month)
            ->where('active', true)
            ->orderBy('date', 'desc')
            ->get();
    }
    public function findChamadaDiaToday(int $congregacaoId, string $date) {
        return ChamadaDiaCongregacao::where('congregacao_id', $congregacaoId)
            ->whereDate('date', '=', $date)
            ->where('active', true)
            ->first();
    }


}
