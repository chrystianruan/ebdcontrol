<?php

namespace App\Http\Controllers;

use App\Http\Enums\StatusEnum;
use App\Http\Enums\TipoPresenca;
use App\Http\Repositories\ChamadaDiaCongregacaoRepository;
use App\Http\Repositories\ChamadaRepository;
use App\Http\Repositories\PessoaRepository;
use App\Http\Services\ChamadaService;
use App\Http\Services\PessoaService;
use App\Mail\ChamadaRealizadaMail;
use App\Mail\PessoaCadastradaMail;
use App\Http\Services\PresencaPessoaService;
use App\Models\ChamadaDiaCongregacao;
use App\Models\PessoaSala;
use App\Models\Congregacao;
use Illuminate\Database\Eloquent\Collection;
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
use Illuminate\Support\Facades\Log;

class ClasseController extends Controller
{

    protected $generalController;
    protected $relatorioService;
    protected $chamadaService;
    protected $pessoaService;
    protected $chamadaDiaCongregacaoRepository;
    protected $pessoaRepository;
    protected $presencaPessoaService;
    private $chamadaRepository;

    public function __construct(
        GeneralController $generalController,
        RelatorioService $relatorioService,
        ChamadaDiaCongregacaoRepository $chamadaDiaCongregacaoRepository,
        ChamadaService $chamadaService,
        PessoaService $pessoaService,
        PessoaRepository $pessoaRepository,
        PresencaPessoaService $presencaPessoaService,
        ChamadaRepository $chamadaRepository
    )
    {
        $this->generalController = $generalController;
        $this->relatorioService = $relatorioService;
        $this->chamadaDiaCongregacaoRepository = $chamadaDiaCongregacaoRepository;
        $this->chamadaService = $chamadaService;
        $this->pessoaService = $pessoaService;
        $this->pessoaRepository = $pessoaRepository;
        $this->presencaPessoaService = $presencaPessoaService;
        $this->chamadaRepository = $chamadaRepository;
    }

    public function indexClasse()
    {
        $nivel = auth()->user()->sala_id;
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
        $funcoes = $this->pessoaService->getArrayQuantidadePessoasPerFuncao(auth()->user()->sala_id);
        $interesseProf = $this->pessoaRepository->findByInteresseAndCongregacaoAndSalaCount(auth()->user()->sala_id);
        $chamadaDia = Chamada::where('id_sala', '=', $nivel)
            ->whereDate('created_at', Carbon::today())
            ->get();
        $chamadasMes = Chamada::where('id_sala', '=', $nivel)
            ->whereMonth('created_at', Carbon::now())
            ->whereYear('created_at', '=', Carbon::now())
            ->get();
        $chamadasAno = Chamada::where('id_sala', '=', $nivel)
            ->whereYear('created_at', Carbon::now())
            ->get();
        $niverMes = $this->pessoaRepository->getAniversariantesMes(auth()->user()->sala_id);
        $alunosInativos = $this->pessoaRepository->getInativos(auth()->user()->sala_id);
        $codigoClasse = Sala::find(auth()->user()->sala_id)->hash;

        $chamadaDiaBD = $this->chamadaDiaCongregacaoRepository->findChamadaDiaToday(auth()->user()->congregacao_id, date('Y-m-d'));
        if ($chamadaDiaBD) {
            $dateChamadaDia = $chamadaDiaBD->date;
        } else {
            $dateChamadaDia = null;
        }

        return view('/classe/dashboard', ['niverMes' => $niverMes, 'alunosInativos' => $alunosInativos,
            'chamadaDia' => $chamadaDia, 'interesseProf' => $interesseProf, 'idadesPessoas' => $idadesPessoas, 'formacoes' => $formacoes,
            'chamadasMes' => $chamadasMes, 'chamadasAno' => $chamadasAno, 'funcoes' => $funcoes, 'dateChamadaDia' => $dateChamadaDia, 'codigoClasse' => $codigoClasse]);
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
        $nivelUser = auth()->user()->sala_id;
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
        $nivelUser = auth()->user()->sala_id;
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
        $sala = auth()->user()->sala_id;
        $chamadaPadraoRealizada = $this->chamadaRepository->getChamadaPadraoToday($sala);
        $pessoasNotFormated = $this->pessoaRepository->findBySalaIdAndSituacaoWithPresenca($sala);
        $pessoas = $this->formatPessoas($pessoasNotFormated);
        $quantidadePresencas = $this->chamadaRepository->getChamadaToday($sala) ? $this->chamadaRepository->getChamadaToday($sala)->presentes : 0;
        $salas = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->get();

        $chamadaDiaBD = $this->chamadaDiaCongregacaoRepository->findChamadaDiaToday(auth()->user()->congregacao_id, date('Y-m-d'));
        if ($chamadaDiaBD) {
            $dateChamadaDia = $chamadaDiaBD->date;
        } else {
            $dateChamadaDia = null;
        }

        return view('/classe/chamada-dia', ['chamadaPadraoRealizada' => $chamadaPadraoRealizada,
            'salas' => $salas, 'pessoas' => $pessoas,
            'dateChamadaDia' => $dateChamadaDia, 'quantidadePresencas' => $quantidadePresencas]);
    }

    public function getQuantidadePresentes(\Illuminate\Support\Collection $pessoas) : int
    {
        $quantidadePresencas = 0;
        foreach ($pessoas as $pessoa) {
            if ($pessoa->presenca) {
                $quantidadePresencas++;
            }
        }
        return $quantidadePresencas;
    }

    public function formatPessoas(Collection $pessoas) : \Illuminate\Support\Collection
    {
        $pessoasFormat = [];
        foreach ($pessoas as $pessoa) {
            $pessoaBd = Pessoa::find($pessoa->pessoa_id);
            $p =
                (object)
                [
                    'pessoa_id' => (int) $pessoa->pessoa_id,
                    'pessoa_nome' => $pessoa->pessoa_nome,
                    'funcao_nome' => $pessoa->funcao_nome,
                    'funcao_id' => (int) $pessoa->funcao_id,
                    'presenca' => (bool) $pessoaBd->presente(),
                    'dados_presenca' => $pessoaBd->dadosPresenca()
                ];
            array_push($pessoasFormat, $p);
        }
        return collect($pessoasFormat);
    }

    public function searchChamadaClasse(Request $request)
    {
        $mes = request('mes');
        $ano = request('ano');
        $sala = auth()->user()->sala_id;
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

    public function searchAniversariantes(Request $request)
    {
        $nivel = auth()->user()->sala_id;
        $mes = request('mes');
        $salas = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->get();
        $meses_abv = [1 => 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        //mes
        if (isset($request->mes)) {
            $pessoas = PessoaSala::select('pessoas.*', 'funcaos.nome as nome_funcao')
                ->join('funcaos', 'funcaos.id', '=', 'pessoa_salas.funcao_id')
                ->join('pessoas', 'pessoas.id', '=', 'pessoa_salas.pessoa_id')
                ->where('sala_id', $nivel)
                ->where('pessoas.situacao', StatusEnum::ATIVO->value)
                ->whereMonth('pessoas.data_nasc', '=', $request->mes)
                ->orderBy('pessoas.nome')
                ->get();

        } else {
            $pessoas = PessoaSala::select('pessoas.*', 'funcaos.nome as nome_funcao')
                ->join('funcaos', 'funcaos.id', '=', 'pessoa_salas.funcao_id')
                ->join('pessoas', 'pessoas.id', '=', 'pessoa_salas.pessoa_id')
                ->where('sala_id', $nivel)
                ->where('pessoas.situacao', StatusEnum::ATIVO->value)
                ->whereMonth('pessoas.data_nasc', '=', Carbon::now())
                ->orderBy('pessoas.nome')
                ->get();
        }

        return view('/classe/aniversariantes', ['pessoas' => $pessoas, 'salas' => $salas,
            'meses_abv' => $meses_abv, 'mes' => $mes]);
    }

//    public function generateRelatorioPerDate(Request $request)
//    {
//
//        $presencas = $this->returnData($request->initialDate, $request->finalDate, $request->congregacaoId, $request->classeId);
//
//        return $presencas;
//    }
//
//    public function returnData($initial_date, $final_date, $congregacao_id, $classe_id)
//    {
//        $chamadas = $this->generalController->getChamadas($initial_date, $final_date, $congregacao_id, $classe_id);
//        $duplicatesNamesAndPresencas = $this->generalController->getListWithNameAndPresencasDuplicates($chamadas);
//
//        return $duplicatesNamesAndPresencas;
//    }
}

