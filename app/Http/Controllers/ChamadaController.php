<?php

namespace App\Http\Controllers;

use App\Http\Enums\TipoPresenca;
use App\Http\Repositories\ChamadaRepository;
use App\Http\Repositories\PessoaRepository;
use App\Http\Repositories\PresencaPessoaRepository;
use App\Http\Repositories\SalaRepository;
use App\Http\Services\ChamadaService;
use App\Http\Services\PresencaPessoaService;
use App\Models\Chamada;
use App\Models\ChamadaDiaCongregacao;
use App\Models\PresencaPessoa;
use App\Models\Sala;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ChamadaController extends Controller
{
    protected $chamadaService;
    protected $pessoaRepository;
    protected $presencaPessoaRepository;
    protected $presencaPessoaService;
    protected $chamadaRepository;
    protected $salaRepository;
    public function __construct(ChamadaService $chamadaService,
    PessoaRepository $pessoaRepository,
    PresencaPessoaRepository $presencaPessoaRepository,
    PresencaPessoaService $presencaPessoaService,
    ChamadaRepository $chamadaRepository,
    SalaRepository $salaRepository
    )
    {
        $this->chamadaService = $chamadaService;
        $this->pessoaRepository = $pessoaRepository;
        $this->presencaPessoaRepository = $presencaPessoaRepository;
        $this->presencaPessoaService = $presencaPessoaService;
        $this->chamadaRepository = $chamadaRepository;
        $this->salaRepository = $salaRepository;
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

    public function showChamada(int $id) : ?View{
        $chamada = Chamada::findOrFail($id);
        if ($chamada->sala->congregacao->id != auth()->user()->congregacao_id) {
            return abort(403);
        }
        $presencas = $this->presencaPessoaRepository->findByDateAndSala(date('Y-m-d', strtotime($chamada->created_at)), $chamada->id_sala);
        $salas = $this->salaRepository->findSalasByCongregacaoId(auth()->user()->congregacao_id);

        return view('/admin/visualizar/chamada', compact(['presencas', 'salas', 'chamada']));
    }

    public function showChamadaClasse(int $id) {
        $chamada = Chamada::findOrFail($id);
        if ($chamada->sala->id != auth()->user()->sala_id) {
            return abort(403);
        }
        $findSala = Sala::findOrFail(auth()->user()->sala_id);
        $presencas = $this->presencaPessoaRepository->findByDateAndSala(date('Y-m-d', strtotime($chamada->created_at)), $chamada->id_sala);

        return view('/classe/visualizar-chamada', compact(['chamada', 'presencas', 'findSala']));
    }

    public function realizarChamada(Request $request) {
        if ($this->chamadaRepository->getChamadaPadraoToday($request->sala)) {
            return redirect($request->route)->with('msg2', 'A chamada não pode ser realizada, pois já encontra concluída');
        }

        $pessoas = $this->pessoaRepository->findBySalaIdAndSituacao($request->sala);

        $dataToInt = $this->chamadaService->convertToInt($request);
        $validateRequest = $this->chamadaService->validateRequest($dataToInt, $pessoas->count());
        if ($validateRequest) {
            return redirect($request->route)->with('msg2', $validateRequest);
        }
        DB::beginTransaction();
        try {
            $this->presencaPessoaService->marcarPresencasLote($request->pessoas_presencas, $request->sala, TipoPresenca::SISTEMA);

            if ($this->chamadaRepository->getChamadaToday($request->sala)) {
                $chamada = $this->chamadaRepository->getChamadaToday($request->sala);
                $chamada->visitantes = $dataToInt['visitantes'];
                $chamada->biblias = $dataToInt['biblias'];
                $chamada->revistas = $dataToInt['revistas'];
                $chamada->observacoes = $request->observacoes;
                $chamada->chamada_padrao = true;
                $chamada->save();

                DB::commit();
                return redirect($request->route)->with('msg', 'Chamada realizada com sucesso!');
            }

            $novaChamada = new Chamada;
            $novaChamada->id_sala = $request->sala;
            $novaChamada->visitantes = $dataToInt['visitantes'];
            $novaChamada->biblias = $dataToInt['biblias'];
            $novaChamada->revistas = $dataToInt['revistas'];
            $novaChamada->observacoes = $request->observacoes;
            $novaChamada->congregacao_id = auth()->user()->congregacao_id;
            $novaChamada->chamada_padrao = true;
            $novaChamada->save();

            DB::commit();

            return redirect($request->route)->with('msg', 'Chamada realizada com sucesso!');


        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect($request->route)->with('msg2', 'Erro Desconhecido ao preencher chamada. Contate o administrador do sistema!');
        }
    }


    public function generatePdfToChamadasToClasse($id)
    {
        $chamada = Chamada::findOrFail($id);
        if ($chamada->sala->id != auth()->user()->sala_id) {
            return abort(403);
        }
        $findSala = Sala::findOrFail(auth()->user()->sala_id);
        $presencas = $this->presencaPessoaRepository->findByDateAndSala(date('Y-m-d', strtotime($chamada->created_at)), $chamada->id_sala);

        return PDF::loadView('/classe/pdf-chamada', compact(['chamada', 'findSala', 'presencas']))
            ->stream('frequencia.pdf');
    }



}
