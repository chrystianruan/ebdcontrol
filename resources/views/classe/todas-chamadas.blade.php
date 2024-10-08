@extends('layouts.mainClasse')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/filtrosClasse.css">

 <div style="margin: 15px; color: white; display: flex; flex-direction: column" >

  <form action="/classe/todas-chamadas" method="POST">
    @csrf
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

  </div>
  </div>
  </form>




  <div class="busca">
    @if(isset($mes) || isset($ano))
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

    @else
    <p class="tit">Buscando por: <i class="result">Chamada de hoje</i></p>

    @endif
  </div>


</div>



@if($chamadas->count() > 0)
  <div style="overflow-x:auto; margin: 1% 3%">
  <table style="width: 100%;">
    <thead>
      <tr>
        <th>Data</th>
        <th>Matriculados</th>
        <th>Presentes</th>
        <th>Visitantes</th>
        <th>Assist. Total</th>
        <th>Bíblias</th>
        <th>Revistas</th>
        <th>Observações</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($chamadas as $c)
      <tr>
        <td>@if(date('d/m/Y', strtotime($c -> created_at)) == date(('d/m/Y'))) <span style="font-weight: bolder; color: lawngreen">Hoje</span> @else {{date('d/m/Y', strtotime($c -> created_at))}} @endif </td>
        <td>{{ $c->matriculados }}</td>
        <td>{{ $c->presentes }} <span style="color: rgb(9, 150, 115); font-weight: bold">({{ number_format(100 * $c->presentes / $c->matriculados, 1, ',', '.') }}%) </span></td>
        <td>{{ $c->visitantes }} </td>
        <td>{{ $c->presentes + $c->visitantes }} <span style="color: rgb(9, 150, 115); font-weight: bold">({{ number_format(100 * ($c->presentes + $c->visitantes) / $c->matriculados, 1, ',', '.') }}%) </span></td>
        <td>{{ $c->biblias }} <span style="color: rgb(9, 150, 115); font-weight: bold">({{ number_format(100 * $c->biblias / ($c->presentes + $c->visitantes), 1, ',', '.') }}%) </span></td>
        <td>{{ $c->revistas }} <span style="color: rgb(9, 150, 115); font-weight: bold">({{ number_format(100 * $c->revistas / ($c->presentes + $c->visitantes), 1, ',', '.') }}%)</span></td>
        <td>@if($c->observacoes)<i class='bx bx-message-error' style="color:red; font-size: 1.3em"></i> @endif</td>
        <td><a href="/classe/visualizar-chamada/{{$c->id}}" style="text-decoration: none; color:#7B4EA5; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left; color: #7B4EA5" class='bx bx-show icon'></i> </a>
        <a href="/classe/pdf-chamada/{{$c->id}}" style="text-decoration: none; color:#7B4EA5;float: left; margin: 5px;"><i style="font-size: 1.8em;margin: 1px;float: left;" class='bx bxs-file-pdf'></i> </a> </td>
      </tr>
      @endforeach

    </tbody>
  </table>
</div>

@else
<div class="ngm">
  <p><i class='fa fa-exclamation-triangle'></i>Nenhuma chamada encontrada</p>
</div>
@endif


@endsection
