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
            'nome.required' =>  'Nome ?? obrigat??rio.',

            'sexo.required' =>  'Sexo ?? obrigat??rio.',
            'sexo.integer' =>  'S?? ?? aceito o sexo masculino ou feminino',
            'sexo.min' =>  'S?? ?? aceito o sexo masculino ou feminino',
            'sexo.max' =>  'S?? ?? aceito o sexo masculino ou feminino',

            'data_nasc.required' =>  'Data de nascimento ?? obrigat??ria.',

            'id_uf.required' =>  'UF ?? obrigat??ria.',
            'id_uf.integer' =>  'UF escolhida n??o existe.',
            'id_uf.min' =>  'UF escolhida n??o existe.',
            'id_uf.max' =>  'UF escolhida n??o existe.',

            'telefone.max' =>  'O telefone precisa de 11 d??gitos: DDD + n??mero',

            'id_formation.required' =>  'Forma????o ?? obrigat??ria.',
            'id_formation.integer' =>  'Forma????o escolhida n??o existe.',
            'id_formation.min' =>  'Forma????o escolhida n??o existe.',
            'id_formation.max' =>  'Forma????o escolhida n??o existe.',

            'id_sala.required' =>  'Classe ?? obrigat??ria.',
            'id_sala.max' =>  'Pessoa s?? pode ser cadastrada em uma classe',
            'id_sala.*.integer' =>  'Classe digitada n??o existe',
            'id_sala.*.min' =>  'Classe digitada n??o existe',
            'id_sala.*.max' =>  'Classe digitada n??o existe',

            'interesse.required' =>  'Interesse ?? obrigat??rio.',
            'interesse.integer' =>  'Interesse escolhido n??o existe.',
            'interesse.min' =>  'Interesse escolhido n??o existe.',
            'interesse.max' =>  'Interesse escolhido n??o existe.',

            'frequencia_ebd.integer' =>  'Frequ??ncia escolhida n??o existe.',
            'frequencia_ebd.min' =>  'Frequ??ncia escolhida n??o existe.',
            'frequencia_ebd.max' =>  'Frequ??ncia escolhida n??o existe.',

            'curso_teo.integer' =>  'Valor inv??lido para curso de Teologia',
            'curso_teo.min' =>  'Valor inv??lido para curso de Teologia',
            'curso_teo.max' =>  'Valor inv??lido para curso de Teologia',

            'id_funcao.integer' =>  'Pessoa s?? pode ser cadastrada como aluno',
            'id_funcao.min' =>  'Pessoa s?? pode ser cadastrada como aluno',
            'id_funcao.max' =>  'Pessoa s?? pode ser cadastrada como aluno',

            'situacao.integer' =>  'Pessoa s?? pode ser cadastrada como ativa',
            'situacao.min' =>  'Pessoa s?? pode ser cadastrada como ativa',
            'situacao.max' =>  'Pessoa s?? pode ser cadastrada como ativa',

            'prof_ebd.integer' =>  'Escolha para professor de EBD escolhida n??o existe.',
            'prof_ebd.min' =>  'Escolha para professor de EBD escolhida n??o existe.',
            'prof_ebd.max' =>  'Escolha para professor de EBD escolhida n??o existe.',

            'prof_comum.integer' =>  'Escolha para professor comum escolhida n??o existe.',
            'prof_comum.min' =>  'Escolha para professor comum escolhida n??o existe.',
            'prof_comum.max' =>  'Escolha para professor comum escolhida n??o existe.',

            'id_public.integer' =>  'P??blico escolhido n??o existe.',
            'id_public.min' =>  'P??blico escolhido n??o existe.',
            'id_public.max' =>  'P??blico escolhido n??o existe.',

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
        return redirect('/classe')->with('msg2', 'Seu usu??rio n??o permiss??o para ver esta pessoa');
    }
  }

  public function indexChamadaClasse() {
      $sala = auth()->user()->id_nivel;
      $chamadas = Chamada::where('id_sala', '=', $sala)->whereDate('created_at', Carbon::today())->get();
      $pessoas = DB::table('pessoas')
      ->select('id', 'nome', 'data_nasc', 'id_funcao')
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
        return redirect('/classe/chamada-dia')->with('msg', 'A chamada n??o pode ser realizada.');
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
        'biblias' => ['required', 'integer', 'min:0', 'max:'.$request->presentes + $request->visitantes],
        'revistas' => ['required', 'integer', 'min:0', 'max:'.$request->presentes + $request->visitantes],
        'observacoes' => ['max: 800']  
    ], [

        'id_sala.integer' => 'Classe escolhida inexistente',
        'id_sala.min' => 'Classe escolhida inexistente',
        'id_sala.max' => 'Classe escolhida inexistente',

        'matriculados.required' => 'O n?? de matriculados ?? obrigat??rio',
        'matriculados.integer' => 'O n?? de matriculados ?? inv??lido',
        'matriculados.min' => 'O n?? de matriculados ?? inv??lido',
        'matriculados.max' => 'O n?? de matriculados ?? inv??lido',

        'presentes.required' => 'O n?? de presentes ?? obrigat??rio',
        'presentes.integer' => 'O n?? de presentes ?? inv??lido',
        'presentes.min' => 'O n?? de presentes ?? inv??lido',
        'presentes.max' => 'O n?? de presentes n??o pode ser maior que o de matriculados',

        'visitantes.required' => 'O n?? de visitantes ?? obrigat??rio',
        'visitantes.integer' => 'O n?? de visitantes ?? inv??lido',
        'visitantes.min' => 'O n?? de visitantes ?? inv??lido',

        'assist_total.required' => 'O n?? de assist??ncia total ?? obrigat??rio',
        'assist_total.integer' => 'O n?? de assist??ncia total ?? inv??lido',
        'assist_total.min' => 'O n?? de assist??ncia total ?? inv??lido',
        'assist_total.max' => 'O n?? de assist??ncia total ?? inv??lido',

        'biblias.required' => 'O n?? de B??blias ?? obrigat??rio',
        'biblias.integer' => 'O n?? de B??blias ?? inv??lido',
        'biblias.min' => 'O n?? de B??blias ?? inv??lido',
        'biblias.max' => 'O n?? de B??blias ?? maior que o de pessoas',

        'revistas.required' => 'O n?? de revistas ?? obrigat??rio',
        'revistas.integer' => 'O n?? de revistas ?? inv??lido',
        'revistas.min' => 'O n?? de revistas ?? inv??lido',
        'revistas.max' => 'O n?? de revistas ?? maior que o de pessoas',

        'observacoes.max' => 'O campo de observa????es aceita, no m??ximo, 700 caracteres.'

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
        return redirect('/classe')->with('msg2', 'Seu usu??rio n??o permiss??o para ver esta chamada');
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
    public function generatePdfToChamadas($id) {

        $chamada = Chamada::select('chamadas.*', 'salas.nome')->join('salas', 'chamadas.id_sala', '=', 'salas.id')->findOrFail($id);

        return \PDF::loadView('/classe/pdf-chamada', compact(['chamada']))
        ->stream('frequencia.pdf');
    }
}
