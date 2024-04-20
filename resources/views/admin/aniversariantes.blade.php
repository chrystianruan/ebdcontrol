@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/filtros.css">

 <div style="margin: 15px">

  <form action="/admin/aniversariantes" method="POST">
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
      @foreach($meses_abv as $val => $nome)
      <option value="{{$val}}">{{$val}} - {{$nome}}</option>
      @endforeach

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



  <div class="busca">

  @if(isset($classe) || isset($mes))
    <p class="tit">Buscando por:</p>

    @if(isset($classe))
    <p class="it">Classe: <i class="result">@foreach($salas as $s) @if($s -> id == $classe) {{$s-> nome}}  @endif @endforeach</i></p>
    @endif

    @if(isset($mes))
    <p class="it">Mês: <i class="result"> @foreach($meses_abv as $val => $nome) @if($val == $mes) {{$val}} - {{$nome}} @endif @endforeach</i></p>
    @endif

    @else

    <p class="it">Buscando por: <i class="result">Aniversariantes do mês atual ({{date('m')}})</i></p>

    @endif


  </div>



  <table style="margin:3%">

  @if($pessoas -> count() > 1)
  <caption class="cont"><h4>Pessoas: <span style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$pessoas -> count()}}</span></h4></caption>
  @endif

  <thead>
    <tr>
        <th>Nome</th>
        <th>Data de Nascimento</th>
        <th>Classe/Função</th>
        <th>Ação</th>
    </tr>
  </thead>

  <tbody>
      @foreach($pessoas as $p)
        <tr @if (in_array(\App\Http\Enums\FuncaoEnum::PROFESSOR->value, array_column($p->funcoes->toArray(), 'id')) || in_array(\App\Http\Enums\FuncaoEnum::SECRETARIO_ADMIN->value, array_column($p->funcoes->toArray(), 'id'))) style="background-color: #d95eff" @endif>
            <td>{{$p -> nome}}</td>
            <td @if (date('d/m', strtotime($p->data_nasc)) == date('d/m')) style="color: yellow; font-weight: bolder" @endif>{{date('d/m', strtotime($p -> data_nasc))}}</td>
            <td><ul>@foreach($p->salas as $key=>$sala) <li> {{ $sala->nome }} ({{ $p->funcoes[$key]['nome'] }})</li> @endforeach</ul></td>
            <td><div style="text-align: center">
                <a href="/admin/visualizar/pessoa/{{$p->id}}" style="text-decoration: none; color:black; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-show icon'></i> </a>
                </div>
            </td>
        </tr>
      @endforeach
  </tbody>

</table>
@endsection
