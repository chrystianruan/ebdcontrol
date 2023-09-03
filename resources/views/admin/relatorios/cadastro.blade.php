@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/filtros.css">


@if(date('w') == 0)
@if($chamadas->count() != $salas->count() && $relatorioToday -> count() < 1)
<div class="orientation">
  <div class="aaa">
      <p><i style="color: white; margin: 5px"class="fa fa-exclamation-circle"></i>{{$salas->count() - $chamadas->count()}} @if($salas->count() - $chamadas->count() > 1) classes ainda não fizeram a chamada @else classe ainda não fez a chamada @endif</p>
  </div>
</div>
@endif




@if ($relatorioToday -> count() < 1)
<form action="/admin/relatorios/cadastro" method="POST">
    @csrf
 <div style="margin: 3%">
    <h2 style="color: white">Relatório de hoje - {{date('d/m/Y')}}</h2>
 </div>
  <table style="margin: 3% 3% 0 3%">

  @if($chamadas -> count() > 1)
  <caption class="cont"><h4>Chamadas realizadas: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$chamadas -> count()}}</font></h4></caption>
  @endif

  <thead>
    <tr>
      <th>Classe
      <th style="text-align:center">Matriculados
      <th style="text-align:center">Presentes
      <th style="text-align:center">Visitantes
      <th style="text-align:center">Assist. Total
      <th style="text-align:center">Bíblias
      <th style="text-align:center">Revistas
    </tr>
  </thead>
  @foreach($chamadas as $c)

  <tbody>
    <tr> <!-- <tr class="disabled">  -->

      <td style="font-weight: bold; color: yellow">@foreach($salas as $s) @if($s -> id == $c -> id_sala) {{$s -> nome}} @endif  @endforeach
      <td style="text-align:center">{{$c -> matriculados}}
      <td style="text-align:center">{{$c -> presentes}}
      <td style="text-align:center">{{$c -> visitantes}}
      <td style="text-align:center">{{$c -> assist_total}}
      <td style="text-align:center">{{$c -> biblias}}
      <td style="text-align:center">{{$c -> revistas}}

    </tr>

  </tbody>

  @endforeach

  <tbody>
    <tr style="background-color: darkred; color: yellow">
        <td>Total: </td>
        <td style="text-align:center">{{$chamadas -> sum('matriculados')}}</td>
        <td style="text-align:center">{{$chamadas -> sum('presentes')}}</td>
        <td style="text-align:center">{{$chamadas -> sum('visitantes')}}</td>
        <td style="text-align:center">{{$chamadas -> sum('assist_total')}}</td>
        <td style="text-align:center">{{$chamadas -> sum('biblias')}}</td>
        <td style="text-align:center">{{$chamadas -> sum('revistas')}}</td>
    </tr>
  </tbody>


</table>

    <div class="btnFilter2" >
        <button type="submit" class="filter" style="width: 120px; height: 40px">Salvar</button>
    </div>
</form>

@else
<div class="notRegister">
  <p> O relatorio de hoje já foi enviado. <a href="/admin/relatorios/todos" style="color: blue">Ver relatórios</a>
  </p>
</div>

@endif
@else
    <div class="notRegister">
        <p> Hoje não é domingo</a>
        </p>
    </div>
@endif
@endsection
