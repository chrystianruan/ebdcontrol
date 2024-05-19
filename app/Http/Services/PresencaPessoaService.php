<?php

namespace App\Http\Services;

use App\Http\Repositories\PresencaPessoaRepository;
use App\Models\PresencaPessoa;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PresencaPessoaService
{
    protected $presencaPessoaRepository;

    public function __construct(PresencaPessoaRepository $presencaPessoaRepository) {
        $this->presencaPessoaRepository = $presencaPessoaRepository;
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
            $pessoaPresenteToday = $this->presencaPessoaRepository->findByPessoaIdAndToday((int) $presenca['id']);

            if ($pessoaPresenteToday) {
                if ($pessoaPresenteToday->presente) {
                    return response()->json([
                        'response' => 'A pessoa já foi marcada como presente'
                    ], 403);
                } else {
                    if ((int) $presenca['presenca'] == 1) {
                        $pessoaPresenteToday->sala_id = $salaId;
                        $pessoaPresenteToday->funcao_id = $presenca['id_funcao'];
                        $pessoaPresenteToday->tipo_presenca_id = $tipoPresenca;
                        $pessoaPresenteToday->presente = 1;
                        $pessoaPresenteToday->save();
                        return response()->json([
                            'response' => 'Presença marcada com sucesso'
                        ], 201);
                    }
                }
            }
            $presencaPessoa = new PresencaPessoa;
            $presencaPessoa->pessoa_id = $presenca['id'];
            $presencaPessoa->sala_id = $salaId;
            $presencaPessoa->funcao_id = $presenca['id_funcao'];
            $presencaPessoa->presente = $presenca['presenca'];
            $presencaPessoa->tipo_presenca_id = $tipoPresenca;
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

}
