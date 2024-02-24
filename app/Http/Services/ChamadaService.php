<?php

namespace App\Http\Services;

use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use App\Models\ChamadaDiaCongregacao;

class ChamadaService
{
    protected $chamadaDiaCongregacaoRepository;
    public function __construct(ChamadaDiaCongregacaoRepository $chamadaDiaCongregacaoRepository)
    {
        $this->chamadaDiaCongregacaoRepository = $chamadaDiaCongregacaoRepository;
    }

    public function classesNotSendChamada(object $salas, object $chamadas) : array {
        $classes = [];

        foreach($salas as $sala) {
            foreach($chamadas as $chamada) {
                if ($sala->id != $chamada->id_sala) {
                    $classes[] = ''.$sala->nome;
                }
            }
        }

        return $classes;
    }

    public function liberarChamadaParaOutroDia(int $congregacaoId, string $date) {
        if ($this->chamadaDiaCongregacaoRepository->haveChamadaDayPerDateAndCongregacao($congregacaoId, $date)) {
            return response()->json([
                'response' => 'Já existe uma chamada para a congregação no dia escolhido'
            ], 403);
        }
        $chamadaDia = new ChamadaDiaCongregacao();
        $chamadaDia->congregacao_id = $congregacaoId;
        $chamadaDia->date = $date;
        $chamadaDia->active = true;
        $chamadaDia->save();

        return response()->json([
            'response' => 'Chamada liberada para o dia escolhido'
        ], 201);
    }

    public function chamadasLiberadasMesAtual(int $congregacaoId, int $month) {
        return $this->chamadaDiaCongregacaoRepository->findChamadasLiberadasByCongregacaoAndMonth($congregacaoId, $month);
    }
 }
