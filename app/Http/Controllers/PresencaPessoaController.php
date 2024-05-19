2<?php

namespace App\Http\Controllers;

use App\Http\Enums\TipoPresenca;
use App\Http\Services\PresencaPessoaService;
use Illuminate\Http\Request;

class PresencaPessoaController extends Controller
{
    protected $presencaPessoaService;

    public function __construct(PresencaPessoaService $presencaPessoaService)
    {
        $this->presencaPessoaService = $presencaPessoaService;
    }

    public function marcarPresencasLote(Request $request){
        $presencas = $request->presencas;
        $response = $this->presencaPessoaService->marcarPresencasLote($presencas, auth()->user()->id_nivel, TipoPresenca::SISTEMA);
        if ($response->getStatusCode() != 201) {
            return view('/')->with('msg2', $response->getContent());
        }
        return view('/')->with('msg', 'Chamada realizada com sucesso');
    }


    public function show(int $id) {

    }

    public function update(int $id) {

    }

    public function delete(int $id) {

    }

    public function listPresencasPessoa(int $pessoaId) {

    }

    public function filter(Request $request) {

    }
}
