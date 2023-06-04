@extends('layouts.main')

@section('title', 'Início')

@section('content')
<link rel="stylesheet" href="/css/saberFinanceiro.css">
<div class="card-container">
	<span class="pro" @if($financeiro->situacao == 1) style="background-color: green;" @else style="background-color: red" @endif> @if($financeiro->situacao == 1)Ativo @else Inativo @endif</span>
    <span class="pro2" @if($financeiro->id_financeiro == 1) style="background-color: green;" @else  style="background-color: red" @endif>@if($financeiro->id_financeiro == 1) Entrada @else Saída @endif</span>
	<h3> Valor: <font  style=" color: @if($financeiro->id_financeiro == 1) green; @else red; @endif"> R$ {{ number_format($financeiro->valor , 2, ",", "." ) }}</font>

    </h3>
    <h6>Data da @if($financeiro -> id_financeiro == 1) Entrada @else Saída: @endif <font style="color: @if($financeiro->id_financeiro == 1) green; @else red; @endif"> {{ date('d/m/Y', strtotime($financeiro -> data_cad)) }} </font></h6>
	<h6>Categoria: @foreach($cats as $c) @if($c -> id == $financeiro -> id_cat) <font style="color: @if($financeiro->id_financeiro == 1) green; @else red; @endif">{{ $c -> nome }}</font> @endif @endforeach</h6>
    <h6>Tipo: @foreach($tipos as $t) @if($t -> id == $financeiro -> id_tipo) <font   style=" color: @if($financeiro->id_financeiro == 1) green; @else red; @endif">{{ $t -> nome }}</font> @endif @endforeach</h6>
	<p style="text-align: justify"> Descrição: <font  style=" color: @if($financeiro->id_financeiro == 1) green; @else red; @endif"> {{ $financeiro -> descricao }}</font></p>
    <p> Cadastrado em: <font  style=" color: @if($financeiro->id_financeiro == 1) green; @else red; @endif"> {{ date('d/m/Y H:i:s', strtotime($financeiro -> created_at)) }}</font></p>
    <p> Atualizado em: <font  style=" color: @if($financeiro->id_financeiro == 1) green; @else red; @endif"> {{ date('d/m/Y H:i:s', strtotime($financeiro -> updated_at)) }}</font> @if($financeiro -> created_at != $financeiro -> updated_at) <i style="font-size: 1.8em; color: yellow"class='bx bx-error'></i>  @endif</p>
    @if($financeiro -> created_at != $financeiro -> updated_at)
    <hr><br>
    <h5>Dados antes da edição</h5>

        @if($financeiro->valor != $financeiro->valor_original)
            <h3> Valor original:
                <span  style=" color: @if($financeiro->id_financeiro == 1) green; @else red; @endif"> R$ {{ number_format($financeiro->valor_original , 2, ",", "." ) }}</span>
            </h3>
        @endif

        @if($financeiro->data_cad != $financeiro->data_cad_original)
            <h6> Data original:
                <span  style=" color: @if($financeiro->id_financeiro == 1) green; @else red; @endif"> {{ date('d/m/Y', strtotime($financeiro -> data_cad_original)) }} </span>
            </h6>
        @endif

        @if($financeiro->id_cat != $financeiro->id_cat_original) <h6> Categoria original:
            <span  style=" color: @if($financeiro->id_financeiro == 1) green; @else red; @endif">
                @foreach($cats as $c) @if($c -> id == $financeiro -> id_cat_original){{ $c -> nome }} @endif @endforeach }}
            </span>
        </h6>
        @endif

        @if($financeiro->id_tipo != $financeiro->id_tipo_original)
            <h6> Tipo original:
                <span  style=" color: @if($financeiro->id_financeiro == 1) green; @else red; @endif">
                    @foreach($tipos as $t) @if($t -> id == $financeiro -> id_tipo_original) <font   style=" color: @if($financeiro->id_financeiro == 1) green; @else red; @endif">{{ $t -> nome }}</font> @endif @endforeach
                </span>
            </h6>
        @endif

        @if($financeiro->descricao != $financeiro->descricao_original)
            <p> Descrição original:
                <span  style=" color: @if($financeiro->id_financeiro == 1) green; @else red; @endif"> {{ $financeiro->descricao }}</span>
            </p>
        @endif

    @endif
	<div class="skills">
		<h6>Informações do usuário que cadastrou</h6>
        @foreach($users as $user)
            @if($user -> id == $financeiro -> user_id)
                <ul>
                    <li>{{ $user->name }}</li>
                    <li>{{ $user->username }}</li>
                </ul>
            @endif
        @endforeach
	</div>



</div>

@endsection
