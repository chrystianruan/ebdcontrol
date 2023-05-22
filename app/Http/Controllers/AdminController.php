<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formation;
use App\Models\Pessoa;
use App\Models\Publico;
use App\Models\Funcao;
use App\Models\User;
use App\Models\Sala;
use App\Models\Uf;
use App\Models\Financeiro_cat;
use App\Models\Financeiro_tipo;
use App\Models\Financeiro_transacao;
use App\Models\Financeiro;
use App\Models\Aviso;
use App\Models\Chamada;
use App\Models\Relatorio;
use Carbon\Carbon;
use DB;


class AdminController extends Controller
{
    //entrada cadastro master e classe
  
    //fim

    public function index() {
        $salas = Sala::where('id', '>', 2)->orderBy('nome')->get();
        $formations = DB::select('SELECT id_formation, count(p.id) as qtd, f.nome  FROM pessoas as p LEFT JOIN formations as f ON  p.id_formation = f.id GROUP BY (id_formation)');
        $dataMes = date('n');     
        $dataAno = date('Y');
        $mesesNome = [1 => 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $meses = Pessoa::selectRaw('count(id) as qtd, MONTH(created_at) as mes')
        ->whereRaw('MONTH(created_at) > 0 AND MONTH(created_at) <= 12')
        ->whereYear('created_at', '=',$dataAno)
        ->groupBy('mes')
        ->get();
        $interesseProf = Pessoa::where('interesse', '<>', 2)->where('id_funcao', '<>', 2)->count();
        $idadesPessoas = DB::select('SELECT count(id) as qtd, floor( (unix_timestamp(current_timestamp()) - unix_timestamp(pessoas.data_nasc)) / (60 * 60 * 24) /365.25) as idades from pessoas group by (floor( (unix_timestamp(current_timestamp()) - unix_timestamp(pessoas.data_nasc)) / (60 * 60 * 24) /365.25));');
        $niverMes = Pessoa::whereMonth('data_nasc', '=', $dataMes)->count();
        $mativo = Pessoa::where('sexo', '=', 1)->where('situacao', '=', 1)->count();
        $minativo = Pessoa::where('sexo', '=', 1)->where('situacao', '=', 2)->count();
        $fativo = Pessoa::where('sexo', '=', 2)->where('situacao', '=', 1)->count();
        $finativo = Pessoa::where('sexo', '=', 2)->where('situacao', '=', 2)->count();
        $alunosInativos = Pessoa::where('situacao', '=', 2)->count();
        $sexos = [$mativo, $minativo, $fativo, $finativo];
        $funcoes = DB::table('pessoas')
        ->select(DB::raw('count(pessoas.id) as qtd, id_funcao, funcaos.nome'))
        ->leftJoin('funcaos', 'pessoas.id_funcao', '=', 'funcaos.id')
        ->groupBy('id_funcao')
        ->get();
        $chamadasMesTotal = Chamada::select(DB::raw('date_format(created_at, "%d/%m/%Y") as data, sum(matriculados) as mat, sum(presentes) as pre, sum(visitantes) as vis'))
        ->whereMonth('created_at', '=', Carbon::now())
        ->groupBy(DB::raw('date_format(created_at, "%d/%m/%Y")'))->get();
        $chamadaDia = Chamada::whereDate('created_at','=', Carbon::today())->get();
        $chamadasMes = Chamada::whereMonth('created_at', '=', Carbon::now())->get();
        $chamadasAno = Chamada::whereYear('created_at', '=', Carbon::now())->get();
        $pessoas = Pessoa::orderBy('nome')->get();
        return view('/admin/dashboard', ['salas' => $salas, 'formations' => $formations, 'pessoas' => $pessoas,
         'meses' => $meses, 'mesesNome' => $mesesNome, 'niverMes' => $niverMes,'dataAno' => $dataAno,
         'sexos' => $sexos,'funcoes' => $funcoes, 'interesseProf' => $interesseProf,
          'idadesPessoas' => $idadesPessoas, 'alunosInativos' => $alunosInativos,  'chamadaDia' => $chamadaDia,
          'chamadasMes' => $chamadasMes, 'chamadasMesTotal' => $chamadasMesTotal, 'chamadasAno' => $chamadasAno]);
    }

    public function indexPessoa() {
        $check = request('scales');
        $salas = Sala::where('id', '>', 2)->orderBy('nome')->get();
        $ufs = Uf::orderBy("nome")->get();
        $publicos = Publico::all();
        $formations = Formation::all();
        $dataAtual = date('d/m/Y');
        return view('/admin/cadastro/pessoa', ['dataAtual' => $dataAtual, 'salas' => $salas, 'ufs' => $ufs, 'publicos' => $publicos,
         'formations' => $formations, 'check' => $check]);
    }

    public function storePessoa(Request $request) {
        $publicos = Publico::all();
        $ufs = Uf::all();
        $formations = Formation::all();
        $lastSala = Sala::orderBy('id', 'desc')->first();
        $this->validate($request, [
            'nome' => ['required'],
            'sexo' => ['required', 'integer', 'min: 1', 'max: 2'],
            'data_nasc' => ['required'],
            'id_uf' => ['required', 'integer', 'min: 1', 'max:'.$ufs->count()],
            'telefone' => ['nullable', 'integer', 'min:11111111111', 'max:99999999999', 'unique:pessoas,telefone'],
            'id_formation' => ['required', 'integer', 'min: 1', 'max:'.$formations->count()],
            'id_sala' => ['required', 'max: 1'],
            'id_sala.*' => ['integer', 'min: 3', 'max:'.$lastSala->id],
            'interesse' => ['required', 'integer', 'min: 1', 'max: 3'],
            'frequencia_ebd' => ['integer', 'min: 1', 'max: 3'],
            'curso_teo' => ['integer', 'min: 1', 'max: 2'],
            'id_funcao' => ['integer', 'min:1', 'max:1'],
            'situacao' => ['integer', 'min:1', 'max:1'],
            'prof_ebd' => ['integer', 'min: 1', 'max: 2'],
            'prof_comum' => ['integer', 'min: 1', 'max: 2'],
            'id_public' => ['integer', 'min: 1', 'max:'.$publicos->count()],
        ], [
            'nome.required' =>  'Nome é obrigatório.',

            'sexo.required' =>  'Sexo é obrigatório.',
            'sexo.integer' =>  'Só é aceito o sexo masculino ou feminino',
            'sexo.min' =>  'Só é aceito o sexo masculino ou feminino',
            'sexo.max' =>  'Só é aceito o sexo masculino ou feminino',

            'data_nasc.required' =>  'Data de nascimento é obrigatória.',

            'id_uf.required' =>  'UF é obrigatória.',
            'id_uf.integer' =>  'UF escolhida não existe.',
            'id_uf.min' =>  'UF escolhida não existe.',
            'id_uf.max' =>  'UF escolhida não existe.',

            'telefone.integer' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone.min' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone.max' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone.unique' =>  'O telefone já existe.',

            'id_formation.required' =>  'Formação é obrigatória.',
            'id_formation.integer' =>  'Formação escolhida não existe.',
            'id_formation.min' =>  'Formação escolhida não existe.',
            'id_formation.max' =>  'Formação escolhida não existe.',

            'id_sala.required' =>  'Classe é obrigatória.',
            'id_sala.max' =>  'Pessoa só pode ser cadastrada em uma classe',
            'id_sala.*.integer' =>  'Classe digitada não existe',
            'id_sala.*.min' =>  'Classe digitada não existe',
            'id_sala.*.max' =>  'Classe digitada não existe',

            'interesse.required' =>  'Interesse é obrigatório.',
            'interesse.integer' =>  'Interesse escolhido não existe.',
            'interesse.min' =>  'Interesse escolhido não existe.',
            'interesse.max' =>  'Interesse escolhido não existe.',

            'frequencia_ebd.integer' =>  'Frequência escolhida não existe.',
            'frequencia_ebd.min' =>  'Frequência escolhida não existe.',
            'frequencia_ebd.max' =>  'Frequência escolhida não existe.',

            'curso_teo.integer' =>  'Valor inválido para curso de Teologia',
            'curso_teo.min' =>  'Valor inválido para curso de Teologia',
            'curso_teo.max' =>  'Valor inválido para curso de Teologia',

            'id_funcao.integer' =>  'Pessoa só pode ser cadastrada como aluno',
            'id_funcao.min' =>  'Pessoa só pode ser cadastrada como aluno',
            'id_funcao.max' =>  'Pessoa só pode ser cadastrada como aluno',

            'situacao.integer' =>  'Pessoa só pode ser cadastrada como ativa',
            'situacao.min' =>  'Pessoa só pode ser cadastrada como ativa',
            'situacao.max' =>  'Pessoa só pode ser cadastrada como ativa',

            'prof_ebd.integer' =>  'Escolha para professor de EBD escolhida não existe.',
            'prof_ebd.min' =>  'Escolha para professor de EBD escolhida não existe.',
            'prof_ebd.max' =>  'Escolha para professor de EBD escolhida não existe.',

            'prof_comum.integer' =>  'Escolha para professor comum escolhida não existe.',
            'prof_comum.min' =>  'Escolha para professor comum escolhida não existe.',
            'prof_comum.max' =>  'Escolha para professor comum escolhida não existe.',

            'id_public.integer' =>  'Público escolhido não existe.',
            'id_public.min' =>  'Público escolhido não existe.',
            'id_public.max' =>  'Público escolhido não existe.',

        ]);
        
        $pessoa = new Pessoa;
        $pessoa-> nome = $request->nome;
        $pessoa-> sexo = $request->sexo;
        $pessoa-> data_nasc = $request->data_nasc;
        $pessoa-> responsavel = $request->responsavel;
        $pessoa-> ocupacao = $request->ocupacao;
        $pessoa-> cidade = $request->cidade;
        $pessoa-> id_uf = $request->id_uf;
        $pessoa-> telefone = $request->telefone;
        $pessoa-> id_formation = $request->id_formation;
        $pessoa-> cursos = $request->cursos;
        $pessoa-> id_sala = ["$request->id_sala"];
        $pessoa-> id_funcao = 1;
        $pessoa-> situacao = 1;
        $pessoa-> interesse = $request->interesse;
        $pessoa-> frequencia_ebd = $request->frequencia_ebd;
        $pessoa-> curso_teo = $request->curso_teo;
        $pessoa-> prof_ebd = $request->prof_ebd;
        $pessoa-> prof_comum = $request->prof_comum;
        $pessoa-> id_public = $request->id_public;
        $pessoa -> save();
        return redirect('/admin/cadastro/pessoa')->with('msg', 'Pessoa cadastrada com sucesso');
    }

   

    
    public function showFilterPessoa() {
        $pessoas = Pessoa::orderBy('nome')->get();
        $salas = Sala::where('id', '>', 2)->orderBy('nome')->get();
        $dataAtual = date('Y-m-d');
        $meses_abv = [1 => 'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        return view('/admin/filtro/pessoa',['pessoas' => $pessoas, 'meses_abv' => $meses_abv, 'salas' => $salas, 'dataAtual' => $dataAtual]);
    }

    public function searchPessoa(Request $request) {
        $nome = request('nome');
        $sexo = request('sexo');
        $sala1 = request('sala');
        $interesse = request('interesse');
        $id_funcao = request('id_funcao');
        $situacao = request('situacao');
        $niver = request('niver');
        $salas = Sala::where('id', '>', 2)->orderBy('nome')->get();
        $dataAtual = date('Y-m-d');
        $meses_abv = [1 => 'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

        $pessoas = Pessoa::select('pessoas.*');

        if ($request->nome) {
            $pessoas = $pessoas->where([['nome', 'like', '%'.$request->nome.'%']]);
        }

        if ($request->sexo) {
            $pessoas = $pessoas->where('sexo', $request->sexo);
        }

        if ($request->sala) {
            $pessoas = $pessoas->whereJsonContains('id_sala', $request->sala);
        }

        if($request->id_funcao) {
            $pessoas = $pessoas->where('id_funcao', $request->id_funcao);
        }

        if ($request->situacao) {
            $pessoas = $pessoas->where('situacao', $request->situacao);
        }

        if ($request->niver) {
            $pessoas = $pessoas->whereMonth('data_nasc', $request->niver);
        }

        $pessoas = $pessoas->orderBy('nome')->get();
      
        return view('/admin/filtro/pessoa',['pessoas' => $pessoas, 'niver' => $niver, 'meses_abv' => $meses_abv, 'salas' => $salas, 'nome' => $nome, 'sexo' => $sexo, 'id_funcao' => $id_funcao, 'situacao' => $situacao, 'sala1' => $sala1, 'dataAtual' => $dataAtual]);
    }



    public function saberMais($id) {
        $dataAtual = date('Y-m-d');
        $pessoa = Pessoa::findOrFail($id);
        $salas = Sala::where('id', '>', 2)->orderBy('nome')->get();
        $ufs = Uf::orderBy("nome")->get();
        $publicos = Publico::all();
        $formations = Formation::all();
        return view('/admin/saber_mais', ['pessoa' => $pessoa, 'ufs' => $ufs, 'dataAtual' => $dataAtual, 'salas' => $salas, 'publicos' => $publicos, 'formations' => $formations]);

    }


    public function editPessoa($id) {
        
        $dataAtual = date('Y-m-d');
        $pessoa = Pessoa::findOrFail($id);
        $salas = Sala::where('id', '>', 2)->orderBy('nome')->get();
        $ufs = Uf::orderBy("nome")->get();
        $functions = Funcao::all();
        $publicos = Publico::all();
        $formations = Formation::all();
        return view('/admin/edit/pessoa', ['pessoa' => $pessoa, 'functions' => $functions, 'ufs' => $ufs, 'dataAtual' => $dataAtual, 'salas' => $salas, 'publicos' => $publicos, 'formations' => $formations]);

    }

    public function updatePessoa(Request $request){
        $ufs = Uf::all();
        $formations = Formation::all();
        $publicos = Publico::all();
        $lastSala = Sala::orderBy('id', 'desc')->first();
        $this->validate($request, [
            'nome' => ['required'],
            'sexo' => ['required', 'integer', 'min: 1', 'max: 2'],
            'data_nasc' => ['required'],
            'id_uf' => ['required', 'integer', 'min: 1', 'max:'.$ufs->count()],
            'telefone' => ['nullable', 'integer', 'min:11111111111', 'max:99999999999', 'unique:pessoas,telefone,'.$request->id],
            'id_formation' => ['required', 'integer', 'min: 1', 'max:'.$formations->count()],
            'id_sala' => ['required', 'max: 2'],
            'id_sala.*' => ['integer', 'min: 3', 'max:'.$lastSala -> id, 'distinct'],
            'interesse' => ['required'],
            'frequencia_ebd' => ['integer', 'min: 1', 'max: 3'],
            'id_funcao' => ['integer', 'min:1', 'max:5'],
            'situacao' => ['integer', 'min:1', 'max:2'],
            'curso_teo' => ['integer', 'min: 1', 'max: 2'],
            'prof_ebd' => ['integer', 'min: 1', 'max: 2'],
            'prof_comum' => ['integer', 'min: 1', 'max: 2'],
            'id_public' => ['integer', 'min: 1', 'max:'.$publicos->count()],
        ], [
            'nome.required' =>  'Nome é obrigatório.',

            'sexo.required' =>  'Sexo é obrigatório.',
            'sexo.integer' =>  'Só é aceito o sexo masculino ou feminino',
            'sexo.min' =>  'Só é aceito o sexo masculino ou feminino',
            'sexo.max' =>  'Só é aceito o sexo masculino ou feminino',
            'data_nasc.required' =>  'Data de nascimento é obrigatória.',
            'id_uf.required' =>  'UF é obrigatória.',
            'id_uf.integer' =>  'UF escolhida não existe.',
            'id_uf.min' =>  'UF escolhida não existe.',
            'id_uf.max' =>  'UF escolhida não existe.',
    
            'telefone.integer' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone.min' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone.max' =>  'O telefone precisa de 11 dígitos: DDD + número',
            'telefone.unique' =>  'O telefone já existe.',

            'id_formation.required' =>  'Formação é obrigatória.',
            'id_formation.integer' =>  'Formação escolhida não existe.',
            'id_formation.min' =>  'Formação escolhida não existe.',
            'id_formation.max' =>  'Formação escolhida não existe.',
            'id_sala.required' =>  'Classe é obrigatória.',
            'id_sala.max' =>  'Pessoa só pode pertencer a até 2 classes',
            'id_sala.*.integer' =>  'Classe digitada não existe',
            'id_sala.*.min' =>  'Classe digitada não existe',
            'id_sala.*.max' =>  'Classe digitada não existe',
            'id_sala.*.distinct' =>  'Pessoa não pode pertencer à mesma classe 2 ou mais vezes', 
            'interesse.required' =>  'Interesse é obrigatório.',
            'interesse.integer' =>  'Interesse escolhido não existe.',
            'interesse.min' =>  'Interesse escolhido não existe.',
            'interesse.max' =>  'Interesse escolhido não existe.',
            'frequencia_ebd.integer' =>  'Frequência escolhida não existe.',
            'frequencia_ebd.min' =>  'Frequência escolhida não existe.',
            'frequencia_ebd.max' =>  'Frequência escolhida não existe.',
            'curso_teo.integer' =>  'Valor inválido para curso de Teologia',
            'curso_teo.min' =>  'Valor inválido para curso de Teologia',
            'curso_teo.max' =>  'Valor inválido para curso de Teologia',
            'id_funcao.integer' =>  'Função escolhida não existe',
            'id_funcao.min' =>  'Função escolhida não existe',
            'id_funcao.max' =>  'Função escolhida não existe',
            'situacao.integer' =>  'Situação escolhida não existe',
            'situacao.min' =>  'Situação escolhida não existe',
            'situacao.max' =>  'Situação escolhida não existe',
            'prof_ebd.integer' =>  'Escolha para professor de EBD escolhida não existe.',
            'prof_ebd.min' =>  'Escolha para professor de EBD escolhida não existe.',
            'prof_ebd.max' =>  'Escolha para professor de EBD escolhida não existe.',
            'prof_comum.integer' =>  'Escolha para professor comum escolhida não existe.',
            'prof_comum.min' =>  'Escolha para professor comum escolhida não existe.',
            'prof_comum.max' =>  'Escolha para professor comum escolhida não existe.',
            'id_public.integer' =>  'Público escolhido não existe.',
            'id_public.min' =>  'Público escolhido não existe.',
            'id_public.max' =>  'Público escolhido não existe.',
        ]);

        Pessoa::findOrFail($request -> id)->update($request->all());
        return redirect('/admin/filtro/pessoa')->with('msg', 'Pessoa foi atualizada com sucesso');
    }

   

    
    public function destroyPessoa($id) {
        Pessoa::findOrFail($id)->delete();
        return redirect('/admin/filtro/pessoa')->with('msg', 'Pessoa deletado com sucesso');
        
    }

 
    public function indexFinanceiroGeral() {
        $ents = Financeiro_transacao::where('id_financeiro', '=', 1)->where('situacao', '=', 1)->orderBy('data_cad')->get();
        $cats = Financeiro_cat::orderBy("nome")->get();
        $tipos = Financeiro_tipo::all();
        $dataMes = date('n');     
        $dataAno = date('Y');
        $jE = Financeiro_transacao::whereMonth('data_cad', '=',1)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->sum('valor');
        $fE = Financeiro_transacao::whereMonth('data_cad', '=',2)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->sum('valor');
        $mE = Financeiro_transacao::whereMonth('data_cad', '=',3)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->sum('valor');
        $aE = Financeiro_transacao::whereMonth('data_cad', '=',4)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->sum('valor');
        $maE = Financeiro_transacao::whereMonth('data_cad', '=',5)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->sum('valor');
        $junE = Financeiro_transacao::whereMonth('data_cad', '=',6)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->sum('valor');
        $julE = Financeiro_transacao::whereMonth('data_cad', '=',7)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->sum('valor');
        $agE = Financeiro_transacao::whereMonth('data_cad', '=',8)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->sum('valor');
        $sE = Financeiro_transacao::whereMonth('data_cad', '=',9)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->sum('valor');
        $oE = Financeiro_transacao::whereMonth('data_cad', '=',10)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->sum('valor');
        $nE = Financeiro_transacao::whereMonth('data_cad', '=',11)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->sum('valor');
        $dE = Financeiro_transacao::whereMonth('data_cad', '=',12)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->sum('valor');
        $mesesE = [$jE, $fE, $mE, $aE, $maE, $junE, $julE, $agE, $sE, $oE, $nE, $dE];
        $jS = Financeiro_transacao::whereMonth('data_cad', '=',1)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->sum('valor');
        $fS = Financeiro_transacao::whereMonth('data_cad', '=',2)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->sum('valor');
        $mS = Financeiro_transacao::whereMonth('data_cad', '=',3)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->sum('valor');
        $aS = Financeiro_transacao::whereMonth('data_cad', '=',4)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->sum('valor');
        $maS = Financeiro_transacao::whereMonth('data_cad', '=',5)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->sum('valor');
        $junS = Financeiro_transacao::whereMonth('data_cad', '=',6)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->sum('valor');
        $julS = Financeiro_transacao::whereMonth('data_cad', '=',7)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->sum('valor');
        $agS = Financeiro_transacao::whereMonth('data_cad', '=',8)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->sum('valor');
        $sS = Financeiro_transacao::whereMonth('data_cad', '=',9)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->sum('valor');
        $oS = Financeiro_transacao::whereMonth('data_cad', '=',10)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->sum('valor');
        $nS = Financeiro_transacao::whereMonth('data_cad', '=',11)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->sum('valor');
        $dS = Financeiro_transacao::whereMonth('data_cad', '=',12)->whereYear('data_cad', '=',$dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->sum('valor');
        $mesesS = [$jS, $fS, $mS, $aS, $maS, $junS, $julS, $agS, $sS, $oS, $nS, $dS];
        $saldosMeses = [($jE - $jS), ($fE - $fS), ($mE - $mS), ($aE - $aS), ($maE - $maS), ($junE - $junS), ($julE - $julS), 
        ($agE - $agS), ($sE - $sS), ($oE - $oS), ($nE - $nS), ($dE - $dS)];
        $entradas = Financeiro_transacao::where('id_financeiro', '=', 1)->where('situacao', '=', 1)->get();
        $saidas = Financeiro_transacao::where('id_financeiro', '=', 2)->where('situacao', '=', 1)->get();
        $entradasMes = Financeiro_transacao::whereMonth('data_cad', '=', $dataMes)->whereYear('data_cad', '=', $dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->get();
        $saidasMes = Financeiro_transacao::whereMonth('data_cad', '=', $dataMes)->whereYear('data_cad', '=', $dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->get();
        $entradasAno = Financeiro_transacao::whereYear('data_cad', '=', $dataAno)->where('id_financeiro', '=', 1)->where('situacao', '=', 1)->get();
        $saidasAno = Financeiro_transacao::whereYear('data_cad', '=', $dataAno)->where('id_financeiro', '=', 2)->where('situacao', '=', 1)->get();
        $catsEnt = DB::select('SELECT id_cat, nome, sum(valor) as somaE FROM financeiro_transacaos as ef LEFT JOIN financeiro_cats as cf ON  ef.id_cat = cf.id WHERE id_financeiro = 1 AND situacao = 1 GROUP BY (id_cat);');
        $catsSaida = DB::select('SELECT id_cat, nome, sum(valor) as somaS FROM financeiro_transacaos as sf LEFT JOIN financeiro_cats as cf ON  sf.id_cat = cf.id WHERE id_financeiro = 2 AND situacao = 1 GROUP BY (id_cat);');
        $dataAtual = date('d/m/Y');
        return view('/admin/financeiro/geral', ['dataAtual' => $dataAtual, 'cats' => $cats, 'tipos' => $tipos, 
         'entradas' => $entradas, 'saidas' => $saidas, 'entradasMes' => $entradasMes,'saidasMes' => $saidasMes,
        'entradasAno' => $entradasAno, 'saidasAno' => $saidasAno, 'mesesE' => $mesesE, 'mesesS' => $mesesS,
        'catsEnt' => $catsEnt,'ents' => $ents, 'catsSaida' => $catsSaida, 'saldosMeses' => $saldosMeses]);
    }


    public function searchFinanceiro(Request $request) {

        $resultado = request('resultado');
        $categoria = request('cat');
        $tipo = request('tipo');
        $mes = request('mes');
        $ano = request('ano');
        $users = User::all();
        $ents = Financeiro_transacao::orderBy('data_cad')->get();
        $cats = Financeiro_cat::orderBy("nome")->get();
        $tipos = Financeiro_tipo::all();
        $meses_abv = [1 => 'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        $selectFinanceiros = [1 => 'Entrada', 'Saída'];

        if($request->resultado == 1) {

            //entrada: categoria
            if(isset($request->cat) && empty($request->tipo) && empty($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get();
            } 

            //entrada: tipo
            elseif(empty($request->cat) && isset($request->tipo) && empty($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_tipo', '=', $request->tipo)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //entrada: mes
            elseif(empty($request->cat) && empty($request->tipo) && isset($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::whereMonth('data_cad', '=', $request->mes)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //entrada: ano
            elseif(empty($request->cat) && empty($request->tipo) && empty($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }
            
            //entrada: categoria e tipo
            elseif(isset($request->cat) && isset($request->tipo) && empty($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->where('id_tipo', '=', $request->tipo)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //entrada: categoria e mes
            elseif(isset($request->cat) && empty($request->tipo) && isset($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->whereMonth('data_cad', '=', $request->mes)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //entrada: categoria e ano
            elseif(isset($request->cat) && empty($request->tipo) && empty($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //entrada: tipo e mes
            elseif(empty($request->cat) && isset($request->tipo) && isset($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_tipo', '=', $request->tipo)
                ->whereMonth('data_cad', '=', $request->mes)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //entrada: tipo e ano
            elseif(empty($request->cat) && isset($request->tipo) && empty($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_tipo', '=', $request->tipo)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //entrada: mes e ano
            elseif(empty($request->cat) && empty($request->tipo) && isset($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::whereMonth('data_cad', '=', $request->mes)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //entrada: categoria, tipo e mes
            elseif(isset($request->cat) && isset($request->tipo) && isset($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->where('id_tipo', '=', $request->tipo)
                ->whereMonth('data_cad', '=', $request->mes)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //entrada: categoria, tipo e ano
            elseif(isset($request->cat) && isset($request->tipo) && empty($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->where('id_tipo', '=', $request->tipo)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            }

            //entrada: categoria, mes e ano
            elseif(isset($request->cat) && empty($request->tipo) && isset($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->whereMonth('data_cad', '=', $request->mes)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            } 

            //entrada: tipo, mes e ano
            elseif(empty($request->cat) && isset($request->tipo) && isset($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_tipo', '=', $request->tipo)
                ->whereMonth('data_cad', '=', $request->mes)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            } 

            //entrada: tudão
            elseif(isset($request->cat) && isset($request->tipo) && isset($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->where('id_tipo', '=', $request->tipo)
                ->whereMonth('data_cad', '=', $request->mes)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get();

            //entrada: nadão
            } else {
                $financeiros = Financeiro_transacao::where('id_financeiro', '=', 1)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')->get();
            }
        } 
        elseif ($request->resultado == 2) {

            //saida: categoria
            if(isset($request->cat) && empty($request->tipo) && empty($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get();
            } 

            //saida: tipo
            elseif(empty($request->cat) && isset($request->tipo) && empty($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_tipo', '=', $request->tipo)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //saida: mes
            elseif(empty($request->cat) && empty($request->tipo) && isset($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::whereMonth('data_cad', '=', $request->mes)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //saida: ano
            elseif(empty($request->cat) && empty($request->tipo) && empty($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }
            
            //saida: categoria e tipo
            elseif(isset($request->cat) && isset($request->tipo) && empty($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->where('id_tipo', '=', $request->tipo)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //saida: categoria e mes
            elseif(isset($request->cat) && empty($request->tipo) && isset($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->whereMonth('data_cad', '=', $request->mes)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //saida: categoria e ano
            elseif(isset($request->cat) && empty($request->tipo) && empty($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //saida: tipo e mes
            elseif(empty($request->cat) && isset($request->tipo) && isset($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_tipo', '=', $request->tipo)
                ->whereMonth('data_cad', '=', $request->mes)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //saida: tipo e ano
            elseif(empty($request->cat) && isset($request->tipo) && empty($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_tipo', '=', $request->tipo)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //saida: mes e ano
            elseif(empty($request->cat) && empty($request->tipo) && isset($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::whereMonth('data_cad', '=', $request->mes)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //saida: categoria, tipo e mes
            elseif(isset($request->cat) && isset($request->tipo) && isset($request->mes) && empty($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->where('id_tipo', '=', $request->tipo)
                ->whereMonth('data_cad', '=', $request->mes)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            }

            //saida: categoria, tipo e ano
            elseif(isset($request->cat) && isset($request->tipo) && empty($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->where('id_tipo', '=', $request->tipo)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            }

            //saida: categoria, mes e ano
            elseif(isset($request->cat) && empty($request->tipo) && isset($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->whereMonth('data_cad', '=', $request->mes)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            } 

            //saida: tipo, mes e ano
            elseif(empty($request->cat) && isset($request->tipo) && isset($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_tipo', '=', $request->tipo)
                ->whereMonth('data_cad', '=', $request->mes)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')
                ->get(); 
            
            } 

            //saida: tudão
            elseif(isset($request->cat) && isset($request->tipo) && isset($request->mes) && isset($request->ano)) {
                $financeiros = Financeiro_transacao::where('id_cat', '=', $request->cat)
                ->where('id_tipo', '=', $request->tipo)
                ->whereMonth('data_cad', '=', $request->mes)
                ->whereYear('data_cad', '=', $request->ano)
                ->where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')->get();

            //saida: nadão
            } else {
                $financeiros = Financeiro_transacao::where('id_financeiro', '=', 2)
                ->where('situacao', '=', 1)
                ->orderByDesc('data_cad')->get();
            }
        } else {
            $financeiros = Financeiro_transacao::orderByDesc('data_cad')->get();
        }

        return view('/admin/financeiro/filtro',['selectFinanceiros' => $selectFinanceiros, 'cats' => $cats, 
        'tipos' => $tipos, 'meses_abv' => $meses_abv, 'resultado' => $resultado, 'financeiros' => $financeiros,
        'categoria' => $categoria, 'tipo' => $tipo, 'mes' => $mes, 'ano' => $ano, 'users' => $users]);
    }

    

    public function indexFinanceiroEntrada() {
        $cats = Financeiro_cat::orderBy("nome")->get();
        $tipos = Financeiro_tipo::all();
        $dataAtual = date('d/m/Y');
        return view('/admin/financeiro/entrada', ['dataAtual' => $dataAtual, 'cats' => $cats, 'tipos' => $tipos]);
    }

    public function storeFinanceiroEntrada(Request $request) {
        $tipos = Financeiro_tipo::all();
        $cats = Financeiro_cat::all();
        $this->validate($request, [
            'valor' => ['required'],
            'descricao' => ['required', 'max: 500'],
            'data_cad' => ['required'],
            'id_financeiro' => ['integer', 'min:1', 'max:1'],
            'user_id' => ['integer', 'min:'.auth()->user()->id, 'max'.auth()->user()->id],
            'situacao' => ['integer', 'min:1', 'max:1'],
            'id_tipo' => ['required', 'integer', 'min: 1', 'max:'.$tipos->count()],
            'id_cat' => ['required', 'integer', 'min: 1', 'max:'.$cats->count()], 

        ], [
            'valor.required' => 'O valor é obrigatório',
            'descricao.required' => 'A descrição é obrigatória',
            'descricao.max' => 'A descrição aceita, no máximo, 500 caracteres',
            'data_cad.required' => 'O data é obrigatória',
            'id_tipo.required' => 'Tipo é obrigatório',
            'id_financeiro.integer' => 'O tipo financeiro sempre será Entrada',
            'id_financeiro.min' => 'O tipo financeiro sempre será Entrada',
            'id_financeiro.max' => 'O tipo financeiro sempre será Entrada',
            'id_tipo.integer' => 'Tipo escolhido não existe',
            'id_tipo.min' => 'Tipo escolhido não existe',
            'id_tipo.max' => 'Tipo escolhido não existe',
            'user_id.integer' => 'O Usuário não pode ser alterado.',
            'user_id.min' => 'O Usuário não pode ser alterado.',
            'user_id.max' => 'O Usuário não pode ser alterado.',
            'situacao.integer' => 'Situação só pode ser ativa',
            'situacao.min' => 'Situação só pode ser ativa',
            'situacao.max' => 'Situação só pode ser ativa',
            'id_cat.required' => 'A categoria é obrigatória',
            'id_cat.integer' => 'A categoria escolhida não existe',
            'id_cat.min' => 'A categoria escolhida não existe',
            'id_cat.max' => 'A categoria escolhida não existe',
        ]);

        $dataAtual = date('d/m/Y');
        $entrada = new Financeiro_transacao;
        $entrada -> valor = $request->valor;
        $entrada -> descricao = $request->descricao;
        $entrada -> data_cad = $request->data_cad;
        $entrada -> id_tipo = $request->id_tipo;
        $entrada -> id_cat = $request->id_cat;
        $entrada -> id_financeiro = 1;
        $entrada -> situacao = 1;
        $entrada -> user_id = auth()->user()->id;
        $entrada -> save();
        return redirect('/admin/financeiro/entrada')->with('msg', 'Entrada cadastrada com sucesso');
    }



    public function indexFinanceiroSaida() {
        $cats = Financeiro_cat::orderBy("nome")->get();
        $tipos = Financeiro_tipo::all();
        $dataAtual = date('d/m/Y');
        return view('/admin/financeiro/saida', ['dataAtual' => $dataAtual, 'cats' => $cats, 'tipos' => $tipos]);
    }

    public function storeFinanceiroSaida(Request $request) {
        $tipos = Financeiro_tipo::all();
        $cats = Financeiro_cat::all();
        $this->validate($request, [
            'valor' => ['required'],
            'descricao' => ['required', 'max: 500'],
            'data_cad' => ['required'],
            'id_financeiro' => ['integer', 'min: 2', 'max:2'],
            'user_id' => ['integer', 'min:'.auth()->user()->id, 'max'.auth()->user()->id],
            'situacao' => ['integer', 'min:1', 'max:1'],
            'id_tipo' => ['required', 'integer', 'min: 1', 'max:'.$tipos->count()],
            'id_cat' => ['required', 'integer', 'min: 1', 'max:'.$cats->count()], 

        ], [
            'valor.required' => 'O valor é obrigatório',
            'descricao.required' => 'A descrição é obrigatória',
            'descricao.max' => 'A descrição aceita, no máximo, 500 caracteres',
            'data_cad.required' => 'O data é obrigatória',
            'id_financeiro.integer' => 'O tipo financeiro sempre será Saída',
            'id_financeiro.min' => 'O tipo financeiro sempre será Saída',
            'id_financeiro.max' => 'O tipo financeiro sempre será Saída',
            'id_tipo.required' => 'Tipo é obrigatório',
            'id_tipo.integer' => 'Tipo escolhido não existe',
            'id_tipo.min' => 'Tipo escolhido não existe',
            'id_tipo.max' => 'Tipo escolhido não existe',
            'user_id.integer' => 'O Usuário não pode ser alterado.',
            'user_id.min' => 'O Usuário não pode ser alterado.',
            'user_id.max' => 'O Usuário não pode ser alterado.',
            'situacao.integer' => 'Situação só pode ser ativa',
            'situacao.min' => 'Situação só pode ser ativa',
            'situacao.max' => 'Situação só pode ser ativa',
            'id_cat.required' => 'A categoria é obrigatória',
            'id_cat.integer' => 'A categoria escolhida não existe',
            'id_cat.min' => 'A categoria escolhida não existe',
            'id_cat.max' => 'A categoria escolhida não existe',
        ]);

        $dataAtual = date('d/m/Y');
        $saida = new Financeiro_transacao;
        $saida -> valor = $request->valor;
        $saida -> descricao = $request->descricao;
        $saida -> data_cad = $request->data_cad;
        $saida -> id_tipo = $request->id_tipo;
        $saida -> id_cat = $request->id_cat;
        $saida -> id_financeiro = 2;
        $saida -> situacao = 1;
        $saida -> user_id = auth()->user()->id;
        $saida -> save();
        return redirect('/admin/financeiro/saida')->with('msg', 'Saída cadastrada com sucesso');
    }

    public function editFinanceiroTransacao($id) {
        $financeiro = Financeiro_transacao::findOrFail($id);
        $cats = Financeiro_cat::orderBy('nome')->get();
        $tipos = Financeiro_tipo::all();
        if($financeiro->user_id != auth()->user()->id) {
            return redirect('/admin/financeiro/filtro')->with('msg2', 'Seu usuário não tem permissão para editar essa entrada/saída.');
        }elseif($financeiro->user_id == auth()->user()->id && $financeiro->created_at != $financeiro->updated_at) {
            return redirect('/admin/financeiro/filtro')->with('msg2', 'Essa entrada/saída já foi editada uma vez, não permitindo mais edições');
        }else {
            return view('/admin/financeiro/editar', ['financeiro' => $financeiro, 'cats' => $cats, 'tipos' => $tipos]);
        }

    }

    public function updateFinanceiroTransacao(Request $request) {
        $cats = Financeiro_cat::orderBy('nome')->get();
        $tipos = Financeiro_tipo::all();
        $financeiro = Financeiro_transacao::findOrFail($request -> id);
        $this->validate($request, [
            'valor' => ['required'],
            'descricao' => ['required', 'max: 500'],
            'data_cad' => ['required'],
            'user_id' => ['integer', 'min:'.$financeiro->user_id, 'max'.$financeiro->user_id],
            'id_financeiro' => ['integer', 'min:'.$financeiro->id_financeiro, 'max:'.$financeiro->id_financeiro],
            'id_tipo' => ['required', 'integer', 'min: 1', 'max:'.$tipos->count()],
            'id_cat' => ['required', 'integer', 'min: 1', 'max:'.$cats->count()], 

        ], [
            'valor.required' => 'O valor é obrigatório',
            'descricao.required' => 'A descrição é obrigatória',
            'descricao.max' => 'A descrição aceita, no máximo, 500 caracteres',
            'id_financeiro.integer' => 'O financeiro não pode ser alterado.',
            'id_financeiro.min' => 'O financeiro não pode ser alterado.',
            'id_financeiro.max' => 'O financeiro não pode ser alterado.',
            'user_id.integer' => 'O Usuário não pode ser alterado.',
            'user_id.min' => 'O Usuário não pode ser alterado.',
            'user_id.max' => 'O Usuário não pode ser alterado.',
            'data_cad.required' => 'O data é obrigatória',
            'id_tipo.required' => 'Tipo é obrigatório',
            'id_tipo.integer' => 'Tipo escolhido não existe',
            'id_tipo.min' => 'Tipo escolhido não existe',
            'id_tipo.max' => 'Tipo escolhido não existe',
            'id_cat.required' => 'A categoria é obrigatória',
            'id_cat.integer' => 'A categoria escolhida não existe',
            'id_cat.min' => 'A categoria escolhida não existe',
            'id_cat.max' => 'A categoria escolhida não existe',
        ]);

        Financeiro_transacao::findOrFail($request -> id)->update($request->all());
        return redirect('/admin/financeiro/filtro')->with('msg', 'Transação foi atualizada com sucesso');

        

    }

    public function indexAviso() {
        $destinatarios = Sala::orderBy("nome")->get();
        $dataAtual = date('d/m/Y');
        $importancias = [1 => 'Alta', 'Média', 'Baixa'];
        return view('/admin/cadastro/aviso', ['dataAtual' => $dataAtual, 'destinatarios' => $destinatarios,
         'importancias' => $importancias]);
    }

    public function storeAviso(Request $request) {
        $this->validate($request, [
            'titulo' => 'required',
            'descricao' => ['required', 'max: 500'],
            'data_post' => 'required',
            'destinatario' => ['required'],
            'importancia' => ['required','integer', 'min:1', 'max:3']
        ], [
            'titulo.required' => 'O título é obrigatório',
            'descricao.required' => 'A descrição é obrigatória',
            'descricao.max' => 'A descrição só pode conter até, no máximo, 500 caracteres',
            'data_post.required' => 'A data de postagem é obrigatória',
            'destinatario.required' => 'O destinatário é obrigatório',
            'importancia.required' => 'A importância é obrigatória',
            'importancia.integer' => 'Essa importância não existe',
            'importancia.min' => 'Essa importância não existe',
            'importancia.max' => 'Essa importância não existe',
        ]);

        $aviso = new Aviso;
        $aviso -> titulo = $request->titulo;
        $aviso -> descricao = $request->descricao;
        $aviso -> data_post = $request->data_post;
        $aviso -> destinatario = $request->destinatario;
        $aviso -> importancia = $request->importancia;
        $aviso -> save();

        return redirect('/admin/cadastro/aviso')->with('msg', 'O aviso foi enviado com sucesso!');
    }

    public function searchAviso(Request $request) {
        $destEnv = request('destinatario');
        $importancia = request('importancia');
        $destinatarios = Sala::orderBy('nome')->get();
        $importancias = [1 => 'Alta', 'Média', 'Baixa'];
        if(isset($request -> destinatario) && empty($request -> importancia)) {
            $avisos = Aviso::where('destinatario', '=', $request->destinatario)->orderBy('data_post',  'DESC')->get();

        } elseif(empty($request -> destinatario) && isset($request -> importancia)) {
            $avisos = Aviso::where('importancia', '=', $request->importancia)->orderBy('data_post',  'DESC')->get();

        }
        elseif(isset($request -> destinatario) && isset($request -> importancia)) {
            $avisos = Aviso::where('destinatario', '=', $request->destinatario)
            ->where('importancia', '=', $request->importancia)->orderBy('data_post',  'DESC')->get();
            
        } else {
            $avisos = Aviso::orderBy('data_post',  'DESC')->get();
        }
        
        return view('/admin/filtro/aviso', ['destinatarios' => $destinatarios, 'importancias' => $importancias,
        'avisos' => $avisos, 'destEnv' => $destEnv, 'importancia' => $importancia]);

    }

    public function editAviso($id) {
        $aviso = Aviso::findOrFail($id);
        $destinatarios = Sala::orderBy('nome')->get();
        $importancias = [1 => 'Alta', 'Média', 'Baixa'];
        return view('/admin/edit/aviso', ['aviso' => $aviso, 'destinatarios' => $destinatarios, 'importancias' => $importancias]);

    }


    public function updateAviso(Request $request){
        $this->validate($request, [
            'titulo' => 'required',
            'descricao' => ['required', 'max: 500'],
            'data_post' => 'required',
            'destinatario' => ['required'],
            'importancia' => ['required','integer', 'min:1', 'max:3']
        ], [
            'titulo.required' => 'O título é obrigatório',
            'descricao.required' => 'A descrição é obrigatória',
            'descricao.max' => 'A descrição só pode conter até, no máximo, 500 caracteres',
            'data_post.required' => 'A data de postagem é obrigatória',
            'destinatario.required' => 'O destinatário é obrigatório',
            'importancia.required' => 'A importância é obrigatória',
            'importancia.integer' => 'Essa importância não existe',
            'importancia.min' => 'Essa importância não existe',
            'importancia.max' => 'Essa importância não existe',
        ]);
        Aviso::findOrFail($request -> id)->update($request->all());
        return redirect('/admin/filtro/aviso')->with('msg', 'O aviso foi atualizado com sucesso');
    }

    public function destroyAviso($id){
        Aviso::findOrFail($id)->delete();
        return redirect('/admin/filtro/aviso')->with('msg', 'Aviso excluído com sucesso');

    }

    public function showPessoa($id) {
        $pessoa = Pessoa::findOrFail($id);
        $salas = Sala::where('id', '>', 2)->orderBy('nome')->get();
        $ufs = Uf::orderBy("nome")->get();
        $publicos = Publico::all();
        $formations = Formation::all();
        return view('/admin/visualizar/pessoa', ['pessoa' => $pessoa, 'ufs' => $ufs, 'salas' => $salas, 'publicos' => $publicos, 'formations' => $formations]);
    }

    public function showFinanceiroTransacao($id) {
        $users = User::all();
        $financeiro = Financeiro_transacao::findOrFail($id);
        $cats = Financeiro_cat::orderBy('nome')->get();
        $tipos = Financeiro_tipo::all();
        return view('/admin/financeiro/visualizar', ['users' => $users, 'financeiro' => $financeiro, 'cats' => $cats, 'tipos' => $tipos]);
    }

    public function searchChamadas(Request $request) {

        $classe = request('classe');
        $mes = request('mes');
        $ano = request('ano');
        $salas = Sala::where('id', '>', 2)->orderBy('nome')->get();
        $meses_abv = [1 => 'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

        //classe
        if(isset($request -> classe) && empty($request -> mes) && empty($request -> ano)) {
            $chamadas = Chamada::where('id_sala', '=', $request -> classe)
            ->orderBy('created_at', 'DESC')
            ->get();
        } 
        //mes
        elseif(empty($request -> classe) && isset($request -> mes) && empty($request -> ano))  {
            $chamadas = Chamada::whereMonth('created_at', '=', $request -> mes)
            ->orderBy('created_at', 'DESC')
            ->get();
        }
        //ano
        elseif(empty($request -> classe) && empty($request -> mes) && isset($request -> ano))  {
            $chamadas = Chamada::whereYear('created_at', '=', $request -> ano)
            ->orderBy('created_at', 'DESC')
            ->get();
        }
        //classe e mes
        elseif(isset($request -> classe) && isset($request -> mes) && empty($request -> ano))  {
            $chamadas = Chamada::where('id_sala', '=', $request -> classe)
            ->whereMonth('created_at', '=', $request -> mes)
            ->orderBy('created_at', 'DESC')
            ->get();
        }
        //classe e ano
        elseif(isset($request -> classe) && empty($request -> mes) && isset($request -> ano))  {
            $chamadas = Chamada::where('id_sala', '=', $request -> classe)
            ->whereYear('created_at', '=', $request -> ano)
            ->orderBy('created_at', 'DESC')
            ->get();
        }
        //mes e ano
        elseif(empty($request -> classe) && isset($request -> mes) && isset($request -> ano))  {
            $chamadas = Chamada::whereMonth('created_at', '=', $request -> mes)
            ->whereYear('created_at', '=', $request -> ano)
            ->orderBy('created_at', 'DESC')
            ->get();
        }
        //classe, mes e ano
        elseif(isset($request -> classe) && isset($request -> mes) && isset($request -> ano))  {
            $chamadas = Chamada::where('id_sala', '=', $request -> classe)
            ->whereMonth('created_at', '=', $request -> mes)
            ->whereYear('created_at', '=', $request -> ano)
            ->orderBy('created_at', 'DESC')
            ->get();

        } else {
            $chamadas = Chamada::whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'DESC')
            ->get();

        }

        return view('/admin/chamadas', ['chamadas' => $chamadas, 'salas' => $salas, 'meses_abv' => $meses_abv, 
        'classe' => $classe, 'mes' => $mes, 'ano' => $ano]);

    }

    public function showChamada($id) {
        $chamada = Chamada::findOrFail($id);
        $salas = Sala::where('id', '>', 2)->get();
        return view('/admin/visualizar/chamada', ['chamada' => $chamada, 'salas' => $salas]);
    }

 
    public function indexRelatorioToday() {
        $relatorioToday = Relatorio::whereDate('created_at', Carbon::today())->get();
        $chamadas = Chamada::whereDate('created_at',  Carbon::today())->get();
        $salas = Sala::where('id', '>', 2)->get();
        return view ('/admin/relatorios/cadastro', ['chamadas' => $chamadas, 'salas' => $salas, 'relatorioToday' => $relatorioToday]);

    }

    public function storeRelatorioToday() {
        $relatorioToday = Relatorio::whereDate('created_at', Carbon::today())->get();
        if($relatorioToday -> count() == 1) {
            return redirect('/admin/relatorios/todos')->with('msg2', 'O relatório de hoje já foi cadastrado.');
        }
        $chamadas = Chamada::select('chamadas.id', 'chamadas.created_at', 'salas.nome', 'matriculados', 'presentes', 'assist_total', 'visitantes', 'biblias', 'revistas')
        ->whereDate('chamadas.created_at', Carbon::today())
        ->join('salas', 'chamadas.id_sala', '=', 'salas.id')
        ->get();

        $relatorio = new Relatorio;
        $relatorio -> salas = $chamadas;
        $relatorio -> matriculados = $chamadas -> sum('matriculados');
        $relatorio -> presentes = $chamadas -> sum('presentes');
        $relatorio -> visitantes = $chamadas -> sum('visitantes');
        $relatorio -> assist_total = $chamadas -> sum('assist_total');
        $relatorio -> biblias = $chamadas -> sum('biblias');
        $relatorio -> revistas = $chamadas -> sum('revistas');
        $relatorio -> save();
        return redirect('/admin/relatorios/todos')->with('msg', 'Relatório do dia cadastrado com sucesso!');

    }

    public function searchRelatorios(Request $request) {
        $mes = request('mes');
        $ano = request('ano');
        $salas = Sala::where('id', '>', 2)->orderBy('nome')->get();
        $meses_abv = [1 => 'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

        //mes
        if(isset($request -> mes) && empty($request -> ano)) {
            $relatorios = Relatorio::whereMonth('created_at', '=', $request -> mes)->orderBy('created_at', 'DESC')->get();
        }
        //ano
        elseif(empty($request -> mes) && isset($request -> ano)) {
            $relatorios = Relatorio::whereYear('created_at', '=', $request -> ano) ->orderBy('created_at', 'DESC')->get();
        }
        //mes e ano
        elseif(isset($request -> mes) && isset($request -> ano)) {
            $relatorios = Relatorio::whereMonth('created_at', '=', $request -> mes)
            ->whereYear('created_at', '=', $request -> ano) ->orderBy('created_at', 'DESC')->get();
        //nada
        } else {
            $relatorios = Relatorio::whereDate('created_at', '=', Carbon::today())->orderBy('created_at', 'DESC')->get();
        }
       
        return view('/admin/relatorios/todos', ['relatorios' => $relatorios, 'meses_abv' => $meses_abv, 'mes' => $mes, 'ano' => $ano]);

    }

    public function showRelatorio($id) {
        $relatorio = Relatorio::findOrFail($id);
        $salas = Sala::where('id', '>', 2)->get();
        return view('/admin/visualizar/relatorio', ['relatorio' => $relatorio, 'salas' => $salas]);
    }

    public function searchAniversariantes(Request $request) {
        $mes = request('mes');
        $classe = request('classe');
        $salas = Sala::where('id', '>', 2)->get();
        $meses_abv = [1 => 'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        //mes
        if(isset($request -> mes) && empty($request -> classe)) {
            $pessoas = Pessoa::whereMonth('data_nasc', '=', $request->mes)->get(); 

        }
        //classe
        elseif(empty($request -> mes) && isset($request -> classe)) {
            $pessoas = Pessoa::whereJsonContains('id_sala', $request->classe)->get(); 

        }
        //classe e mes
        elseif(isset($request -> mes) && isset($request -> classe)) {
            $pessoas = Pessoa::whereMonth('data_nasc', '=', $request->mes)
            ->whereJsonContains('id_sala', $request->classe)->get();

        } else {
            $pessoas = Pessoa::whereMonth('data_nasc', '=', Carbon::now())->get(); 
        }

        return view('/admin/aniversariantes', ['pessoas' => $pessoas, 'salas' => $salas,
        'meses_abv' => $meses_abv, 'mes' => $mes, 'classe' => $classe]);
    }
    
    public function sobre() {
        return view('/admin/sobre');
    }

    public function generatePdfToRelatorios($id) {

        $relatorio = Relatorio::findOrFail($id);
        $classes = Sala::select('id', 'nome')->get();
      
        return \PDF::loadView('/admin/visualizar/pdf-relatorio', compact(['relatorio', 'classes']))
        ->setPaper('a4', 'landscape')
        ->stream('relatorio.pdf');
    }

    public function generatePdfToChamadas($id) {

        $chamada = Chamada::select('chamadas.*', 'salas.nome')->join('salas', 'chamadas.id_sala', '=', 'salas.id')->findOrFail($id);

        return \PDF::loadView('/admin/visualizar/pdf-chamada', compact(['chamada']))
        ->stream('frequencia.pdf');
    }
}
