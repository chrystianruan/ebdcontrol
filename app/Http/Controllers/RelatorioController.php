<?php
namespace App\Http\Controllers;
use App\Http\Repositories\ChamadaRepository;
use App\Http\Repositories\SalaRepository;
use App\Http\Services\ChamadaService;
use App\Models\Chamada;
use App\Models\Funcao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RelatorioController extends Controller {

    private $chamadaRepository;
    private $salaRepository;
    private $chamadaService;
    public function __construct(ChamadaRepository $chamadaRepository,
                                SalaRepository $salaRepository,
                                ChamadaService  $chamadaService
    ) {
        $this->chamadaRepository = $chamadaRepository;
        $this->salaRepository = $salaRepository;
        $this->chamadaService = $chamadaService;
    }
    public function generateRelatorioPerDate(Request $request)
    {

        $presencas = $this->returnData($request->initialDate, $request->finalDate, base64_decode($request->classeId));

        return $presencas;
    }

    public function returnData($initial_date, $final_date, $classe_id)
    {
        $chamadas = $this->getChamadas($initial_date, $final_date, auth()->user()->congregacao_id, $classe_id);
        $duplicatesNamesAndPresencas = $this->getListWithNameAndPresencasDuplicates($chamadas);

        return $duplicatesNamesAndPresencas;
    }

    public function getChamadas($initial_date, $final_date, $congregacao_id, $classe_id) {
        $finalDate = $final_date." 23:59:59";
        $chamadas = Chamada::where('id_sala', '=', $classe_id)
            ->whereBetween('created_at',  [$initial_date, $finalDate])
            ->where('congregacao_id', '=', $congregacao_id)
            ->get();

        return $chamadas;
    }
    public function getListWithNameAndPresencasDuplicates($chamadas) {
        $namesAndPresencas = [];
        foreach ($chamadas as $cha) {
            $nomes = json_decode($cha->nomes, true);
            foreach ($nomes as $cn) {

                $namesAndPresencas[] = [
                    'id' => $cn['id'],
                    'nome' => $cn['nome'],
                    'data_nasc' => $cn['data_nasc'],
                    'id_funcao' => $cn['id_funcao'],
                    'presenca' => (int)$cn['presenca'],
                ];
            }
        }
        return json_encode($namesAndPresencas);
    }
    public function formatData(Request $request) {
        $dataArray = json_decode($request->data, true);
        $dataFormated = [];
        $funcoes = Funcao::all();
        $funcao = "Secretário/Classe";
        foreach ($dataArray as $data) {
            foreach ($funcoes as $func) {
                if ($func->id == $data['id_funcao']) {
                    $funcao = $func->nome;
                }
            }
            $dataFormated[] = [
                'id' => $data['id'],
                'nome' => $data['nome'],
                'id_funcao' => $funcao,
                'data_nasc' => date('d/m/Y', strtotime($data['data_nasc'])),
                'presencas' => array_sum($data['presencas'])
            ];
        }
        array_multisort(array_column($dataFormated, 'presencas'), SORT_DESC, $dataFormated);
        return $dataFormated;
    }

    public function gerarRelatorio(Request $request) {
        $meses_abv = [1 => 'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        try {
            $relatorios = $this->chamadaRepository->getSumOfChamadasFindByMesOrYear($request->mes, $request->ano);
            $chamadas = Chamada::where('congregacao_id', auth()->user()->congregacao_id)
                ->whereDate('created_at', Carbon::today())
                ->get();
            $salas = $this->salaRepository->findSalasByCongregacaoId(auth()->user()->congregacao_id);
            $classesFaltantes = $this->chamadaService->classesNotSendChamada($salas, $chamadas);

            return view('/admin/relatorios/todos', compact(['relatorios', 'meses_abv', 'chamadas', 'salas', 'classesFaltantes']));
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


}
