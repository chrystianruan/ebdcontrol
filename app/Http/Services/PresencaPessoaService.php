<?php

namespace App\Http\Services;

use App\Http\DTOs\PresencaPessoaDTO;
use App\Http\Repositories\PresencaPessoaRepository;
use App\Models\Chamada;
use App\Models\PresencaPessoa;
use Carbon\Carbon;
use Faker\Extension\ColorExtension;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PresencaPessoaService
{
    protected $presencaPessoaRepository;
    protected $relatorioService;
    protected $chamadaService;

    public function __construct(PresencaPessoaRepository $presencaPessoaRepository,
                                RelatorioService $relatorioService,
                                ChamadaService $chamadaService
                                ) {
        $this->presencaPessoaRepository = $presencaPessoaRepository;
        $this->relatorioService = $relatorioService;
        $this->chamadaService = $chamadaService;
    }
    public function marcarPresencasLote(string $presencas, $salaId, int $tipoPresenca) : JsonResponse {
        try {
            foreach(json_decode($presencas, true) as $presenca) {
                $this->marcarPresencaIndividual($presenca, $salaId, $tipoPresenca);
            }

            return response()->json([
                'response' => 'Presenças registradas com sucesso'
            ], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'response' => 'Não foi possível marcar a presença'
            ], 500);
        }

    }

    public function marcarPresencaIndividual(array $presenca, int $salaId, int $tipoPresenca) : JsonResponse {
        try {
            $pessoaPresenteToday = $this->presencaPessoaRepository->findByPessoaIdAndToday((int) $presenca['pessoa_id']);

            if ($pessoaPresenteToday) {
               return $this->verifyPresenca($pessoaPresenteToday, $presenca, $salaId, $tipoPresenca);
            }

            $presencaPessoa = new PresencaPessoa;
            $presencaPessoa->pessoa_id = $presenca['pessoa_id'];
            $presencaPessoa->sala_id = $salaId;
            $presencaPessoa->funcao_id = $presenca['funcao_id'];
            $presencaPessoa->presente = $presenca['presenca'];
            $presencaPessoa->tipo_presenca_id = $tipoPresenca;
            $presencaPessoa->save();

            if ((int) $presenca['presenca'] == 1) {
                $this->adicionarPresencaInChamada($salaId, auth()->user()->congregacao_id);
            }

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

    public function verifyPresenca(PresencaPessoa $pessoaPresenteToday, array $presenca, int $salaId, int $tipoPresenca) : JsonResponse {
        if ($pessoaPresenteToday->presente) {
            return response()->json([
                'response' => 'A pessoa já se encontra como presente, portanto será inalterada'
            ], 403);
        } else {
            if ((int) $presenca['presenca'] == 1) {
                $pessoaPresenteToday->sala_id = $salaId;
                $pessoaPresenteToday->funcao_id = $presenca['id_funcao'];
                $pessoaPresenteToday->tipo_presenca_id = $tipoPresenca;
                $pessoaPresenteToday->presente = 1;
                $pessoaPresenteToday->save();

                $this->adicionarPresencaInChamada($salaId, auth()->user()->congregacao_id);

                return response()->json([
                    'response' => 'Presença pré-existente marcada como falta, mas alterada com sucesso para presente'
                ], 201);
            }
        }
        return response()->json([
            'response' => 'Presença já existente e inalterada'
        ], 403);
    }

    public function adicionarPresencaInChamada(int $salaId, int $congregacaoId) : void {
        $chamada = Chamada::where('id_sala', '=', $salaId)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if (!$chamada) {
            $this->chamadaService->criarRegistroChamadaPresencaIndividual($salaId, $congregacaoId);
        } else {
            $chamada->presentes = $chamada->presentes + 1;
            $chamada->save();
        }
    }

    public function filter(PresencaPessoaDTO $presencaPessoa) : ?Collection {
        return $this->presencaPessoaRepository->findByMonthAndYearAndSalaId($presencaPessoa->getDataInicio(), $presencaPessoa->getDataFim(), $presencaPessoa->getSalaId(), $presencaPessoa->getOrderBy());
    }

}
