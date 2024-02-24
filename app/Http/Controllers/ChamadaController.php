<?php

namespace App\Http\Controllers;

use App\Http\Services\ChamadaService;
use App\Models\ChamadaDiaCongregacao;
use http\Env\Response;
use Illuminate\Http\Request;

class ChamadaController extends Controller
{
    protected $chamadaService;
    public function __construct(ChamadaService $chamadaService)
    {
        $this->chamadaService = $chamadaService;
    }

    public function edit(int $id) {

    }
    public function update(int $id) {

    }

    public function liberarChamada(Request $request) {
        $response = $this->chamadaService->liberarChamadaParaOutroDia(intval($request->congregacao), $request->date);

        return $response;
    }

    public function chamadasLiberadaMes() {
        return $this->chamadaService->chamadasLiberadasMesAtual(auth()->user()->congregacao_id, intval(date('n')));
    }

    public function apagarChamadaDia(int $id) {
        ChamadaDiaCongregacao::findOrFail($id)->delete();
        return response()->json([
            'response' => 'Dia de chamada apagado com sucesso'
        ], 201);
    }
}
