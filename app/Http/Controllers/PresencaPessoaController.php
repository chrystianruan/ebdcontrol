<?php

namespace App\Http\Controllers;

use App\Http\DTOs\PresencaIndividualDTO;
use App\Http\DTOs\PresencaPessoaDTO;
use App\Http\DTOs\PresencaIndividualDadosValidacaoDTO;
use App\Http\Enums\orderBy;
use App\Http\Enums\TipoPresenca;
use App\Http\Repositories\PessoaRepository;
use App\Http\Repositories\SalaRepository;
use App\Http\Requests\PresencaIndividualRequest;
use App\Http\Services\PresencaPessoaService;
use App\Models\PessoaSala;
use App\Models\Sala;
use Cassandra\Column;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PresencaPessoaController extends Controller
{
    private $presencaPessoaService;
    private $salaRepository;
    private $pessoaRepository;

    public function __construct(PresencaPessoaService $presencaPessoaService, SalaRepository $salaRepository, PessoaRepository $pessoaRepository)
    {
        $this->presencaPessoaService = $presencaPessoaService;
        $this->salaRepository = $salaRepository;
        $this->pessoaRepository = $pessoaRepository;
    }

    public function show(int $id) {

    }

    public function update(int $id) {

    }

    public function delete(int $id) {

    }

    public function showRelatorioPresenca() {
        $classes = $this->salaRepository->findSalasByCongregacaoId(auth()->user()->congregacao_id);

        return view('/admin/relatorios/presenca-classe', compact('classes'));

    }

    public function getPresencasOfClasse(Request $request) : ?string {
        if (Sala::findOrFail((int) base64_decode($request->classeId))->congregacao_id != auth()->user()->congregacao_id) {
            return response()->json(['error' => "Não autorizado"], 403);
        }
        if ((int) auth()->user()->permissao_id == 4) {
            if ((int) auth()->user()->sala_id != (int) base64_decode($request->classeId)) {
                return response()->json(['error' => "Não autorizado"], 403);
            }
        }
        $presencaPessoaDTO = new PresencaPessoaDTO();
        $presencaPessoaDTO->setSalaId((int) base64_decode($request->classeId));
        $presencaPessoaDTO->setDataInicio($request->initialDate);
        $presencaPessoaDTO->setDataFim($request->finalDate);
        $presencaPessoaDTO->setOrderBy([
            'type' => orderBy::DESC->value,
            'column' => 'presencas'
        ]);


        $presencas = $this->presencaPessoaService->filter($presencaPessoaDTO);

        return $presencas->toJson();

    }

    public function getPresencasOfPessoa() {

    }

    public function marcarPresencaIndividualNivelComum(PresencaIndividualRequest $request) : RedirectResponse {
        try {
            $pessoaSalas = $this->pessoaRepository->getSalasOfPessoa(auth()->user()->pessoa_id);

            if ($pessoaSalas->count() > 1 && !$request->pessoa_sala) {
                return redirect()->back()->with('msg_error', 'O vínculo é obrigatório para marcar presença.');
            }

            $salaId = (int) $request->pessoa_sala ? PessoaSala::findOrFail($request->pessoa_sala)->sala_id : $request->sala;

            $dadosValidacao = new PresencaIndividualDadosValidacaoDTO($request->latitude, $request->longitude, $request->codigo);

            $validacao = $this->presencaPessoaService->validatePresenca($salaId, $dadosValidacao);
            if (!$validacao['response']) {
                return redirect()->back()->with('msg_validacao', $validacao['erros']);
            }

            $presencaIndividual = $request->pessoa_sala ? new PresencaIndividualDTO(auth()->user()->pessoa_id, PessoaSala::findOrFail($request->pessoa_sala)->funcao_id, 1) : new PresencaIndividualDTO(auth()->user()->pessoa_id, $request->funcao, 1);
            $tipoPresenca = TipoPresenca::NIVEL_ALUNO;

            $response = $this->presencaPessoaService->marcarPresencaIndividual($presencaIndividual, $salaId, $tipoPresenca);

            return redirect()->back()->with('msg_success', $response->getData()->response);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('msg_error', '[ERRO INTERNO] Não foi possível marcar a presença. Contate o administrador.');
        }
    }

}
