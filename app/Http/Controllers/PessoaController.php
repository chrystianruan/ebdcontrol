<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Http\Requests\UpdatePessoaRequest;
use App\Http\Services\PessoaService;
use App\Models\Congregacao;
use App\Models\Formation;
use App\Models\LinkCadastroGeral;
use App\Models\Pessoa;
use App\Models\Publico;
use App\Models\Sala;
use App\Models\Uf;
use Illuminate\Http\Request;
use Termwind\Components\Li;
use Illuminate\Support\Facades\DB;

class PessoaController extends Controller
{
    private $linkCadastroGeral;
    private $pessoaService;
    private $ufs;
    private $publicos;
    private $formations;

    public function __construct(LinkCadastroGeral $linkCadastroGeral, PessoaService $pessoaService) {
        $this->linkCadastroGeral = $linkCadastroGeral;
        $this->pessoaService = $pessoaService;
        $this->publicos = Publico::all();
        $this->formations = Formation::all();
        $this->ufs = Uf::orderBy("nome")->get();
    }
    public function liberarLinkCadastroGeral(Request $req) {
        return $this->pessoaService->liberarLinkGeral(intval($req->congregacao));
    }

    public function desabilitarLinkCadastroGeral() {

    }
    public function indexCadastroClasse() {
        $title = "Cadastro Classe";
        $check = request('scales');
        $route = "cadastro.pessoa.classe";

        $congregacao = Congregacao::select('*', DB::raw('congregacaos.id as congregacao_id'),DB::raw('setors.id as setor_id'), DB::raw('congregacaos.nome as congregacao_nome'), DB::raw('setors.nome as setor_nome'))
            ->join('setors', 'setors.id', '=', 'congregacaos.setor_id')
            ->findOrFail(auth()->user()->congregacao_id);

        return view('/classe/cadastro-pessoa', ['ufs' => $this->ufs, 'publicos' => $this->publicos,
            'formations' => $this->formations, 'check' => $check, 'congregacao' => $congregacao, 'title' => $title,
            'route' => $route]);
    }

    public function indexCadastroAdmin() {
        $classes = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('nome')->get();

        $title = "Cadastro Admin";
        $check = request('scales');
        $route = "cadastro.pessoa.admin";

        $congregacao = Congregacao::select('*', DB::raw('congregacaos.id as congregacao_id'),DB::raw('setors.id as setor_id'), DB::raw('congregacaos.nome as congregacao_nome'), DB::raw('setors.nome as setor_nome'))
            ->join('setors', 'setors.id', '=', 'congregacaos.setor_id')
            ->findOrFail(auth()->user()->congregacao_id);

        return view('/admin/cadastro/pessoa', ['classes' => $classes, 'ufs' => $this->ufs, 'publicos' => $this->publicos,
            'formations' => $this->formations, 'check' => $check, 'congregacao' => $congregacao, 'title' => $title,
            'route' => $route]);
    }

    public function indexCadastroGeral($congregacaoId) {
        if (!$this->linkCadastroGeral->getLinkActive(base64_decode($congregacaoId))) {
            return abort(404);
        }
        $classes = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', base64_decode($congregacaoId))
            ->orderBy('nome')->get();
        $title = "Cadastro Geral";
        $route = "cadastro.pessoa.geral";
        $check = request('scales');

        $congregacao = Congregacao::select('*', DB::raw('congregacaos.id as congregacao_id'),DB::raw('setors.id as setor_id'), DB::raw('congregacaos.nome as congregacao_nome'), DB::raw('setors.nome as setor_nome'))
            ->join('setors', 'setors.id', '=', 'congregacaos.setor_id')
            ->findOrFail(base64_decode($congregacaoId));

        return view('/cadastro', ['classes' => $classes, 'ufs' => $this->ufs, 'publicos' => $this->publicos,
            'formations' => $this->formations, 'check' => $check, 'congregacao' => $congregacao, 'title' => $title,
            'route' => $route]);
    }

    public function store(StorePessoaRequest $request) {
        return $this->pessoaService->store($request);
    }

    public function update(UpdatePessoaRequest $request) {
        return $this->pessoaService->update($request);
    }

    public function searchPessoa(Request $request) {
        $nome = request('nome');
        $sexo = request('sexo');
        $paternidade_maternidade = request('paternidade_maternidade');
        $sala1 = request('sala');
        $interesse = request('interesse');
        $id_funcao = request('id_funcao');
        $situacao = request('situacao');
        $niver = request('niver');
        $salas = Sala::where('id', '>', 2)
            ->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('nome')->get();
        $dataAtual = date('Y-m-d');
        $meses_abv = [1 => 'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

        $pessoas = Pessoa::select('pessoas.*');

        if ($request->nome) {
            $pessoas = $pessoas->where([['nome', 'like', '%'.$request->nome.'%']]);
        }

        if ($request->sexo) {
            $pessoas = $pessoas->where('sexo', $request->sexo);
        }

        if ($request->paternidade_maternidade) {
            $pessoas = $pessoas->where('paternidade_maternidade', $request->paternidade_maternidade);
        }

        if ($request->sala) {
            $pessoas = $pessoas->whereJsonContains('id_sala', $request->sala);
        }

        if($request->id_funcao) {
            $pessoas = $pessoas->where('id_funcao', $request->id_funcao);
        }

        if($request->interesse) {
            $pessoas = $pessoas->where('interesse', $request->interesse)
                ->where('id_funcao', '<>', 2);
        }

        if ($request->situacao) {
            $pessoas = $pessoas->where('situacao', $request->situacao);
        }

        if ($request->niver) {
            $pessoas = $pessoas->whereMonth('data_nasc', $request->niver);
        }

        $pessoas = $pessoas->where('congregacao_id', '=', auth()->user()->congregacao_id)
            ->orderBy('nome')
            ->get();

        return view('/admin/filtro/pessoa',['pessoas' => $pessoas, 'niver' => $niver, 'meses_abv' => $meses_abv,
            'salas' => $salas, 'nome' => $nome, 'sexo' => $sexo, 'paternidade_maternidade' => $paternidade_maternidade,
            'id_funcao' => $id_funcao, 'interesse' => $interesse, 'situacao' => $situacao, 'sala1' => $sala1,
            'dataAtual' => $dataAtual]);
    }


}
