<?php

namespace App\Http\Controllers;

use App\Http\DTOs\PresencaPessoaDTO;
use App\Http\Enums\orderBy;
use App\Http\Enums\TipoPresenca;
use App\Http\Repositories\SalaRepository;
use App\Http\Services\PresencaPessoaService;
use Cassandra\Column;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PresencaPessoaController extends Controller
{
    private $presencaPessoaService;
    private $salaRepository;

    public function __construct(PresencaPessoaService $presencaPessoaService, SalaRepository $salaRepository)
    {
        $this->presencaPessoaService = $presencaPessoaService;
        $this->salaRepository = $salaRepository;
    }

    public function show(int $id) {

    }

    public function update(int $id) {

    }

    public function delete(int $id) {

    }

//    public function getPresencasOfPessoa(int $pessoaId) : View {
//
//    }

    public function showRelatorioPresenca() {
        $classes = $this->salaRepository->findSalasByCongregacaoId(auth()->user()->congregacao_id);

        return view('/admin/relatorios/presenca-classe', compact('classes'));

    }

    public function getPresencasOfClasse(Request $request) : ?Collection {
        $presencaPessoaDTO = new PresencaPessoaDTO();
        $presencaPessoaDTO->setSalaId((int) base64_decode($request->classeId));
        $presencaPessoaDTO->setDataInicio($request->initialDate);
        $presencaPessoaDTO->setDataFim($request->finalDate);
        $presencaPessoaDTO->setOrderBy([
            'type' => orderBy::DESC->value,
            'column' => 'presencas'
        ]);


        $presencas = $this->presencaPessoaService->filter($presencaPessoaDTO);

        return $presencas;

    }
}
