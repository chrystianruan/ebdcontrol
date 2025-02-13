<?php

namespace App\Http\Services;

use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use App\Http\Repositories\ChamadaRepository;
use App\Http\Repositories\PresencaPessoaRepository;
use App\Models\Chamada;
use App\Models\ChamadaDiaCongregacao;
use App\Models\Congregacao;
use App\Models\PresencaPessoa;
use App\Models\Sala;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ChamadaService
{
    protected $chamadaDiaCongregacaoRepository;
    protected $presencaPessoaRepository;
    protected $chamadaRepository;
    public function __construct(ChamadaDiaCongregacaoRepository $chamadaDiaCongregacaoRepository,
                                PresencaPessoaRepository $presencaRepository,
                                ChamadaRepository $chamadaRepository)
    {
        $this->chamadaDiaCongregacaoRepository = $chamadaDiaCongregacaoRepository;
        $this->presencaPessoaRepository = $presencaRepository;
        $this->chamadaRepository = $chamadaRepository;
    }

    public function classesNotSendChamada(Collection $salas, Collection $chamadas) : array {
        $classes = [];

        foreach($salas as $sala) {
            if (!$this->isPresentInChamadas($chamadas, $sala->id)){
                $classes[] = [
                    'id' => $sala->id,
                    'nome' => $sala->nome,
                ];
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

       foreach (Sala::where('congregacao_id', $congregacaoId)->get() as $sala) {
           $sala = Sala::find($sala->id);
           $sala->hash = bin2hex(random_bytes(2));
           $sala->save();
       }

        return response()->json([
            'response' => 'Chamada liberada para o dia escolhido'
        ], 201);
    }

    public function chamadasLiberadasMesAtual(int $congregacaoId, int $month) {
        return $this->chamadaDiaCongregacaoRepository->findChamadasLiberadasByCongregacaoAndMonth($congregacaoId, $month);
    }

    public function findByCongregacaoAndMonthAndYear(int $congregacaoId, int $month, int $year) {
        return $this->chamadaRepository->findByCongregacaoAndMonthAndYearAndGroupByCreatedAt($congregacaoId, $month, $year);
    }

    public function criarRegistroChamadaPresencaIndividual(int $salaId, int $congregacaoId, int $quantidadeMatriculados) : void {
       try {
           $chamada = new Chamada;
           $chamada->matriculados = $quantidadeMatriculados;
           $chamada->presentes = 1;
           $chamada->id_sala = $salaId;
           $chamada->congregacao_id = $congregacaoId;
           $chamada->save();
       } catch (\Exception $e) {
           Log::error($e->getMessage());
       }

    }

    public function existsChamadaToday(int $salaId) : bool {
        $chamada = Chamada::where('id_sala', '=', $salaId)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($chamada) {
            return true;
        }

        return false;
    }


 }
