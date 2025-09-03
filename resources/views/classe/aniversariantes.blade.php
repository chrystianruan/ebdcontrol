@extends('layouts.mainClasse')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/filtrosPessoa.css">

 <div style="margin: 15px">

  <form action="/classe/aniversariantes" method="POST">
  @csrf
  <div class="fields">

  <div class="itens">
  <legend class="title">Filtrar por: </legend>
  </div>

    <div class="itens">


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

  @if(isset($mes))
    <p class="tit">Buscando por:</p>

    @if(isset($mes))
    <p class="it">Mês: <i class="result"> @foreach($meses_abv as $val => $nome) @if($val == $mes) {{$val}} - {{$nome}} @endif @endforeach</i></p>
    @endif

    @else

    <p class="it">Buscando por: <i class="result">Aniversariantes do mês atual ({{date('m')}})</i></p>

    @endif


  </div>



  <table style="margin:3%">

  @if($pessoas -> count() > 1)
  <caption class="cont"><h4>Pessoas: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$pessoas -> count()}}</font></h4></caption>
  @endif

  <thead>
    <tr>
      <th>Nome</th>
        <th>Número de Telefone</th>
      <th>Idade</th>
        <th>Função</th>
      <th>Ação</th>
    </tr>
  </thead>
  @foreach($pessoas as $p)

  <tbody>
    <tr @if ($p->id_funcao == 2 || $p->id_funcao == 4 || $p->id_funcao == 5) style="background-color: rgba(59,52,52,0.73)" @endif>

        <td>{{$p -> nome}}</td>
        <td>
        @if($p->telefone == null && $p->telefone_responsavel == null)
            -
        @else
            <a class="link-wpp" href="https://api.whatsapp.com/send?phone=55{{ $p->responsavel ? $p->telefone_responsavel : $p->telefone  }}" target="blank">
                {{ $p->responsavel ? $p->telefone_responsavel : $p->telefone }}
            </a>
            {{ $p->responsavel ? "(Responsável)" : "" }}
        @endif
        </td>
      <td>
          @if(floor((strtotime(date('Y-m-d')) - strtotime($p -> data_nasc))/(60 * 60 * 24) /365.25) < 2)
        {{floor((strtotime(date('Y-m-d')) - strtotime($p -> data_nasc))/(60 * 60 * 24) /365.25)}} ano <span style="font-weight: bold;color: orange">({{date('d/m', strtotime($p -> data_nasc))}})</span>
        @else
        {{floor((strtotime(date('Y-m-d')) - strtotime($p -> data_nasc))/(60 * 60 * 24) /365.25)}} anos <span style="font-weight: bold;color: orange">({{date('d/m', strtotime($p -> data_nasc))}})</span>
        @endif
      </td>
        <td>{{ $p->nome_funcao }}</td>
      <td><div style="text-align: center">
            <a href="/classe/visualizar-pessoa/{{$p->id}}" style="text-decoration: none; color:black; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left; color: #7B4EA5" class='bx bx-show icon'></i> </a>
          </div>
      </td>

    </tr>
  </tbody>

  @endforeach
</table>
@endsection
