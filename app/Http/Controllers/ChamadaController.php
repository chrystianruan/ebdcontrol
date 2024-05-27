<?php

namespace App\Http\Controllers;

use App\Http\Enums\TipoPresenca;
use App\Http\Repositories\ChamadaRepository;
use App\Http\Repositories\PessoaRepository;
use App\Http\Repositories\PresencaPessoaRepository;
use App\Http\Services\ChamadaService;
use App\Http\Services\PresencaPessoaService;
use App\Models\Chamada;
use App\Models\ChamadaDiaCongregacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChamadaController extends Controller
{
    protected $chamadaService;
    protected $pessoaRepository;
    protected $presencaPessoaRepository;
    protected $presencaPessoaService;
    protected $chamadaRepository;
    public function __construct(ChamadaService $chamadaService,
    PessoaRepository $pessoaRepository,
    PresencaPessoaRepository $presencaPessoaRepository,
    PresencaPessoaService $presencaPessoaService,
    ChamadaRepository $chamadaRepository
    )
    {
        $this->chamadaService = $chamadaService;
        $this->pessoaRepository = $pessoaRepository;
        $this->presencaPessoaRepository = $presencaPessoaRepository;
        $this->presencaPessoaService = $presencaPessoaService;
        $this->chamadaRepository = $chamadaRepository;
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

    public function realizarChamada(Request $request) {
        $sala = auth()->user()->id_nivel;


        if ($this->chamadaRepository->getChamadaToday($sala)) {
            return redirect('/classe/chamada-dia')->with('msg2', 'A chamada não pode ser realizada.');
        }

        $pessoas = $this->pessoaRepository->findBySalaIdAndSituacao($sala);

        $dataToInt = $this->chamadaService->convertToInt($request);
        $validateRequest = $this->chamadaService->validateRequest($dataToInt, $pessoas->count());
        if ($validateRequest) {
            return redirect()->back()->with('msg2', $validateRequest);
        }

        try {
            $this->presencaPessoaService->marcarPresencasLote($request->pessoas_presencas, auth()->user()->id_nivel, TipoPresenca::SISTEMA);

            if ($this->chamadaRepository->getChamadaToday($sala)) {
                $chamada = Chamada::where('id_sala', '=', $sala)
                    ->whereDate('created_at', Carbon::today())
                    ->first();
                $chamada->matriculados = $pessoas->count();
                $chamada->visitantes = $dataToInt['visitantes'];
                $chamada->biblias = $dataToInt['biblias'];
                $chamada->revistas = $dataToInt['revistas'];
                $chamada->observacoes = $request->observacoes;
                $chamada->save();

                return redirect('/classe/todas-chamadas')->with('msg', 'Chamada realizada com sucesso!');
            }

            $novaChamada = new Chamada;
            $novaChamada->id_sala = $sala;
            $novaChamada->matriculados = $pessoas->count();
            $novaChamada->visitantes = $dataToInt['visitantes'];
            $novaChamada->biblias = $dataToInt['biblias'];
            $novaChamada->revistas = $dataToInt['revistas'];
            $novaChamada->observacoes = $request->observacoes;
            $novaChamada->congregacao_id = auth()->user()->congregacao_id;
            $novaChamada->save();

            return redirect('/classe/todas-chamadas')->with('msg', 'Chamada realizada com sucesso!');


        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('/classe/todas-chamadas')->with('msg2', 'Erro ao preencher chamada');
        }
    }


}
