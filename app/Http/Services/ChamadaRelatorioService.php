<?php

namespace App\Http\Services;

use App\Models\Chamada;
use App\Models\Relatorio;
use Carbon\Carbon;

class ChamadaRelatorioService
{
    /**
     * @param object $chamada
     * @return string
     */
    public function saveRelatorio(object $chamada) : string {
        $chamadaToday = $this->haveChamadaInRelatorioToday();
        $chamadas = Chamada::select('chamadas.id', 'chamadas.created_at', 'salas.nome', 'matriculados', 'presentes', 'assist_total', 'visitantes', 'biblias', 'revistas')
            ->whereDate('chamadas.created_at', Carbon::today())
            ->where('chamadas.congregacao_id', '=', auth()->user()->congregacao_id)
            ->join('salas', 'chamadas.id_sala', '=', 'salas.id')
            ->get();

        $relatorio = new Relatorio;
        if (!$chamadaToday) {
            $relatorio->salas = $chamadas;
            $relatorio->matriculados = (int)$chamada->matriculados;
            $relatorio->presentes =  (int)$chamada->presentes;
            $relatorio->visitantes = (int)$chamada->visitantes;
            $relatorio->assist_total = (int)$chamada->assist_total;
            $relatorio->biblias = (int)$chamada->biblias;
            $relatorio->revistas = (int)$chamada->revistas;
            $relatorio->congregacao_id = auth()->user()->congregacao_id;
            $relatorio->save();

            return "Chamada salva e registro criado em relatório";
        }
        $ultimoRegistroRelatorio = $relatorio->ultimoRegistro();
        $ultimoRegistroRelatorio->salas = $chamadas;
        $ultimoRegistroRelatorio->matriculados = (int)$ultimoRegistroRelatorio->matriculados + (int)$chamada->matriculados;
        $ultimoRegistroRelatorio->presentes = (int)$ultimoRegistroRelatorio->presentes + (int)$chamada->presentes;
        $ultimoRegistroRelatorio->visitantes = (int)$ultimoRegistroRelatorio->visitantes + (int)$chamada->visitantes;
        $ultimoRegistroRelatorio->biblias = (int)$ultimoRegistroRelatorio->biblias + (int)$chamada->biblias;
        $ultimoRegistroRelatorio->revistas = (int)$ultimoRegistroRelatorio->revistas + (int)$chamada->revistas;
        $ultimoRegistroRelatorio->assist_total = (int)$ultimoRegistroRelatorio->assist_total + (int)$chamada->assist_total;
        $ultimoRegistroRelatorio->save();

        return "Chamada salva e registro criado em relatório";
    }


    /**
     * @return boolean
     */
    private function haveChamadaInRelatorioToday() : bool {
        $relatorio = new Relatorio;
        $ultimoRegistroRelatorio = $relatorio->ultimoRegistro();
        if ($ultimoRegistroRelatorio) {
            if (date('Y-m-d', strtotime($ultimoRegistroRelatorio->created_at)) == date('Y-m-d')) {
                return true;
            }
        }

        return false;
    }
}
