@extends('layouts.main')

@section('title', 'Início')

@section('content')
<link rel="stylesheet" href="/css/filtros.css">
<div style="margin: 15px">
<form action="/admin/relatorios/todos" method="POST">
    @csrf
    @if(date('w') == 0 || date('Y-m-d') == $dateChamadaDia)
        @if($chamadas->count() != $salas->count())
        <div class="orientation">
          <div class="aaa">
              @if($salas->count() - $chamadas->count() == $salas->count())
                <p>Nenhuma classe enviou a chamada</p>
              @elseif($salas->count() - $chamadas->count() != $salas->count() && $salas->count() - $chamadas->count() != 0)
                  <p>Classes que ainda não enviaram a chamada ({{ count($classesFaltantes) }}): @for($i = 0; $i < count($classesFaltantes); $i++) <span style="font-weight: bold">{{ $classesFaltantes[$i]['nome'] }}@if($i+1 != count($classesFaltantes)),@endif </span> @endfor </p>
              @else
                <p>Todas as classes enviaram a chamada!</p>
              @endif
          </div>
        </div>
        @endif
    @endif

    <div class="fields">

    <div class="itens">
    <legend class="title">Filtrar por: </legend>
    </div>

      <div class="itens">

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

    @if(isset($mes) || isset($ano))
    <div class="busca">
      <p class="tit">Buscando por:</p>

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
      <p class="tit">Buscando por: <i class="result">Relatório de hoje</i></p>
    </div>
    @endif
    @if($relatorios -> count() > 0)
    <table style="margin:3%">

        @if($relatorios -> count() > 1)
        <caption class="cont"><h4>Relatórios: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$relatorios -> count()}}</font></h4></caption>
        @endif

        <thead>
          <tr>
            <th>Data
            <th style="text-align:center">Matriculados
            <th style="text-align:center">Presentes
            <th style="text-align:center">Visitantes
            <th style="text-align:center">Assist. Total
            <th style="text-align:center">Bíblias
            <th style="text-align:center">Revistas
            <th>Ação
        </thead>
        @foreach($relatorios as $r)

        <tbody>
          <tr> <!-- <tr class="disabled">  -->

            <td>@if(date('d/m/Y', strtotime($r -> created_at)) == date('d/m/Y')) <span style="background-color: red; padding: 3px; border-radius: 5px; font-weight: bold">Hoje!</span>@else {{date('d/m/Y', strtotime($r -> created_at))}} @endif
            <td style="text-align:center">{{$r -> matriculados}}
            <td style="text-align:center">{{$r -> presentes}}
            <td style="text-align:center">{{$r -> visitantes}}
            <td style="text-align:center">{{ $r->presentes + $r->visitantes }}
            <td style="text-align:center">{{$r -> biblias}}
            <td style="text-align:center">{{$r -> revistas}}
             <td><a href="/admin/visualizar/relatorio/{{ date('Y-m-d', strtotime($r->created_at)) }}" style="text-decoration: none; color:black; margin: 5px;"><i style="font-size: 1.8em;margin: 1px;" class='bx bx-show icon'></i> </a> <a href="/admin/visualizar/pdf-relatorio/{{ date('Y-m-d', strtotime($r->created_at)) }}" style="text-decoration: none; color:black; margin: 5px;"><i style="font-size: 1.8em;margin: 1px;" class='bx bxs-file-pdf'></i> </a>
          </tr>

        </tbody>

        @endforeach
      </table>
    @else
        <p style="margin-top: 15%; color: yellow; text-align: center">Nenhum resultado encontrado</p>
    @endif
@endsection
