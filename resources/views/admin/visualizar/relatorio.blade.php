@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/filtros.css">

  <table style="margin: 3% 3% 0 3%">


  <thead>
    <tr>
      <th>Classe
      <th style="text-align:center">Matriculados
      <th style="text-align:center">Presentes
      <th style="text-align:center">Visitantes
      <th style="text-align:center">Assist. Total
      <th style="text-align:center">Bíblias
      <th style="text-align:center">Revistas
      <th>Ação
    </tr>
  </thead>

  @foreach($relatorio -> salas as $sala)

  <tbody>
    <tr> <!-- <tr class="disabled">  -->
     
      <td style="font-weight: bold; color: yellow">@foreach($salas as $s) @if($s -> id == $sala['id_sala']) {{$s -> nome}} @endif  @endforeach
      <td style="text-align:center">{{$sala['matriculados']}}
      <td style="text-align:center">{{$sala['presentes']}}
      <td style="text-align:center">{{$sala['visitantes']}}
      <td style="text-align:center">{{$sala['assist_total']}}
      <td style="text-align:center">{{$sala['biblias']}}
      <td style="text-align:center">{{$sala['revistas']}}
      <td style="text-align:center"><a href="/admin/visualizar/chamada/{{$sala['id']}}" style="text-decoration: none; color:black; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-show icon'></i> </a>
   
    </tr>
      
  </tbody>
  @endforeach


  <tbody>
    <tr style="background-color: darkred; color: yellow">
        <td>Total: </td>
        <td style="text-align:center">{{$relatorio -> matriculados}}</td>
        <td style="text-align:center">{{$relatorio -> presentes}}</td>
        <td style="text-align:center">{{$relatorio -> visitantes}}</td>
        <td style="text-align:center">{{$relatorio -> assist_total}}</td>
        <td style="text-align:center">{{$relatorio -> biblias}}</td>
        <td style="text-align:center">{{$relatorio -> revistas}}</td>
        <td></td>
    </tr>
  </tbody>


</table>



@endsection