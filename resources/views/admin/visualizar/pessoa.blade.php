@extends('layouts.main')

@section('title', 'Início')

@section('content')
<style>
    .marker {
        font-weight: bolder;
        color: yellow
    }
</style>
<link rel="stylesheet" href="/css/saber.css">
<div class="card-container">
	<span class="pro" @if($pessoa->situacao == 1) style="background-color: green" @else  style="background-color: red" @endif>@if($pessoa->situacao == 1)Ativo @else Inativo @endif</span>
	<span class="pro2" @if($pessoa->sexo == 1) style="background-color: blue" @else  style="background-color: pink" @endif> @if($pessoa->sexo == 1) Masculino @else Feminino @endif</span>
	@if($pessoa->responsavel != null)
	<span class="pro2"> Menor de idade</span>
	@endif
	<img class="round"
	src="@if($pessoa -> id_funcao == 1) /img/student.png
	@elseif($pessoa -> id_funcao == 2) /img/teacher.png
	@elseif($pessoa -> id_funcao == 3) /img/consultant.png
	@elseif($pessoa -> id_funcao == 4) /img/secretary.png
	@else /img/manager.png


	@endif" alt="user" />
	<h3>{{$pessoa -> nome}}	</h3>
	<h4>@foreach($salas as $sala)@foreach($pessoa->id_sala as $ids) @if($sala->id == $ids) {{$sala->nome}}@if(count($pessoa->id_sala) > 1 && $pessoa->id_sala[0]),@endif  @endif @endforeach @endforeach</h4>
	<h6 style="color: yellow">{{ $pessoa->funcao_nome }}</h6>
	<p> Idade: <span class="marker">
		@if(floor((strtotime(date('Y-m-d')) - strtotime($pessoa -> data_nasc))/(60 * 60 * 24) /365.25) < 2)
        {{floor((strtotime(date('Y-m-d')) - strtotime($pessoa -> data_nasc))/(60 * 60 * 24) /365.25)}} ano
        @else
        {{floor((strtotime(date('Y-m-d')) - strtotime($pessoa -> data_nasc))/(60 * 60 * 24) /365.25)}} anos
        @endif

		({{date('d/m/Y', strtotime($pessoa -> data_nasc))}})</span>
	</p>
	<p>Endereço:  <span class="marker">{{$pessoa -> cidade}} / {{ $pessoa->nome_uf }}</span> </p>
	<p>N° de telefone:  <span class="marker"> @if($pessoa -> telefone == null)</span> <span style="color: #aaa">Sem dados</span> @else {{$pessoa -> telefone}} @endif</p>
    <p>Paternidade/Maternidade: <span class="marker"> @if($pessoa -> paternidade_maternidade == null) <span style="color: #aaa">Não</span> @else {{ $pessoa->paternidade_maternidade }} @endif </span></p>
    @if($pessoa->responsavel)
    <p>Nome responsável: <span class="marker">{{ $pessoa->responsavel }} </span> </p>
    <p>Número de telefone do responsável: <span class="marker">{{ $pessoa->telefone_responsavel }} </span> </p>
    @endif
	<div class="skills">
		<h6>Infos Gerais</h6>
		<ul>
			<li>Escolaridade: <span class="marker">{{ $pessoa->nome_formation }} </span></li>
			@if($pessoa->cursos != null)
			<li>Cursos: <span class="marker">{{$pessoa->cursos}}</span></li>
			@endif
		</ul>
        @if ($pessoa->interesse === 1 || $pessoa->interesse === 3)
        <h6>Interesse em ser professor</h6>
        <ul>
            <li>Resposta: <span class="marker">@if ($pessoa->interesse === 1) Sim @else Talvez @endif</span></li>
        </ul>
        <ul>
            <li>Sempre frequentou a EBD? <span class="marker">@if($pessoa->frequencia_ebd == 1) Sim @elseif($pessoa->frequencia_ebd == 2) Não @else Mais ou menos @endif </span> </li>
            <li>Possui curso de teologia? <span class="marker">@if($pessoa->curso_teo == 1) Sim @else Não @endif </span></li>
            <li>É/foi professor da EBD? <span class="marker">@if($pessoa->prof_ebd == 1) Sim @else Não @endif </span></li>
            <li>É/foi professor secular? <span class="marker">@if($pessoa->prof_comum == 1) Sim @else Não @endif </span></li>
            <li>Para qual público prefere dar aula? <span class="marker">{{$pessoa->nome_publico}}</span></li>
        </ul>
        @endif
	</div>



</div>
@endsection
