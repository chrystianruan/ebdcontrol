<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use App\Http\Services\ChamadaRelatorioService;
use App\Http\Services\ChamadaService;
use App\Models\ChamadaDiaCongregacao;
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
use DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ClasseController extends Controller
{

    protected $generalController;
    protected $chamadaRelatorioService;
    protected $chamadaService;

    protected $chamadaDiaCongregacaoRepository;

    public function __construct(GeneralController $generalController,
                                ChamadaRelatorioService $chamadaRelatorioService,
    ChamadaDiaCongregacaoRepository $chamadaDiaCongregacaoRepository,
    ChamadaService $chamadaService
    )
    {
        $this->generalController = $generalController;
        $this->chamadaRelatorioService = $chamadaRelatorioService;
        $this->chamadaDiaCongregacaoRepository = $chamadaDiaCongregacaoRepository;
        $this->chamadaService = $chamadaService;
    }

    public function indexClasse()
    {
        $dataMes = date('n');
        $dataAno = date('Y');
        $nivel = auth()->user()->id_nivel;
        $idadesPessoas = DB::table('pessoas')
            ->select(DB::raw('count(id) as qtd, timestampdiff(YEAR, pessoas.data_nasc, current_timestamp()) as idades'))
            ->whereJsonContains('id_sala', '' . $nivel)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->groupBy(DB::raw('timestampdiff(YEAR, pessoas.data_nasc, current_timestamp())'))
            ->get();
        $formacoes = Pessoa::select(DB::raw('pessoas.id_formation, count(pessoas.id) as qtdPessoas, formations.nome'))
            ->whereJsonContains('id_sala', '' . $nivel)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->join('formations', 'pessoas.id_formation', '=', 'formations.id')
            ->groupBy('id_formation')
            ->get();
        $funcoes = Pessoa::select(DB::raw('id_funcao, count(pessoas.id) as qtd, funcaos.nome'))
            ->whereJsonContains('id_sala', '' . $nivel)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->join('funcaos', 'pessoas.id_funcao', '=', 'funcaos.id')
            ->groupBy('id_funcao')
            ->get();
        $interesseProf = Pessoa::whereJsonContains('id_sala', '' . $nivel)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->where('interesse', '<>', 2)
            ->where('id_funcao', '<>', 2)
            ->count();
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
        $niverMes = Pessoa::whereJsonContains('id_sala', '' . $nivel)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->whereMonth('data_nasc', '=', $dataMes)
            ->count();
        $alunosInativos = Pessoa::whereJsonContains('id_sala', '' . $nivel)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->where('situacao', '=', 2)
            ->count();

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

    public function storePessoaClasse(Request $request)
    {
        $nivelUser = auth()->user()->id_nivel;
        $publicos = Publico::all();
        $ufs = Uf::all();
        $formations = Formation::all();
        $this->validate($request, [
            'nome' => ['required'],
            'sexo' => ['required', 'integer', 'min: 1', 'max: 2'],
            'filhos' => ['required', 'integer', 'min: 1', 'max: 2'],
            'data_nasc' => ['required'],
            'id_uf' => ['required', 'integer', 'min: 1', 'max:' . $ufs->count()],
            'telefone' => ['nullable', 'integer', 'min:11111111111', 'max:99999999999', 'unique:pessoas,telefone'],
            'telefone_responsavel' => ['nullable', 'integer', 'min:11111111111', 'max:99999999999'],
            'id_formation' => ['required', 'integer', 'min: 1', 'max:' . $formations->count()],
            'id_sala' => ['max: 1'],
            'id_sala.*' => ['integer', 'min:' . $nivelUser, 'max:' . $nivelUser],
            'interesse' => ['required', 'integer', 'min: 1', 'max: 3'],
            'frequencia_ebd' => ['integer', 'min: 1', 'max: 3'],
            'curso_teo' => ['integer', 'min: 1', 'max: 2'],
            'id_funcao' => ['integer', 'min:1', 'max:1'],
            'situacao' => ['integer', 'min:1', 'max:1'],
            'prof_ebd' => ['integer', 'min: 1', 'max: 2'],
            'prof_comum' => ['integer', 'min: 1', 'max: 2'],
            'id_public' => ['integer', 'min: 1', 'max:' . $publicos->count()],
        ], [
            'nome.required' => 'Nome é obrigatório.',

            'sexo.required' => 'Sexo é obrigatório.',
            'sexo.integer' => 'Só é aceito o sexo masculino ou feminino',
            'sexo.min' => 'Só é aceito o sexo masculino ou feminino',
            'sexo.max' => 'Só é aceito o sexo masculino ou feminino',

            'filhos.required' => 'Campo de filhos é obrigatório.',
            'filhos.integer' => 'Só é aceito ter ou não filhos',
            'filhos.min' => 'Só é aceito ter ou não filhos',
            'filhos.max' => 'Só é aceito ter ou não filhos',

            'data_nasc.required' => 'Data de nascimento é obrigatória.',

            'id_uf.required' => 'UF é obrigatória.',
            'id_uf.integer' => 'UF escolhida não existe.',
            'id_uf.min' => 'UF escolhida não existe.',
            'id_uf.max' => 'UF escolhida não existe.',

            'telefone.integer' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone.min' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone.max' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone.unique' =>  'O telefone já existe.',

            'telefone_responsavel.integer' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone_responsavel.min' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone_responsavel.max' =>  'O telefone precisa de 11 dígitos: DDD + número',

            'id_formation.required' => 'Formação é obrigatória.',
            'id_formation.integer' => 'Formação escolhida não existe.',
            'id_formation.min' => 'Formação escolhida não existe.',
            'id_formation.max' => 'Formação escolhida não existe.',

            'id_sala.required' => 'Classe é obrigatória.',
            'id_sala.max' => 'Pessoa só pode ser cadastrada em uma classe',
            'id_sala.*.integer' => 'Classe digitada não existe',
            'id_sala.*.min' => 'Classe digitada não existe',
            'id_sala.*.max' => 'Classe digitada não existe',

            'interesse.required' => 'Interesse é obrigatório.',
            'interesse.integer' => 'Interesse escolhido não existe.',
            'interesse.min' => 'Interesse escolhido não existe.',
            'interesse.max' => 'Interesse escolhido não existe.',

            'frequencia_ebd.integer' => 'Frequência escolhida não existe.',
            'frequencia_ebd.min' => 'Frequência escolhida não existe.',
            'frequencia_ebd.max' => 'Frequência escolhida não existe.',

            'curso_teo.integer' => 'Valor inválido para curso de Teologia',
            'curso_teo.min' => 'Valor inválido para curso de Teologia',
            'curso_teo.max' => 'Valor inválido para curso de Teologia',

            'id_funcao.integer' => 'Pessoa só pode ser cadastrada como aluno',
            'id_funcao.min' => 'Pessoa só pode ser cadastrada como aluno',
            'id_funcao.max' => 'Pessoa só pode ser cadastrada como aluno',

            'situacao.integer' => 'Pessoa só pode ser cadastrada como ativa',
            'situacao.min' => 'Pessoa só pode ser cadastrada como ativa',
            'situacao.max' => 'Pessoa só pode ser cadastrada como ativa',

            'prof_ebd.integer' => 'Escolha para professor de EBD escolhida não existe.',
            'prof_ebd.min' => 'Escolha para professor de EBD escolhida não existe.',
            'prof_ebd.max' => 'Escolha para professor de EBD escolhida não existe.',

            'prof_comum.integer' => 'Escolha para professor comum escolhida não existe.',
            'prof_comum.min' => 'Escolha para professor comum escolhida não existe.',
            'prof_comum.max' => 'Escolha para professor comum escolhida não existe.',

            'id_public.integer' => 'Público escolhido não existe.',
            'id_public.min' => 'Público escolhido não existe.',
            'id_public.max' => 'Público escolhido não existe.',

        ]);

        $pessoa = new Pessoa;
        $pessoa->nome = $request->nome;
        $pessoa->sexo = $request->sexo;
        if ($request->filhos == 2 && $request->sexo == 1) {
            $pessoa->paternidade_maternidade = "Pai";
        } elseif ($request->filhos == 2 && $request->sexo == 2) {
            $pessoa->paternidade_maternidade = "Mãe";
        } else {
            $pessoa->paternidade_maternidade = null;
        }
        $pessoa->data_nasc = $request->data_nasc;
        $pessoa->responsavel = $request->responsavel;
        $pessoa->telefone_responsavel = $request->telefone_responsavel;
        $pessoa->ocupacao = $request->ocupacao;
        $pessoa->cidade = $request->cidade;
        $pessoa->id_uf = $request->id_uf;
        $pessoa->telefone = $request->telefone;
        $pessoa->id_formation = $request->id_formation;
        $pessoa->cursos = $request->cursos;
        $pessoa->id_sala = ["$nivelUser"];
        $pessoa->id_funcao = 1;
        $pessoa->situacao = 1;
        $pessoa->interesse = $request->interesse;
        $pessoa->frequencia_ebd = $request->frequencia_ebd;
        $pessoa->curso_teo = $request->curso_teo;
        $pessoa->prof_ebd = $request->prof_ebd;
        $pessoa->prof_comum = $request->prof_comum;
        $pessoa->id_public = $request->id_public;
        $pessoa->congregacao_id = auth()->user()->congregacao_id;
        $pessoa->save();
        return redirect('/classe/cadastro-pessoa')->with('msg', 'Aluno cadastrado com sucesso');

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
        $pessoas = Pessoa::whereJsonContains('id_sala', '' . $nivelUser);

        if ($request->nome) {
            $pessoas = $pessoas->where([['nome', 'like', '%' . $request->nome . '%']]);
        }

        if ($request->sexo) {
            $pessoas = $pessoas->where('sexo', $request->sexo);
        }

        if ($request->id_funcao) {
            $pessoas = $pessoas->where('id_funcao', $request->id_funcao);
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

        $pessoas = $pessoas->where('congregacao_id', '=', auth()->user()->congregacao_id)
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

        if (in_array("$nivelUser", $pessoa->id_sala)) {
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
        $pessoas = Pessoa::select('pessoas.id', 'pessoas.nome', 'data_nasc', 'id_funcao', DB::raw("0 as presenca"), 'funcaos.nome as nome_funcao')
            ->join('funcaos', 'funcaos.id', '=', 'pessoas.id_funcao')
            ->whereJsonContains('id_sala', '' . $sala)
            ->where('situacao', '=', 1)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('nome')
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
        $pessoas = DB::table('pessoas')
            ->select('nome', 'data_nasc', 'id_funcao')
            ->whereJsonContains('id_sala', '' . $sala)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->where('situacao', '=', 1)
            ->orderBy('nome')->get();

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

        $result = $this->chamadaRelatorioService->saveRelatorio($chamadaRealizada);

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

