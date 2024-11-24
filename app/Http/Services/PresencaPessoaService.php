<?php

namespace App\Http\Services;

use App\Http\DTOs\PresencaIndividualDadosValidacaoDTO;
use App\Http\DTOs\PresencaIndividualDTO;
use App\Http\DTOs\PresencaPessoaDTO;
use App\Http\Repositories\PresencaPessoaRepository;
use App\Models\Chamada;
use App\Models\PresencaPessoa;
use App\Models\Sala;
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
                $presencaIndividual = new PresencaIndividualDTO($presenca['pessoa_id'], $presenca['funcao_id'], $presenca['presenca']);
                $this->marcarPresencaIndividual($presencaIndividual, $salaId, $tipoPresenca);
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

    public function marcarPresencaIndividual(PresencaIndividualDTO $presenca, int $salaId, int $tipoPresenca) : JsonResponse {
        try {
            $pessoaPresenteToday = $this->presencaPessoaRepository->findByPessoaIdAndToday($presenca->getPessoaId());

            if ($pessoaPresenteToday) {
               return $this->verifyPresenca($pessoaPresenteToday, $presenca, $salaId, $tipoPresenca);
            }

            $presencaPessoa = new PresencaPessoa;
            $presencaPessoa->pessoa_id = $presenca->getPessoaId();
            $presencaPessoa->sala_id = $salaId;
            $presencaPessoa->funcao_id = $presenca->getFuncaoId();
            $presencaPessoa->presente = $presenca->getPresenca();
            $presencaPessoa->tipo_presenca_id = $tipoPresenca;
            $presencaPessoa->save();

            if ($presenca->getPresenca()) {
                $this->adicionarPresencaInChamada($salaId, auth()->user()->congregacao_id);
            }

            return response()->json([
                'response' => 'Presença registrada com sucesso'
            ], 201);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new \Exception();
        }

    }

    public function verifyPresenca(PresencaPessoa $pessoaPresenteToday, PresencaIndividualDTO $presenca, int $salaId, int $tipoPresenca) : JsonResponse {
        if ($pessoaPresenteToday->presente) {
            return response()->json([
                'response' => 'A pessoa já se encontra como presente, portanto será inalterada'
            ], 403);
        } else {
            if ($presenca->getPresenca()) {
                $pessoaPresenteToday->sala_id = $salaId;
                $pessoaPresenteToday->funcao_id = $presenca->getFuncaoId();
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

    public function validatePresenca(int $salaId, PresencaIndividualDadosValidacaoDTO $dadosValidacao) : array {
        try {
            $response = [];
            $response['response'] = true;

            $codigoSala = Sala::findOrFail($salaId)->hash;

            if ($codigoSala != $dadosValidacao->getCodigo() || !$this->verificarLocalizacao($dadosValidacao->getLatitude(), $dadosValidacao->getLongitude(), -5.932785336998465, -35.29170830642453)) {
                $response['response'] = false;
                if ($codigoSala != $dadosValidacao->getCodigo()) {
                    $response['erros'][0] = 'Código não corresponde a sala';
                }
                if (!$this->verificarLocalizacao($dadosValidacao->getLatitude(), $dadosValidacao->getLongitude(), -5.932785336998465, -35.29170830642453)) {
                    $response['erros'][1] = 'Localização inválida. Fora do limite de distância';
                }
            }

            return $response;

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [
                'error' => 'Erro ao validar presença'
            ];
        }
    }

    function verificarLocalizacao($latitudeUsuario, $longitudeUsuario, $latitudeCongregacao, $longitudeCongregacao) : bool {
        $latitudeUsuario = deg2rad($latitudeUsuario);
        $longitudeUsuario = deg2rad($longitudeUsuario);
        $latitudeCongregacao = deg2rad($latitudeCongregacao);
        $longitudeCongregacao = deg2rad($longitudeCongregacao);

        $distancia = (6371 * acos( cos( $latitudeUsuario ) * cos( $latitudeCongregacao ) * cos( $longitudeCongregacao - $longitudeUsuario ) + sin( $latitudeUsuario ) * sin($latitudeCongregacao) ) );
        $distancia = number_format($distancia, 2, '.', '');

        if ($distancia > 0.1) {
            return false;
        }

        return true;
    }

}
