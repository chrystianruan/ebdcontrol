@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/filtros.css">


  <div style="margin: 15px">

<form action="/admin/filtro/pessoa" method="POST">
  @csrf
 
  <div class="fields">
  <div class="itens">
  <legend class="title">Filtrar por: </legend>
  </div>
    
    
    <div class="itens">
    <input type="text" name="nome" placeholder="Digite o nome da pessoa">

    <select name="sexo">
    <option selected disabled value="">Sexo</option>
    <option value="1">Masculino</option>
    <option value="2">Feminino</option>

    </select>
    
    <select name="sala">
      <option selected disabled value="">Classe</option>
      @foreach($salas as $sala)
      @if($sala -> id > 2)
        <option value="{{$sala -> id}}">{{$sala -> nome}} - {{$sala -> tipo}}</option>
      @endif
      @endforeach
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

@if(isset($nome) || isset($sexo) || isset($id_funcao) || isset($situacao) || isset($sala1))
  <p class="tit">Buscando por:</p> 
  @if(isset($nome))
   <li class="ponto">Nome: <i class="result">{{$nome}}</i></li>
  @endif

  @if(isset($sexo) && empty($nome))
  <li class="ponto">Sexo: <i class="result">@if($sexo == 1) Masculino @else Feminino @endif</i></li>
  @endif

  @if(isset($sala1) && empty($nome))
  <li class="ponto">Classe: <i class="result">@foreach($salas as $sala)
          @if($sala -> id == $sala1)
          {{$sala -> nome}}
          @endif
          @endforeach</i></li>
  @endif

  @if(isset($id_funcao) && empty($nome))
  <li class="ponto">Função: <i class="result">@if($id_funcao == 1) Aluno @elseif($id_funcao == 2) Professor @elseif($id_funcao == 3) Secretário/Classe @elseif($id_funcao == 4) Secretário/Adm @elseif($id_funcao == 5) Superintendente @else Erro @endif</i></li>
  @endif

  @if(isset($situacao) && empty($nome))
  <li class="ponto">Situação: <i class="result">@if($situacao == 1) Ativo @else Inativo @endif</i></li>
  @endif

  </div>
  @else

  <p class="tit">Buscando por: <i class="result">Tudo</i></p> 

  @endif


</div>

  </div> 

  @if($pessoas->count() > 0)
  <table style="margin:3%">
  <caption class="cont"><h4>Matriculados: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$pessoas -> count()}}</font></caption>
  <thead>
    <tr>
      <th>Nome
      <th>Idade
      <th>Data Nascimento
      <th>Sexo
      <th>N° de telefone
      <th>Sala
      <th>Função

      <th style="text-align: center">Ações
  </thead>
  @foreach($pessoas as $pessoa)
  <tbody>
    
    <tr @if($pessoa -> situacao == 2) class="disabled" @endif> 
      <td style="width: 350px">{{$pessoa -> nome}}
      <td  style="width: 100px">@if(floor((strtotime($dataAtual) - strtotime($pessoa -> data_nasc))/(60 * 60 * 24) /365.25) < 2) 
        {{floor((strtotime($dataAtual) - strtotime($pessoa -> data_nasc))/(60 * 60 * 24) /365.25)}} ano
        @else
        {{floor((strtotime($dataAtual) - strtotime($pessoa -> data_nasc))/(60 * 60 * 24) /365.25)}} anos
        @endif
      <td>{{date('d/m/Y', strtotime($pessoa -> data_nasc))}}
      <td>@if($pessoa -> sexo == 1)
          M
          @elseif($pessoa -> sexo == 2)
          F
          @else
          Erro
          @endif
      <td @if($pessoa -> telefone == null) style="color: gray; text-align: center" @endif>
          @if($pessoa -> telefone == null)
          -
          @else 
          {{$pessoa -> telefone}}
          @endif
       
      <td style="width: 180px">
        @foreach($pessoa->id_sala as $id_sal)
              @foreach($salas as $sal)
                  @if($sal -> id == $id_sal)
                   <li> {{$sal->nome}}</li>
                  @endif
              @endforeach
          @endforeach
      <td>@if($pessoa -> id_funcao == 1) Aluno @elseif($pessoa -> id_funcao == 2) Professor @elseif($pessoa -> id_funcao == 3) Secretário/Classe @elseif($pessoa -> id_funcao == 4) Secretário/Adm @elseif($pessoa -> id_funcao == 5) Superintendente @else Erro @endif
      
           
            <td style="min-width:170px;"><div style="text-align: center">
            <a href="/admin/visualizar/pessoa/{{$pessoa->id}}" style="text-decoration: none; color:black; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-show icon'></i> </a>
            <a href="/admin/edit/pessoa/{{$pessoa->id}}" style="text-decoration: none; color:black; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-edit icon'></i> </a> 
            
            <form action="/admin/filtro/pessoa/{{$pessoa -> id}}" style="float:left; " method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="funcao1()" style="border: none; font-size: 1em; background: none"><i style="font-size: 1.8em; margin: 1px; cursor:pointer; margin: 5px; float: left" class='bx bx-trash-alt icon'></i> </button>
            </form> </div> </td>
    </tr>
    
  </tbody>
  @endforeach  
</table>
@else
<div class="ngm">
  <p ><i class='bx bx-stop'></i>Não há pessoas cadastradas para os filtros escolhidos!</p>
</div>
@endif
<script>

  function funcao1()
  {
    var x;
    var r=confirm("Deseja mesmo excluir essa pessoa?");
    if (r==true)
      {
      x="você pressionou OK!";
      }
    else
      {
      x="Você pressionou Cancelar!";
      }
    document.getElementById("demo").innerHTML=x;
  }
  </script>

@endsection

