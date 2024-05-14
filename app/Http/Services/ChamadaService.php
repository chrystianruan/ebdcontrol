<?php

namespace App\Http\Services;

use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use App\Models\ChamadaDiaCongregacao;
use App\Models\PresencaPessoa;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ChamadaService
{
    protected $chamadaDiaCongregacaoRepository;
    public function __construct(ChamadaDiaCongregacaoRepository $chamadaDiaCongregacaoRepository)
    {
        $this->chamadaDiaCongregacaoRepository = $chamadaDiaCongregacaoRepository;
    }

    public function classesNotSendChamada(Collection $salas, Collection $chamadas) : array {
        $classes = [];

        foreach($salas as $sala) {
            if (!$this->isPresentInChamadas($chamadas, $sala->id)){
                array_push($classes, $sala->nome);
            }
        }

        return $classes;
    }

    public function isPresentInChamadas(Collection $chamadas, int $itemId) {
        foreach ($chamadas as $c) {
            if ($itemId == $c->id_sala) {
                return true;
            }
        }

        return false;
    }

    public function convertToInt(object $request) : array {
        $values = [
            "presentes" => intval($request->presentes),
            "visitantes" => intval($request->visitantes),
            "assistenciaTotal" => intval($request->presentes+$request->visitantes),
            "revistas" => intval($request->revistas),
            "biblias" => intval($request->biblias)
        ];

        return $values;
    }

    public function validateRequest(array $data, int $matriculados) : string {
        $presentes = $data['presentes'];
        $visitantes = $data['visitantes'];
        $assistenciaTotal = $presentes+$visitantes;
        $biblias = $data['biblias'];
        $revistas = $data['revistas'];
         if (gettype($presentes) != "integer" || $presentes < 0 || $presentes > $matriculados) {
            return "Número de presentes inválido";
         }
         if (gettype($visitantes) != "integer" || $visitantes < 0) {
             return "Número de visitantes inválido";
         }
         if (gettype($biblias) != "integer" || $biblias < 0 || $biblias > $assistenciaTotal) {
             return "Número de Bíblias inválido";
         }
        if (gettype($revistas) != "integer" || $revistas < 0 || $revistas > $assistenciaTotal) {
            return "Número de revistas inválido";
         }
        return 0;
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

    public function marcarPresencasLote(array $presencas) {
        try {
            foreach(json_decode($presencas, true) as $presenca) {
                $this->marcarPresencaIndividual($presenca);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'response' => 'Não foi possível marcar a presença'
            ], 500);
        }

    }

    public function marcarPresencaIndividual($presenca) : JsonResponse {
        try {
            if ($this->checkPresencaExists($presenca)) {
                return response()->json([
                    'response' => 'Presença não pode ser registrada, pois já existe um registro para essa pessoa na data de hoje'
                ], 403);
            }

            $presencaPessoa = new PresencaPessoa;
            $presencaPessoa->pessoa_id = $presenca['pessoa_id'];
            $presencaPessoa->sala_id = $presenca['sala_id'];
            $presencaPessoa->funcao_id = $presenca['funcao_id'];
            $presencaPessoa->presente = $presenca['presente'];
            $presencaPessoa->tipo_presenca = $presenca['tipo_presenca'];
            $presencaPessoa->save();

            return response()->json([
                'response' => 'Presença registrada com sucesso'
            ], 201);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'response' => 'Não foi possível marcar a presença'
            ], 500);
        }

    }

    private function checkPresencaExists($presenca) : bool {
        return true;
    }
 }
