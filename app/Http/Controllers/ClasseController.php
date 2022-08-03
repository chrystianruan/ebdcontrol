<?php

namespace App\Http\Controllers;

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

class ClasseController extends Controller
{
   public function indexClasse() {
    $dataMes = date('n');
    $dataAno = date('Y');
    $nivel = auth()->user()->id_nivel;
    $idadesPessoas = DB::table('pessoas')
    ->select(DB::raw('count(id) as qtd, floor( (unix_timestamp(current_timestamp()) - unix_timestamp(pessoas.data_nasc)) / (60 * 60 * 24) /365.25) as idades'))
    ->whereJsonContains('id_sala', ''.$nivel)
    ->groupBy(DB::raw('floor( (unix_timestamp(current_timestamp()) - unix_timestamp(pessoas.data_nasc)) / (60 * 60 * 24) /365.25);'))
    ->get();
    $formacoes = Pessoa::select(DB::raw('pessoas.id_formation, count(pessoas.id) as qtdPessoas, formations.nome'))
    ->leftJoin('formations', 'pessoas.id_formation', '=', 'formations.id')
    ->whereJsonContains('id_sala', ''.$nivel)
    ->groupBy('id_formation')
    ->get();
    $funcoes = DB::table('pessoas')
    ->select(DB::raw('id_funcao, count(pessoas.id) as qtd, funcaos.nome'))
    ->leftJoin('funcaos', 'pessoas.id_funcao', '=', 'funcaos.id')
    ->whereJsonContains('id_sala', ''.$nivel)
    ->groupBy('id_funcao')
    ->get();
    $interesseProf = Pessoa::whereJsonContains('id_sala', ''.$nivel)->where('interesse', '<>', 2)->where('id_funcao', '<>', 2)->count();
    $chamadaDia = Chamada::where('id_sala', '=', $nivel)->whereDate('created_at', Carbon::today())->get();
    $chamadasMes = Chamada::where('id_sala', '=', $nivel)->whereMonth('created_at', Carbon::now())->get();
    $chamadasAno = Chamada::where('id_sala', '=', $nivel)->whereYear('created_at', Carbon::now())->get();
    $niverMes = Pessoa::whereJsonContains('id_sala', ''.$nivel)->whereMonth('data_nasc', '=', $dataMes)->count();
    $alunosInativos = Pessoa::whereJsonContains('id_sala', ''.$nivel)->where('situacao', '=', 2)->count();

    return view('/classe/dashboard', ['niverMes' => $niverMes, 'alunosInativos' => $alunosInativos,
     'chamadaDia' => $chamadaDia, 'interesseProf' => $interesseProf, 'idadesPessoas' => $idadesPessoas, 'formacoes' => $formacoes,
    'chamadasMes' => $chamadasMes, 'chamadasAno' => $chamadasAno, 'funcoes' => $funcoes]);
   }

   public function indexCadastroClasse() {
        $check = request('scales');
        $salas = Sala::where('id', '>', 2)->orderBy('nome')->get();
        $ufs = Uf::orderBy("nome")->get();
        $publicos = Publico::all();
        $formations = Formation::all();
      return view('/classe/cadastro-pessoa', ['salas' => $salas, 'ufs' => $ufs, 'publicos' => $publicos,
      'formations' => $formations, 'check' => $check]);
  }

  public function storePessoaClasse(Request $request) {
        $nivelUser = auth()->user()->id_nivel;
        $publicos = Publico::all();
        $ufs = Uf::all();
        $formations = Formation::all();
        $this->validate($request, [
            'nome' => ['required'],
            'sexo' => ['required', 'integer', 'min: 1', 'max: 2'],
            'data_nasc' => ['required'],
            'id_uf' => ['required', 'integer', 'min: 1', 'max:'.$ufs->count()],
            'telefone' => ['max: 11'],
            'id_formation' => ['required', 'integer', 'min: 1', 'max:'.$formations->count()],
            'id_sala' => ['max: 1'],
            'id_sala.*' => ['integer', 'min:'.$nivelUser, 'max:'.$nivelUser],
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

            'telefone.max' =>  'O telefone precisa de 11 dígitos: DDD + número',

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
        $pessoa-> id_sala = ["$nivelUser"];
        $pessoa-> id_funcao = 1;
        $pessoa-> situacao = 1;
        $pessoa-> interesse = $request->interesse;
        $pessoa-> frequencia_ebd = $request->frequencia_ebd;
        $pessoa-> curso_teo = $request->curso_teo;
        $pessoa-> prof_ebd = $request->prof_ebd;
        $pessoa-> prof_comum = $request->prof_comum;
        $pessoa-> id_public = $request->id_public;
        $pessoa -> save();
        return redirect('/classe/cadastro-pessoa')->with('msg', 'Aluno cadastrado com sucesso');

  }

  public function searchPessoaClasse(Request $request) {
    $nivelUser = auth()->user()->id_nivel;
    $nome = request('nome');
    $sexo = request('sexo');
    $id_funcao = request('id_funcao');
    $situacao = request('situacao');

    //nome
    if(isset($request->nome) && empty($request->sexo) && empty($request->id_funcao)  && empty($request->situacao)){
        $pessoas = Pessoa::where([['nome', 'like', '%'.$request -> nome.'%']])
        ->whereJsonContains('id_sala', ''.$nivelUser)->orderBy('nome')
        ->get();
    }
    //sexo
    elseif(empty($request->nome) && isset($request->sexo) && empty($request->id_funcao)  && empty($request->situacao)) {
        $pessoas = Pessoa::where('sexo', '=', $request -> sexo)
        ->whereJsonContains('id_sala', ''.$nivelUser)->orderBy('nome')
        ->get();
    }
    //id_funcao
    elseif(empty($request->nome) && empty($request->sexo) && isset($request->id_funcao)  && empty($request->situacao)) {
        $pessoas = Pessoa::where('id_funcao', '=', $request -> id_funcao)
        ->whereJsonContains('id_sala', ''.$nivelUser)->orderBy('nome')
        ->get();
    }
    //situacao
    elseif(empty($request->nome) && empty($request->sexo) && empty($request->id_funcao)  &&  isset($request->situacao)) {
        $pessoas = Pessoa::where('situacao', '=', $request -> situacao)
        ->whereJsonContains('id_sala', ''.$nivelUser)->orderBy('nome')
        ->get();
    }
    //sexo e id_funcao
    elseif(empty($request->nome) && isset($request->sexo) && isset($request->id_funcao)  &&  empty($request->situacao)) {
        $pessoas = Pessoa::where('sexo', '=', $request -> sexo)
        ->where('id_funcao', '=', $request -> id_funcao)
        ->whereJsonContains('id_sala', ''.$nivelUser)->orderBy('nome')
        ->get();
    }
    //sexo e situacao
    elseif(empty($request->nome) && isset($request->sexo) && empty($request->id_funcao)  && isset($request->situacao)) {
        $pessoas = Pessoa::where('sexo', '=', $request -> sexo)
        ->where('situacao', '=', $request -> situacao)
        ->whereJsonContains('id_sala', ''.$nivelUser)->orderBy('nome')
        ->get();
    }
    //id_funcao e situacao
    elseif(empty($request->nome) && empty($request->sexo) && isset($request->id_funcao)  && isset($request->situacao)) {
        $pessoas = Pessoa::where('id_funcao', '=', $request -> id_funcao)
        ->where('situacao', '=', $request -> situacao)
        ->whereJsonContains('id_sala', ''.$nivelUser)->orderBy('nome')
        ->get();
    }
    //sexo, id_funcao e situacao
    elseif(empty($request->nome) && isset($request->sexo) && isset($request->id_funcao)  && isset($request->situacao)) {
        $pessoas = Pessoa::where('sexo', '=', $request -> sexo)
        ->where('id_funcao', '=', $request -> id_funcao)
        ->where('situacao', '=', $request -> situacao)
        ->whereJsonContains('id_sala', ''.$nivelUser)->orderBy('nome')
        ->get();
        
    } else {
         $pessoas = Pessoa::whereJsonContains('id_sala', ''.$nivelUser)->orderBy('nome')
         ->get();

    }

    return view('/classe/pessoas', ['pessoas' => $pessoas, 'nome' => $nome, 'sexo' => $sexo, 
    'id_funcao' => $id_funcao, 'situacao' => $situacao]);
  }

  public function showPessoaClasse($id) {
    $nivelUser = auth()->user()->id_nivel;
    $pessoa = Pessoa::findOrFail($id);
    
    if(in_array("$nivelUser", $pessoa->id_sala)) {
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

  public function indexChamadaClasse() {
      $sala = auth()->user()->id_nivel;
      $chamadas = Chamada::where('id_sala', '=', $sala)->whereDate('created_at', Carbon::today())->get();
      $pessoas = DB::table('pessoas')
      ->select('nome', 'data_nasc', 'id_funcao')
      ->whereJsonContains('id_sala', ''.$sala)
      ->where('situacao', '=', 1)
      ->orderBy('nome')->get();
      $salas = Sala::where('id', '>', 2)->get();
      return view('/classe/chamada-dia', ['chamadas' => $chamadas, 'salas' => $salas, 'pessoas' => $pessoas]);
   }

   public function storeChamadaClasse(Request $request) {

      $sala = auth()->user()->id_nivel;
      $chamadas = Chamada::where('id_sala', '=', $sala)->whereDate('created_at', Carbon::today())->get();
             
      if($chamadas->count() == 1) {
        return redirect('/classe/chamada-dia')->with('msg', 'A chamada não pode ser realizada.');
    }
    $pessoas = DB::table('pessoas')
    ->select('nome', 'data_nasc', 'id_funcao')
    ->whereJsonContains('id_sala', ''.$sala)
    ->where('situacao', '=', 1)
    ->orderBy('nome')->get();


    $this->validate($request, [
        'id_sala' => ['integer','min'.$sala, 'max'.$sala],
        'matriculados' => ['required', 'integer', 'min:'.$pessoas->count(), 'max:'.$pessoas->count()],
        'presentes' => ['required', 'integer', 'min:0', 'max:'.$pessoas->count()],
        'visitantes' => ['required', 'integer', 'min:0'],
        'assist_total' => ['required', 'integer', 'min:'.$request->presentes + $request->visitantes, 'max:'.$request->presentes + $request->visitantes],
        'biblias' => ['required', 'integer', 'min:0'],
        'revistas' => ['required', 'integer', 'min:0'],
        'observacoes' => ['max: 800']  
    ], [

        'id_sala.integer' => 'Classe escolhida inexistente',
        'id_sala.min' => 'Classe escolhida inexistente',
        'id_sala.max' => 'Classe escolhida inexistente',

        'matriculados.required' => 'O n° de matriculados é obrigatório',
        'matriculados.integer' => 'O n° de matriculados é inválido',
        'matriculados.min' => 'O n° de matriculados é inválido',
        'matriculados.max' => 'O n° de matriculados é inválido',

        'presentes.required' => 'O n° de presentes é obrigatório',
        'presentes.integer' => 'O n° de presentes é inválido',
        'presentes.min' => 'O n° de presentes é inválido',
        'presentes.max' => 'O n° de presentes não pode ser maior que o de matriculados',

        'visitantes.required' => 'O n° de visitantes é obrigatório',
        'visitantes.integer' => 'O n° de visitantes é inválido',
        'visitantes.min' => 'O n° de visitantes é inválido',

        'assist_total.required' => 'O n° de assistência total é obrigatório',
        'assist_total.integer' => 'O n° de assistência total é inválido',
        'assist_total.min' => 'O n° de assistência total é inválido',
        'assist_total.max' => 'O n° de assistência total é inválido',

        'biblias.required' => 'O n° de Bíblias é obrigatório',
        'biblias.integer' => 'O n° de Bíblias é inválido',
        'biblias.min' => 'O n° de Bíblias é inválido',

        'revistas.required' => 'O n° de revistas é obrigatório',
        'revistas.integer' => 'O n° de revistas é inválido',
        'revistas.min' => 'O n° de revistas é inválido',

        'observacoes.max' => 'O campo de observações aceita, no máximo, 700 caracteres.'

    ]);
    

      $chamada = new Chamada;
      $chamada -> id_sala = $sala;
      $chamada -> nomes = $pessoas;
      $chamada -> presencas = $request -> presencas;
      $chamada -> matriculados = $request -> matriculados;
      $chamada -> presentes = $request -> presentes;
      $chamada -> visitantes = $request -> visitantes;
      $chamada -> assist_total = $request -> assist_total;
      $chamada -> biblias = $request -> biblias;
      $chamada -> revistas = $request -> revistas;
      $chamada -> observacoes = $request -> observacoes;
      $chamada -> save();

      return redirect('/classe/todas-chamadas')->with('msg', 'Chamada realizada com sucesso!');

   }

   public function searchChamadaClasse(Request $request) {
      $mes = request('mes');
      $ano = request('ano');
      $sala = auth()->user()->id_nivel;
      $findSala = Sala::findOrFail($sala);
      $meses_abv = [1 => 'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

     //mes
     if(isset($request -> mes) && empty($request -> ano))  {
         $chamadas = Chamada::where('id_sala', '=', $sala)
         ->whereMonth('created_at', '=', $request -> mes)
         ->orderBy('created_at', 'DESC')
         ->get();
     }
     //ano
     elseif(empty($request -> mes) && isset($request -> ano))  {
         $chamadas = Chamada::where('id_sala', '=', $sala)
         ->whereYear('created_at', '=', $request -> ano)
         ->orderBy('created_at', 'DESC')
         ->get();
     }
     //mes e ano
     elseif(isset($request -> mes) && isset($request -> ano))  {
         $chamadas = Chamada::where('id_sala', '=', $sala)
         ->whereMonth('created_at', '=', $request -> mes)
         ->whereYear('created_at', '=', $request -> ano)
         ->orderBy('created_at', 'DESC')
         ->get();

     } else {
         $chamadas = Chamada::where('id_sala', '=', $sala)
         ->whereDate('created_at', Carbon::today())
         ->orderBy('created_at', 'DESC')
         ->get();

     }
     return view('/classe/todas-chamadas', ['mes' => $mes, 'ano' => $ano, 'chamadas' => $chamadas, 'findSala' => $findSala, 'meses_abv' => $meses_abv]);

   }



   public function showChamadaClasse($id) {
      $nivel = auth()->user()->id_nivel;
      $findSala = Sala::findOrFail($nivel);
      $chamada = Chamada::findOrFail($id);
      if($nivel != $chamada -> id_sala) {
        return redirect('/classe')->with('msg2', 'Seu usuário não permissão para ver esta chamada');
        }
      return view('/classe/visualizar-chamada', ['chamada' => $chamada, 'findSala' => $findSala]);
      
   }

   public function searchAniversariantes(Request $request) {
    $nivel = auth()->user()->id_nivel;
    $mes = request('mes');
    $salas = Sala::where('id', '>', 2)->get();
    $meses_abv = [1 => 'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
    //mes
    if(isset($request -> mes)) {
        $pessoas = Pessoa::whereJsonContains('id_sala', ''.$nivel)
        ->whereMonth('data_nasc', '=', $request->mes)
        ->get(); 

    } else {
        $pessoas = Pessoa::whereJsonContains('id_sala', ''.$nivel)
        ->whereMonth('data_nasc', '=', Carbon::now())
        ->get(); 
    }

    return view('/classe/aniversariantes', ['pessoas' => $pessoas, 'salas' => $salas,
    'meses_abv' => $meses_abv, 'mes' => $mes]);
}
}
