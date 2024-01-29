@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/filtros.css">

 <div style="margin: 15px">

  <form action="/admin/chamadas" method="POST">
  @csrf
  <div class="fields">

  <div class="itens">
  <legend class="title">Filtrar por: </legend>
  </div>

    <div class="itens">



    <select name="classe">
        <option selected disabled value="">Classe</option>
          @foreach($salas as $s)
              <option value="{{$s -> id}}">{{$s -> nome}}</option>
          @endforeach

      </select>

      <select name="mes">
        <option selected disabled value="">Mês</option>
          @foreach($meses_abv as $num => $nome)
              <option value="{{$num}}">{{$nome}} ({{$num}})</option>
          @endforeach

      </select>

      <select name="ano">
        <option selected disabled value="">Ano</option>
          @for($i = 2022; $i <= date('Y'); $i++)
              <option value="{{$i}}">{{$i}}</option>
          @endfor

      </select>




    <div class="btnFilter">
    <button type="submit" class="filter">Filtrar</button>
    </div>

    <div class="btnFilter">
    <button type="reset" class="resett">Limpar tudo</button>
    </div>

  </div>
  </div>
  </form>
</div>


    @if(isset($classe) || isset($mes) || isset($ano))
  <div class="busca">
    <p class="tit">Buscando por:</p>

    @if(isset($classe))
    <li class="ponto">Classe:
        <i class="result">@foreach ($salas as $s)  @if($classe == $s -> id) {{$s->nome}} @endif @endforeach</i>
    </li>
    @endif

    @if(isset($mes))
    <li class="ponto">Mês:
        <i class="result">@foreach($meses_abv as $num => $nome) @if($mes == $num) {{$nome}} ({{$num}}) @endif @endforeach</i>
    </li>
    @endif

    @if(isset($ano))
    <li class="ponto">Ano:
        <i class="result">{{$ano}}</i>
    </li>
    @endif


  </div>
  @else
  <div class="busca">
    <p class="tit">Buscando por: <i class="result">Chamadas de hoje</i></p>
  </div>
  @endif


@if($chamadas->count() > 0)
  <table style="margin:3%">

  @if($chamadas -> count() > 1)
  <caption class="cont"><h4>Chamadas: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$chamadas -> count()}}</font></h4></caption>
  @endif

  <thead>
    <tr>
      <th>Classe
      <th>Data
      <th style="text-align:center">Matriculados
      <th style="text-align:center">Presentes
      <th style="text-align:center">Visitantes
      <th style="text-align:center">Assist. Total
      <th style="text-align:center">Bíblias
      <th style="text-align:center">Revistas
      <th style="text-align:center">Observações</th>
      <th>Ação
  </thead>
  @foreach($chamadas as $c)

  <tbody>
    <tr> <!-- <tr class="disabled">  -->

      <td style="font-weight: bold; color: yellow">@foreach($salas as $s) @if($s -> id == $c -> id_sala) {{$s -> nome}} @endif  @endforeach
      <td>@if(date('d/m/Y', strtotime($c -> created_at)) == date('d/m/Y')) <span style="background-color: red; padding: 3px; border-radius: 5px; font-weight: bold">Hoje!</span>@else {{date('d/m/Y', strtotime($c -> created_at))}} @endif
      <td style="text-align:center">{{$c -> matriculados}}
      <td style="text-align:center">{{$c -> presentes}}
      <td style="text-align:center">{{$c -> visitantes}}
      <td style="text-align:center">{{$c -> assist_total}}
      <td style="text-align:center">{{$c -> biblias}}
      <td style="text-align:center">{{$c -> revistas}}
        <td style="text-align:center">@if($c->observacoes)<i class='bx bx-message-error' style="color:red; font-size: 1.3em"></i>@endif</td>
       <td><a href="/admin/visualizar/chamada/{{$c->id}}" style="text-decoration: none; color:black; margin: 5px;"><i style="font-size: 1.8em;margin: 1px;" class='bx bx-show icon'></i> </a>
           <a href="/admin/visualizar/pdf-chamada/{{$c->id}}" style="text-decoration: none; color:black; margin: 5px;"><i style="font-size: 1.8em;margin: 1px;" class='bx bxs-file-pdf'></i> </a>
    </tr>

  </tbody>

  @endforeach
</table>
@else
<p style="margin-top: 15%; color: yellow; text-align: center">Nenhum resultado encontrado</p>
@endif
@endsection
