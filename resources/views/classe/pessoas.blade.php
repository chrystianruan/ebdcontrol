@extends('layouts.mainClasse')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/filtrosPessoa.css">


  <div style="margin: 15px">

<form action="/classe/pessoas" method="POST">
  @csrf

  <div class="fields">
  <div class="itens">
  <legend class="title">Filtrar por: </legend>
  </div>
  <font style=";margin-left: 15px;font-size: 12px; color: white">(O filtro <mark>Nome</mark> é exclusivo. Portanto, para funcionar corretamente, não poderá ser usado com outros filtros)</font>


    <div class="itens">
    <input type="text" name="nome" placeholder="Digite o nome da pessoa">

    <select name="sexo">
    <option selected disabled value="">Sexo</option>
    <option value="1">Masculino</option>
    <option value="2">Feminino</option>

    </select>

    <select name="niver">
      <option selected disabled value="">Aniversário</option>
      @foreach($meses_abv as $val => $name)
      <option value="{{$val}}">{{$val}} - {{$name}}</option>
      @endforeach

    </select>

    <select name="interesse">
      <option selected disabled value="">Interesse Professor</option>
      <option value="1">Sim</option>

    </select>

    <select name="id_funcao">
    <option selected disabled value="">Função</option>
    <option value="1">Aluno</option>
    <option value="2">Professor</option>
    <option value="3">Secretário/Classe</option>
    <option value="4">Secretário/Adm</option>
    <option value="5">Superintendente</option>

    </select>

    <select name="situacao">
    <option selected disabled value="">Situação</option>
    <option value="1">Ativo</option>
    <option value="2">Inativo</option>

    </select>


    <div class="btnFilter">
    <button type="submit" class="filter">Filtrar</button>
    </div>

    <div class="btnFilter">
    <button type="reset" class="resett">Limpar tudo</button>
    </div>
    </div>
  </div>
  </div>
  </form>
  <div class="busca">

@if(isset($nome) || isset($sexo) || isset($id_funcao) || isset($situacao) || isset($interesse))
  <p class="tit">Buscando por: @if(isset($nome) && (isset($sexo) || isset($id_funcao) || isset($situacao)))<i class="result">Tudo</i> @endif</p>
  @if(isset($nome) && empty($sexo) && empty($id_funcao) && empty($situacao))
   <li class="ponto">Nome: <i class="result">{{$nome}}</i></li>
  @endif

  @if(isset($sexo) && empty($nome))
  <li class="ponto">Sexo: <i class="result">@if($sexo == 1) Masculino @else Feminino @endif</i></li>
  @endif

  @if(isset($niver) && empty($nome))
    <li class="ponto">Aniversário: <i class="result">@foreach($meses_abv as $num => $mes) @if($niver == $num) {{$num}} - {{$mes}} @endif @endforeach</i></li>
  @endif

  @if(isset($id_funcao) && empty($nome))
  <li class="ponto">Função: <i class="result">@if($id_funcao == 1) Aluno @elseif($id_funcao == 2) Professor @elseif($id_funcao == 3) Secretário/Classe @elseif($id_funcao == 4) Secretário/Adm @elseif($id_funcao == 5) Superintendente @else Erro @endif</i></li>
  @endif

  @if(isset($situacao) && empty($nome))
  <li class="ponto">Situação: <i class="result">@if($situacao == 1) Ativo @else Inativo @endif</i></li>
  @endif

  @if(isset($interesse) && empty($nome))
  <li class="ponto">Interesse: <i class="result">Sim</i></li>
  @endif

  </div>
  @else

  <p class="tit">Buscando por: <i class="result">Tudo</i></p>

  @endif


</div>

  </div>

  @if($pessoas->count() > 0)
  <table style="margin:3%">
  <caption class="cont"><h4>Qntd.: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$pessoas -> count()}}</font></caption>
  <thead>
    <tr>
      <th>Nome
      <th style="text-align: center">N° de telefone
      <th>Ação
  </thead>
  @foreach($pessoas as $pessoa)
  <tbody>

    <tr @if($pessoa -> situacao == 2) class="disabled" @endif>
      <td style="width: 350px">{{$pessoa -> nome}}
      <td  style="@if($pessoa -> telefone == null)color: gray;@endif text-align: center" >
          @if($pessoa -> telefone == null)
          -
          @else
          <a class="link-wpp" href="https://api.whatsapp.com/send?phone=55{{ $pessoa->telefone }}" target="blank"> {{$pessoa -> telefone}} </a>
          @endif
      <td> <a href="/classe/visualizar-pessoa/{{$pessoa->id}}" style="text-decoration: none; color:#7B4EA5; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left; color: #7B4EA5" class='bx bx-show icon'></i> </a> </td>
    </tr>

  </tbody>
  @endforeach
</table>
@else
<div class="ngm">
  <p><i class='fa fa-exclamation-triangle'></i>Não há pessoas cadastradas para os filtros escolhidos!</p>
</div>
@endif


@endsection
