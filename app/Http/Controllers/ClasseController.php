<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use App\Http\Repositories\PessoaRepository;
use App\Http\Services\ChamadaService;
use App\Http\Services\PessoaService;
use App\Mail\ChamadaRealizadaMail;
use App\Mail\PessoaCadastradaMail;
use App\Models\ChamadaDiaCongregacao;
use App\Models\Congregacao;
use Illuminate\Http\Request;
use App\Models\Formation;
use App\Models\Funcao;
use App\Models\Pessoa;
use App\Models\Publico;
use App\Models\User;
use App\Models\Sala;
use App\Models\Uf;
use App\Models\Aviso;
use App\Models\Chamada;
use Carbon\Carbon;
use App\Http\Services\RelatorioService;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class ClasseController extends Controller
{

    protected $generalController;
    protected $relatorioService;
    protected $chamadaService;
    protected $pessoaService;
    protected $chamadaDiaCongregacaoRepository;
    protected $pessoaRepository;

    public function __construct(GeneralController $generalController,
                                RelatorioService $relatorioService,
    ChamadaDiaCongregacaoRepository $chamadaDiaCongregacaoRepository,
    ChamadaService $chamadaService,
    PessoaService $pessoaService,
    PessoaRepository $pessoaRepository,
    )
    {
        $this->generalController = $generalController;
        $this->relatorioService = $relatorioService;
        $this->chamadaDiaCongregacaoRepository = $chamadaDiaCongregacaoRepository;
        $this->chamadaService = $chamadaService;
        $this->pessoaService = $pessoaService;
        $this->pessoaRepository = $pessoaRepository;
    }

    public function indexClasse()
    {
        $nivel = auth()->user()->id_nivel;
        $idadesPessoas = DB::table('pessoa_salas')
            ->select(DB::raw('count(pessoas.id) as qtd, timestampdiff(YEAR, pessoas.data_nasc, current_timestamp()) as idades'))
            ->join('pessoas', 'pessoas.id', '=', 'pessoa_salas.pessoa_id')
            ->where('pessoa_salas.sala_id', $nivel)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->groupBy(DB::raw('timestampdiff(YEAR, pessoas.data_nasc, current_timestamp())'))
            ->get();
        $formacoes = Pessoa::select(DB::raw('pessoas.id_formation, count(pessoas.id) as qtdPessoas, formations.nome'))
            ->join('pessoa_salas', 'pessoa_salas.pessoa_id', '=', 'pessoas.id')
            ->join('formations', 'pessoas.id_formation', '=', 'formations.id')
            ->where('pessoa_salas.sala_id', $nivel)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->groupBy('id_formation')
            ->get();
        $funcoes = $this->pessoaService->getArrayQuantidadePessoasPerFuncao(auth()->user()->id_nivel);
        $interesseProf = $this->pessoaRepository->findByInteresseAndCongregacaoAndSalaCount(auth()->user()->id_nivel);
        $chamadaDia = Chamada::where('id_sala', '=', $nivel)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->whereDate('created_at', Carbon::today())
            ->get();
        $chamadasMes = Chamada::where('id_sala', '=', $nivel)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->whereMonth('created_at', Carbon::now())
            ->get();
        $chamadasAno = Chamada::where('id_sala', '=', $nivel)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->whereYear('created_at', Carbon::now())
            ->get();
        $niverMes = $this->pessoaRepository->getAniversariantesMes(auth()->user()->id_nivel);
        $alunosInativos = $this->pessoaRepository->getInativos(auth()->user()->id_nivel);

        return view('/classe/dashboard', ['niverMes' => $niverMes, 'alunosInativos' => $alunosInativos,
            'chamadaDia' => $chamadaDia, 'interesseProf' => $interesseProf, 'idadesPessoas' => $idadesPessoas, 'formacoes' => $formacoes,
            'chamadasMes' => $chamadasMes, 'chamadasAno' => $chamadasAno, 'funcoes' => $funcoes]);
    }

    public function indexCadastroClasse()
    {
        $check = request('scales');
        $salas = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('nome')->get();
        $ufs = Uf::orderBy("nome")->get();
        $publicos = Publico::all();
        $formations = Formation::all();
        return view('/classe/cadastro-pessoa', ['salas' => $salas, 'ufs' => $ufs, 'publicos' => $publicos,
            'formations' => $formations, 'check' => $check]);
    }


    public function searchPessoaClasse(Request $request)
    {
        $nivelUser = auth()->user()->id_nivel;
        $nome = request('nome');
        $sexo = request('sexo');
        $niver = request('niver');
        $id_funcao = request('id_funcao');
        $situacao = request('situacao');
        $interesse = request('interesse');
        $meses_abv = [1 => 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        //nome
        $pessoas = Pessoa::select('pessoas.*')
                                ->join('pessoa_salas', 'pessoas.id', '=', 'pessoa_salas.pessoa_id')
                                ->where('pessoa_salas.sala_id', $nivelUser)
                                ->where('congregacao_id', '=', auth()->user()->congregacao_id);

        if ($request->nome) {
            $pessoas = $pessoas->where([['nome', 'like', '%' . $request->nome . '%']]);
        }

        if ($request->sexo) {
            $pessoas = $pessoas->where('sexo', $request->sexo);
        }

        if ($request->id_funcao) {
            $pessoas = $pessoas->where('pessoa_salas.funcao_id', $request->id_funcao);
        }

        if ($request->situacao) {
            $pessoas = $pessoas->where('situacao', $request->situacao);
        }

        if ($request->interesse) {
            $pessoas = $pessoas->where('interesse', $request->interesse);
        }

        if ($request->niver) {
            $pessoas = $pessoas->whereMonth('data_nasc', $request->niver);
        }

        $pessoas = $pessoas
            ->groupBy('pessoa_salas.pessoa_id')
            ->orderBy('nome')
            ->get();


        return view('/classe/pessoas', ['pessoas' => $pessoas, 'nome' => $nome, 'sexo' => $sexo,
            'id_funcao' => $id_funcao, 'interesse' => $interesse, 'situacao' => $situacao,
            'meses_abv' => $meses_abv, 'niver' => $niver]);
    }

    public function showPessoaClasse($id)
    {
        $nivelUser = auth()->user()->id_nivel;
        $pessoa = Pessoa::findOrFail($id);

        if (in_array($nivelUser, array_column($pessoa->salas->toArray(), 'id'))) {
            $findSala = Sala::findOrFail($nivelUser);
            $ufs = Uf::orderBy("nome")->get();
            $publicos = Publico::all();
            $formations = Formation::all();
            return view('/classe/visualizar-pessoa', ['pessoa' => $pessoa, 'findSala' => $findSala, 'ufs' => $ufs,
                'publicos' => $publicos, 'formations' => $formations]);
        } else {
            return redirect('/classe')->with('msg2', 'Seu usuário não permissão para ver esta pessoa');
        }
    }

    public function indexChamadaClasse()
    {
        $sala = auth()->user()->id_nivel;
        $chamadas = Chamada::where('id_sala', '=', $sala)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->whereDate('created_at', Carbon::today())
            ->get();
        $pessoas = Pessoa::select('pessoas.id', 'pessoas.nome', 'data_nasc', DB::raw("0 as presenca"), 'funcaos.id as id_funcao', 'funcaos.nome as nome_funcao')
            ->join('pessoa_salas', 'pessoas.id', '=', 'pessoa_salas.pessoa_id')
            ->join('funcaos', 'funcaos.id', '=', 'pessoa_salas.funcao_id')
            ->where('pessoas.situacao', '=', 1)
            ->where('pessoa_salas.sala_id', $sala)
            ->where('pessoas.congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('pessoas.nome')
            ->groupBy('pessoa_salas.pessoa_id')
            ->get();
        $salas = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->get();

        $chamadaDiaBD = $this->chamadaDiaCongregacaoRepository->findChamadaDiaToday(auth()->user()->congregacao_id, date('Y-m-d'));
        if ($chamadaDiaBD) {
            $dateChamadaDia = $chamadaDiaBD->date;
        } else {
            $dateChamadaDia = null;
        }

        return view('/classe/chamada-dia', ['chamadas' => $chamadas,
            'salas' => $salas, 'pessoas' => $pessoas,
            'dateChamadaDia' => $dateChamadaDia]);
    }

    public function storeChamadaClasse(Request $request) {
        $sala = auth()->user()->id_nivel;
        $chamadas = Chamada::where('id_sala', '=', $sala)
            ->whereDate('created_at', Carbon::today())
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->get();

        if ($chamadas->count() == 1) {
            return redirect('/classe/chamada-dia')->with('msg2', 'A chamada não pode ser realizada.');
        }
//        $pessoas = DB::table('pessoas')
//            ->select('nome', 'data_nasc', 'id_funcao')
//            ->whereJsonContains('id_sala', '' . $sala)
//            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
//            ->where('situacao', '=', 1)
//            ->orderBy('nome')->get();

        $pessoas = $this->pessoaRepository->findBySalaIdAndSituacao($sala);

        $dataToInt = $this->chamadaService->convertToInt($request);
        $validateRequest = $this->chamadaService->validateRequest($dataToInt, $pessoas->count());
        if ($validateRequest) {
            return redirect()->back()->with('msg2', $validateRequest);
        }

        $chamada = new Chamada;
        $chamada->id_sala = $sala;
        $chamada->nomes = $request->pessoas_presencas;
        $chamada->matriculados = $pessoas->count();
        $chamada->presentes = $dataToInt['presentes'];
        $chamada->visitantes = $dataToInt['visitantes'];
        $chamada->assist_total = $dataToInt['presentes'] + $dataToInt['visitantes'];
        $chamada->biblias = $dataToInt['biblias'];
        $chamada->revistas = $dataToInt['revistas'];
        $chamada->observacoes = $request->observacoes;
        $chamada->congregacao_id = auth()->user()->congregacao_id;
        $chamada->save();

        $chamadaRealizada = Chamada::where('congregacao_id', auth()->user()->congregacao_id)->latest()->first();

        $result = $this->relatorioService->saveRelatorio($chamadaRealizada);
        $salaNome = Sala::findOrFail((int) $chamada->id_sala)->nome;
        $congregacaoNome = Congregacao::findOrFail((int) $chamada->congregacao_id)->nome;

        $email = new ChamadaRealizadaMail($salaNome, $congregacaoNome);
        Mail::to('chrystianr37@gmail.com')
            ->send($email);

        return redirect('/classe/todas-chamadas')->with('msg', $result);

    }

    public function searchChamadaClasse(Request $request)
    {
        $mes = request('mes');
        $ano = request('ano');
        $sala = auth()->user()->id_nivel;
        $findSala = Sala::findOrFail($sala);
        $meses_abv = [1 => 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        //mes
        if (isset($request->mes) && empty($request->ano)) {
            $chamadas = Chamada::where('id_sala', '=', $sala)
                ->whereMonth('created_at', '=', $request->mes)
                ->where('congregacao_id', '=', auth()->user()->congregacao_id)
                ->orderBy('created_at', 'DESC')
                ->get();
        } //ano
        elseif (empty($request->mes) && isset($request->ano)) {
            $chamadas = Chamada::where('id_sala', '=', $sala)
                ->whereYear('created_at', '=', $request->ano)
                ->where('congregacao_id', '=', auth()->user()->congregacao_id)
                ->orderBy('created_at', 'DESC')
                ->get();
        } //mes e ano
        elseif (isset($request->mes) && isset($request->ano)) {
            $chamadas = Chamada::where('id_sala', '=', $sala)
                ->whereMonth('created_at', '=', $request->mes)
                ->whereYear('created_at', '=', $request->ano)
                ->where('congregacao_id', '=', auth()->user()->congregacao_id)
                ->orderBy('created_at', 'DESC')
                ->get();

        } else {
            $chamadas = Chamada::where('id_sala', '=', $sala)
                ->whereDate('created_at', Carbon::today())
                ->where('congregacao_id', '=', auth()->user()->congregacao_id)
                ->orderBy('created_at', 'DESC')
                ->get();

        }
        return view('/classe/todas-chamadas', ['mes' => $mes, 'ano' => $ano, 'chamadas' => $chamadas, 'findSala' => $findSala, 'meses_abv' => $meses_abv]);

    }


    public function showChamadaClasse($id)
    {
        $nivel = auth()->user()->id_nivel;
        $findSala = Sala::findOrFail($nivel);
        $chamada = Chamada::findOrFail($id);
        if ($nivel != $chamada->id_sala) {
            return redirect('/classe')->with('msg2', 'Seu usuário não permissão para ver esta chamada');
        }
        return view('/classe/visualizar-chamada', ['chamada' => $chamada, 'findSala' => $findSala]);

    }

    public function searchAniversariantes(Request $request)
    {
        $nivel = auth()->user()->id_nivel;
        $mes = request('mes');
        $salas = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->get();
        $meses_abv = [1 => 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        //mes
        if (isset($request->mes)) {
            $pessoas = Pessoa::select('pessoas.*', 'funcaos.nome as nome_funcao')
                ->join('funcaos', 'funcaos.id', '=', 'pessoas.id_funcao')
                ->whereJsonContains('id_sala', '' . $nivel)
                ->whereMonth('data_nasc', '=', $request->mes)
                ->where('congregacao_id', '=', auth()->user()->congregacao_id)
                ->get();

        } else {
            $pessoas = Pessoa::select('pessoas.*', 'funcaos.nome as nome_funcao')
                ->join('funcaos', 'funcaos.id', '=', 'pessoas.id_funcao')
                ->whereJsonContains('id_sala', '' . $nivel)
                ->whereMonth('data_nasc', '=', Carbon::now())
                ->where('congregacao_id', '=', auth()->user()->congregacao_id)
                ->get();
        }

        return view('/classe/aniversariantes', ['pessoas' => $pessoas, 'salas' => $salas,
            'meses_abv' => $meses_abv, 'mes' => $mes]);
    }

    public function generatePdfToChamadas($id)
    {

        $chamada = Chamada::select('chamadas.*', 'salas.nome')->join('salas', 'chamadas.id_sala', '=', 'salas.id')->findOrFail($id);

        return PDF::loadView('/classe/pdf-chamada', compact(['chamada']))
            ->stream('frequencia.pdf');
    }

    public function generateRelatorioPerDate(Request $request)
    {

        $presencas = $this->returnData($request->initialDate, $request->finalDate, $request->congregacaoId, $request->classeId);

        return $presencas;
    }

    public function returnData($initial_date, $final_date, $congregacao_id, $classe_id)
    {
        $chamadas = $this->generalController->getChamadas($initial_date, $final_date, $congregacao_id, $classe_id);
        $duplicatesNamesAndPresencas = $this->generalController->getListWithNameAndPresencasDuplicates($chamadas);

        return $duplicatesNamesAndPresencas;
    }
}

