@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/financeiro_filter.css">
<div  style="margin: 15px">
<form action="/admin/financeiro/filtro" method="POST">
    @csrf
    <div class="fields">

    <div class="itens">
    <legend class="title">Filtrar por:  </legend>
    </div>
    <font style=";margin-left: 15px;font-size: 12px; color: white">(Os filtros <mark>Categoria</mark>, <mark>Tipo</mark>, <mark>Mês</mark> e <mark>Ano</mark> dependem do <mark>Financeiro</mark> para funcionarem corretamente)</font>
      <div class="itens">


      <select name="resultado" required @if($resultado == 1) style="border: 2px solid; border-color: green" @elseif($resultado == 2) style="border-color: red" @else @endif>

        <option selected disabled value="">Financeiro</option>
        @foreach ($selectFinanceiros as $valor => $nome)
        <option value="{{$valor}}" style="@if($valor == 1) color: green;  @else color: red; @endif">{{$nome}} </option>
        @endforeach
      </select>
      <div style="margin-left: 3%">
      <select name="cat">
        <option selected disabled value="">Categoria</option>
        @foreach ($cats as $c)
            <option value="{{$c->id}}">{{$c->nome}}</option>
        @endforeach
      </select>



      <select name="tipo">
        <option selected disabled value="">Tipo</option>
        @foreach ($tipos as $t)
            <option value="{{$t->id}}">{{$t->nome}}</option>
        @endforeach
      </select>

      <select name="mes" >
        <option selected disabled value="">Mês</option>
          @foreach($meses_abv as $ind => $nome)
              <option value="{{$ind}}"> {{$ind}} - {{$nome}} </option>
           @endforeach

      </select>

      <select name="ano" >
        <option selected disabled value="">Ano</option>
              @for($i = 2022; $i <= date('Y'); $i++)
              <option value="{{$i}}">{{$i}}</option>
              @endfor
      </select>
      </div>
      <div class="btnFilter">
      <button type="submit" class="filter">Filtrar</button>
      <button type="reset" class="resett">Limpar tudo</button>
      </div>
    </div>
    </form>
    @if ($errors->any())
    <div class="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if(isset($resultado) || isset($categoria) || isset($tipo) || isset($mes) || isset($ano))
    <div class="busca" @if($resultado == 1) style="background-color: green;" @elseif($resultado == 2) style="background-color: red" @endif>
        <p class="tit">Buscando por:</p>
        @if(isset($resultado))
         <li class="ponto">Financeiro:
            <i class="result">
            @foreach ($selectFinanceiros as $valor => $nome) @if($resultado == $valor) {{$nome}} @endif @endforeach
            </i>
         </li>
        @endif

        @if(isset($resultado) && isset($categoria))
        <li class="ponto">Categoria:
          <i class="result">@foreach ($cats as $c) @if($categoria == $c->id) {{$c->nome}} @endif @endforeach</i>
        </li>
        @endif

        @if(isset($resultado) && isset($tipo))
        <li class="ponto">Tipo:
          <i class="result"> @foreach ($tipos as $t) @if($tipo == $t->id) {{$t->nome}} @endif @endforeach</i>
        </li>
        @endif

        @if(isset($resultado) && isset($mes))
        <li class="ponto">Mês:
          <i class="result"> @foreach($meses_abv as $ind => $nome) @if($mes == $ind) {{$mes}} - {{$nome}} @endif @endforeach</i>
        </li>
        @endif

        @if(isset($resultado) && isset($ano))
        <li class="ponto">Ano: <i class="result">{{$ano}}</i></li>
        @endif

        </div>
        @else
        <div class="busca">
            <p class="tit">Buscando por: <i class="result" style="color: rgb(9, 150, 115)">Tudo</i></p>
        </div>
        @endif


      </div>
    </div>


    @if($financeiros -> count() > 0)
    <table style="margin:3%;">
    @if($financeiros -> count() > 0)
    <caption class="cont">
       <h4 style="float:left; background-color: #7B4EA5;padding: 5px; border-radius: 10px 10px 0 0 ">Quantidade: <font style="color: @if($resultado == 1) green; @elseif($resultado == 2) red; @else white; @endif background-color: black; border-radius: 5px; padding: 0 10px">{{$financeiros -> count()}}</font></h4>
       @if(isset($resultado))<h4 style="float:right; background-color: #7B4EA5; padding: 5px; border-radius: 10px 10px 0 0 ">Total: <font style="color:@if($resultado == 1) green; @elseif($resultado == 2) red; @else white; @endif; background-color: black; border-radius: 5px; padding: 0 10px; "> R$ {{number_format($financeiros -> sum('valor'), 2, ",", "." )}}</font></h4>@else @endif
    </caption>
    @endif
    <thead>
      <tr>
        <th>Valor
        <th>Descrição
        <th>Data
        <th>Categoria
        <th>Tipo
        <th>Dono
        <th style="text-align: center">Ações
        <th style="text-align: center">Edição
    </thead>
    @foreach($financeiros as $f)
    <tbody>
      <tr> <!-- <tr class="disabled">  -->
        <td style="font-weight: bold; color: @if($f->id_financeiro == 1) green; @elseif($f->id_financeiro == 2) red; @else yellow; @endif">R$ {{number_format($f->valor , 2, ",", "." )}}
        <td>@if(strlen($f->descricao) > 40) {{substr($f -> descricao, 0, 32)}}... @else {{$f->descricao}} @endif
        <td>{{date('d/m/Y', strtotime($f -> data_cad))}}
        <td>@foreach($cats as $c) @if($c->id == $f->id_cat) {{$c -> nome}} @endif @endforeach
        <td>@foreach($tipos as $t) @if($t->id == $f->id_tipo) {{$t -> nome}} @endif @endforeach
        <td>@foreach($users as $u) @if($u->id == $f->user_id) {{$u -> username}} @endif @endforeach
        <td style="text-align: center">
          <a href="/admin/financeiro/visualizar/{{$f->id}}" style="text-decoration: none; color:black; margin: 5px;"><i style="font-size: 1.8em;margin: 1px;" class='bx bx-show icon'></i> </a>
          <a href="/admin/financeiro/editar/{{$f->id}}" style="text-decoration: none; color:black; margin: 5px;"><i style="font-size: 1.8em;margin: 1px;" class='bx bx-edit icon'></i> </a>
        </td>
        <td style="text-align: center">@if($f -> created_at != $f -> updated_at) <i style="font-size: 1.8em; color: red"class='bx bx-error'></i> @endif

  </tr>

    </tbody>
    @endforeach
  </table>
  @elseif($financeiros -> count() > 0 && empty($resultado))
  <p style="color: pink"> Selecione o tipo financeiro</p>
  @else
  <p style="color: yellow; margin-top: 15%; text-align: center">Nenhum resultado encontrado.</p>
  @endif

  @endsection
