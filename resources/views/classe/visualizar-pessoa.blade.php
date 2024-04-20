@extends('layouts.mainClasse')

@section('title', 'Início')

@section('content')
<link rel="stylesheet" href="/css/saber.css">
<div class="card-container">
	<span class="pro" @if($pessoa->situacao == 1) style="background-color: green" @else  style="background-color: red" @endif>@if($pessoa->situacao == 1)Ativo @else Inativo @endif</span>
	<span class="pro2" @if($pessoa->sexo == 1) style="background-color: blue" @else  style="background-color: pink" @endif> @if($pessoa->sexo == 1) Masculino @else Feminino @endif</span>
	@if($pessoa->responsavel != null)
	<span class="pro2"> Menor de idade</span>
	@endif

	<h3>{{$pessoa -> nome}}	</h3>
	<h4>@foreach($pessoa->salas as $key=>$sala)@if($sala->id == auth()->user()->id_nivel) {{ $sala->nome }} ({{ $pessoa->funcoes[$key]['nome'] }}) @endif   @endforeach</h4>
	<p> Idade: <span style="color: yellow">
		@if(floor((strtotime(date('Y-m-d')) - strtotime($pessoa -> data_nasc))/(60 * 60 * 24) /365.25) < 2)
        {{floor((strtotime(date('Y-m-d')) - strtotime($pessoa -> data_nasc))/(60 * 60 * 24) /365.25)}} ano
        @else
        {{floor((strtotime(date('Y-m-d')) - strtotime($pessoa -> data_nasc))/(60 * 60 * 24) /365.25)}} anos
        @endif

		({{date('d/m/Y', strtotime($pessoa -> data_nasc))}})</span>
	</p>
	<p>Endereço:  <span style="color: yellow">{{$pessoa -> cidade}} /@foreach($ufs as $uf) @if($uf -> id == $pessoa -> id_uf) {{$uf -> nome}} @endif @endforeach </span> </p>
	<p>N° de telefone: @if($pessoa -> telefone == null) <span style="color: #aaa">Sem dados</span> @else <a style="color: yellow" href="https://api.whatsapp.com/send?phone=55{{$pessoa -> telefone}}">{{$pessoa -> telefone}}</a>  @endif</p>

	<div class="skills">
		<h6>Infos Gerais</h6>
		<ul>
			<li>@foreach($formations as $f) @if($pessoa -> id_formation == $f -> id) {{$f -> nome}} @endif @endforeach </li>
			@if($pessoa->cursos != null)
			<li>{{$pessoa->cursos}}</li>
			@endif
		</ul>
	</div>
</div>



@endsection
