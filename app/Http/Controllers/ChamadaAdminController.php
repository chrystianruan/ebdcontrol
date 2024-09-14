<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use App\Http\Repositories\PessoaRepository;
use App\Http\Repositories\PresencaPessoaRepository;
use App\Http\Repositories\SalaRepository;
use App\Http\Services\ChamadaService;
use App\Models\Chamada;
use App\Models\Sala;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChamadaAdminController extends Controller
{
    private $pessoaRepository;
    private $presencaPessoaRepository;
    private $salaRepository;
    private $chamadaService;
    private $chamadaDiaCongregacaoRepository;
    public function __construct(PessoaRepository $pessoaRepository, PresencaPessoaRepository $presencaPessoaRepository,
                                SalaRepository $salaRepository, ChamadaService $chamadaService, ChamadaDiaCongregacaoRepository $chamadaDiaCongregacaoRepository){
        $this->pessoaRepository = $pessoaRepository;
        $this->presencaPessoaRepository = $presencaPessoaRepository;
        $this->salaRepository = $salaRepository;
        $this->chamadaService = $chamadaService;
        $this->chamadaDiaCongregacaoRepository = $chamadaDiaCongregacaoRepository;
    }
    public function printFolhaFrequencia($idClasse, $dateRequest) {
        $classeSelected = Sala::select('nome')->findOrFail($idClasse);
        $date = $dateRequest;

        $pessoas = $this->pessoaRepository->findBySalaIdAndSituacao($idClasse);


        return Pdf::loadView('/admin/visualizar/pdf-folha-frequencia', compact(['pessoas', 'date', 'classeSelected']))
            ->stream("frequencia.pdf");
    }

    public function generatePdfToChamadasToAdmin($id) {

        $chamada = Chamada::findOrFail($id);
        if ($chamada->sala->congregacao->id != auth()->user()->congregacao_id) {
            return abort(403);
        }
        $presencas = $this->presencaPessoaRepository->findByDateAndSala(date('Y-m-d', strtotime($chamada->created_at)), $chamada->id_sala);
        $salas = $this->salaRepository->findSalasByCongregacaoId(auth()->user()->congregacao_id);

        return Pdf::loadView('/admin/visualizar/pdf-chamada', compact(['chamada', 'presencas', 'salas']))
            ->stream('frequencia-finalizada.pdf');
    }

    public function indexRealizarChamadas() :? View {
        $dateChamadaDia = null;
        $chamadaDiaBD = $this->chamadaDiaCongregacaoRepository->findChamadaDiaToday(auth()->user()->congregacao_id, date('Y-m-d'));
        if ($chamadaDiaBD) {
            $dateChamadaDia = $chamadaDiaBD->date;
        }
        $chamadas = Chamada::where('congregacao_id', auth()->user()->congregacao_id)
            ->whereDate('created_at', Carbon::today())
            ->get();
        $salas = $this->salaRepository->findSalasByCongregacaoId(auth()->user()->congregacao_id);
        $classesFaltantes = $this->chamadaService->classesNotSendChamada($salas, $chamadas);

        return view('admin.chamadas.realizar-chamada', compact('dateChamadaDia', 'classesFaltantes'));
    }
}
