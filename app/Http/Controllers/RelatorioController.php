<?php
namespace App\Http\Controllers;
use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use App\Http\Repositories\ChamadaRepository;
use App\Http\Repositories\SalaRepository;
use App\Http\Services\ChamadaService;
use App\Models\Chamada;
use App\Models\Funcao;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RelatorioController extends Controller {

    private $chamadaRepository;
    private $salaRepository;
    private $chamadaService;
    private $chamadaDiaCongregacaoRepository;
    public function __construct(ChamadaRepository $chamadaRepository,
                                SalaRepository $salaRepository,
                                ChamadaService  $chamadaService,
                                ChamadaDiaCongregacaoRepository $chamadaDiaCongregacaoRepository
    )
    {
        $this->chamadaRepository = $chamadaRepository;
        $this->salaRepository = $salaRepository;
        $this->chamadaService = $chamadaService;
        $this->chamadaDiaCongregacaoRepository = $chamadaDiaCongregacaoRepository;
    }

    public function gerarRelatorio(Request $request) {
        $meses_abv = [1 => 'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        try {
            $dateChamadaDia = null;
            $chamadaDiaBD = $this->chamadaDiaCongregacaoRepository->findChamadaDiaToday(auth()->user()->congregacao_id, date('Y-m-d'));
            if ($chamadaDiaBD) {
                $dateChamadaDia = $chamadaDiaBD->date;
            }
            $relatorios = $this->chamadaRepository->getSumOfChamadasFindByMesOrYear($request->mes, $request->ano);
            $chamadas = Chamada::where('congregacao_id', auth()->user()->congregacao_id)
                ->whereDate('created_at', Carbon::today())
                ->get();
            $salas = $this->salaRepository->findSalasByCongregacaoId(auth()->user()->congregacao_id);
            $classesFaltantes = $this->chamadaService->classesNotSendChamada($salas, $chamadas);

            return view('/admin/relatorios/todos', compact(['relatorios', 'meses_abv', 'chamadas', 'salas', 'classesFaltantes', 'dateChamadaDia']));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('/admin/relatorios/todos')->with('msg2', 'Erro ao gerar relatório');
        }
    }


    public function show(string $date) : View {
        $chamadas = $this->chamadaRepository->findByCreatedAtAndCongregacao(auth()->user()->congregacao_id, $date);
        $relatorio = $this->chamadaRepository->getSumOfChamadasFindByCreatedAt($date);

        return view('admin.visualizar.relatorio', compact(['chamadas', 'relatorio']));
    }

    public function generatePdfRelatorioChamada(string $date) : Response {
        $chamadas = $this->chamadaRepository->findByCreatedAtAndCongregacao(auth()->user()->congregacao_id, $date);
        $relatorio = $this->chamadaRepository->getSumOfChamadasFindByCreatedAt($date);
        return Pdf::loadView('/admin/visualizar/pdf-relatorio', compact(['relatorio', 'chamadas']))
            ->setPaper('a4', 'landscape')
            ->stream('relatorio.pdf');
    }


}
