<?php
namespace App\Http\Controllers;
use App\Http\Enums\ViewEnum;
use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use App\Http\Repositories\ChamadaRepository;
use App\Http\Repositories\SalaRepository;
use App\Http\Services\ChamadaService;
use App\Http\Services\ClasseDestaqueService;
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
    private $classeDestaqueService;
    public function __construct(ChamadaRepository $chamadaRepository,
                                SalaRepository $salaRepository,
                                ChamadaService  $chamadaService,
                                ChamadaDiaCongregacaoRepository $chamadaDiaCongregacaoRepository,
                                ClasseDestaqueService $classeDestaqueService
    )
    {
        $this->chamadaRepository = $chamadaRepository;
        $this->salaRepository = $salaRepository;
        $this->chamadaService = $chamadaService;
        $this->chamadaDiaCongregacaoRepository = $chamadaDiaCongregacaoRepository;
        $this->classeDestaqueService = $classeDestaqueService;
    }

    public function gerarRelatorio(Request $request) {
        $meses_abv = [1 => 'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        try {
            $dateChamadaDia = null;
            $chamadaDiaBD = $this->chamadaDiaCongregacaoRepository->findChamadaDiaToday(auth()->user()->congregacao_id, date('Y-m-d'));
            if ($chamadaDiaBD) {
                $dateChamadaDia = $chamadaDiaBD->date;
            }

            if ($request->mes && $request->ano) {
                $mes = $request->mes;
                $ano = $request->ano;
            } else {
                $mes = (int) date('n');
                $ano = (int) date('Y');
            }

            $relatorios = $this->chamadaRepository->getSumOfChamadasFindByMesOrYear($mes, $ano);

            // Calcular destaques por data
            $destaquesPorData = [];
            foreach ($relatorios as $r) {
                $dateKey = date('Y-m-d', strtotime($r->created_at));
                $chamadasDoDia = Chamada::with('sala')
                    ->where('congregacao_id', auth()->user()->congregacao_id)
                    ->whereDate('created_at', $dateKey)
                    ->get();
                $destaquesPorData[$dateKey] = $this->classeDestaqueService->calcularDestaques($chamadasDoDia);
            }

            $chamadas = Chamada::where('congregacao_id', auth()->user()->congregacao_id)
                ->whereDate('created_at', Carbon::today())
                ->where('chamada_padrao', true)
                ->get();
            $salas = $this->salaRepository->findSalasByCongregacaoId(auth()->user()->congregacao_id);
            $classesFaltantes = $this->chamadaService->classesNotSendChamada($salas, $chamadas);
            $blade = ViewEnum::RELATORIOS;

            return view('/admin/relatorios/todos', compact([
                'relatorios',
                'destaquesPorData',
                'meses_abv',
                'chamadas',
                'salas',
                'classesFaltantes',
                'dateChamadaDia',
                'blade',
                'mes',
                'ano'
            ]));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('/admin/relatorios')->with('msg2', 'Erro ao gerar relatório');
        }
    }


    public function show(string $date) : View {
        $chamadas = $this->chamadaRepository->findByCreatedAtAndCongregacao(auth()->user()->congregacao_id, $date);
        $relatorio = $this->chamadaRepository->getSumOfChamadasFindByCreatedAt($date);

        return view('admin.visualizar.relatorio', compact(['chamadas', 'relatorio']));
    }

    /**
     * Retorna os dados completos do relatório para o modal (AJAX).
     */
    public function getModalRelatorio(string $date) {
        try {
            $dados = $this->classeDestaqueService->getDadosModalRelatorio($date, auth()->user()->congregacao_id);

            if (!empty($dados['vazio'])) {
                return response()->json(['vazio' => true, 'mensagem' => 'Nenhum dado encontrado para esta data.'], 200);
            }

            return response()->json($dados, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao carregar modal de relatório: ' . $e->getMessage());
            return response()->json(['erro' => 'Erro ao carregar os dados do relatório.'], 500);
        }
    }

    public function generatePdfRelatorioChamada(string $date) : Response {
        $chamadas = $this->chamadaRepository->findByCreatedAtAndCongregacao(auth()->user()->congregacao_id, $date);
        $relatorio = $this->chamadaRepository->getSumOfChamadasFindByCreatedAt($date);

        $chamadasComSala = Chamada::with('sala')
            ->where('congregacao_id', auth()->user()->congregacao_id)
            ->whereDate('created_at', $date)
            ->get();

        $destaques   = $this->classeDestaqueService->calcularDestaques($chamadasComSala);
        $piores      = $this->classeDestaqueService->calcularPioresIndices($chamadasComSala);
        $comparativo = $this->classeDestaqueService->calcularComparativo($date, auth()->user()->congregacao_id);

        $salas = $this->salaRepository->findSalasByCongregacaoId(auth()->user()->congregacao_id);
        $classesFaltantes = $this->chamadaService->classesNotSendChamada($salas, $chamadasComSala);

        return Pdf::loadView('/admin/visualizar/pdf-relatorio', compact([
                'relatorio', 'chamadas', 'destaques', 'piores', 'comparativo', 'classesFaltantes'
            ]))
            ->setPaper('a4', 'landscape')
            ->stream('relatorio.pdf');
    }


}
