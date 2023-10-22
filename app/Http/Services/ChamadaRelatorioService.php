<?php

namespace App\Http\Services;

use App\Models\Chamada;
use App\Models\Relatorio;
use Carbon\Carbon;

class ChamadaRelatorioService
{
    /**
     * @param object
     * @return string
     */
    public function saveRelatorio($chamada) {
        $chamadaToday = $this->haveChamadaInRelatorioToday();
        $chamadas = Chamada::select('chamadas.id', 'chamadas.created_at', 'salas.nome', 'matriculados', 'presentes', 'assist_total', 'visitantes', 'biblias', 'revistas')
            ->whereDate('chamadas.created_at', Carbon::today())
            ->where('chamadas.congregacao_id', '=', auth()->user()->congregacao_id)
            ->join('salas', 'chamadas.id_sala', '=', 'salas.id')
            ->get();


        $relatorio = new Relatorio;
        if (!$chamadaToday) {
            $relatorio->salas = $chamadas;
            $relatorio->matriculados = $chamada->matriculados;
            $relatorio->presentes =  $chamada->presentes;
            $relatorio->visitantes = $chamada->visitantes;
            $relatorio->assist_total = $chamada->assist_total;
            $relatorio->biblias = $chamada->biblias;
            $relatorio->revistas = $chamada->revistas;
            $relatorio->congregacao_id = auth()->user()->congregacao_id;
            $relatorio->save();

            return "Chamada salva e registro criado em relatório";
        }
        $ultimoRegistroRelatorio = $relatorio->ultimoRegistro();
        $ultimoRegistroRelatorio->salas = $chamadas;
        $ultimoRegistroRelatorio->matriculados = $ultimoRegistroRelatorio->matriculados + $chamada->matriculados;
        $ultimoRegistroRelatorio->presentes = $ultimoRegistroRelatorio->presentes + $chamada->presentes;
        $ultimoRegistroRelatorio->visitantes = $ultimoRegistroRelatorio->visitantes + $chamada->visitantes;
        $ultimoRegistroRelatorio->biblias = $ultimoRegistroRelatorio->biblias + $chamada->biblias;
        $ultimoRegistroRelatorio->revistas = $ultimoRegistroRelatorio->revistas + $chamada->revistas;
        $ultimoRegistroRelatorio->assist_total = $ultimoRegistroRelatorio->assist_total + $chamada->assist_total;
        $ultimoRegistroRelatorio->save();

        return "Chamada salva e registro atualizado em relatório";
    }


    /**
     * @return boolean
     */
    private function haveChamadaInRelatorioToday() {
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
