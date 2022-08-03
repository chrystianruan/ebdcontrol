@extends('layouts.mainMaster')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/filtrosPessoa.css">

 <div style="margin: 15px">

  <form action="/master/filtro/usuario" method="POST">
  @csrf
  <div class="fields">
    
  <div class="itens">
  <legend class="title">Filtrar por: </legend>
  </div>
  
    <div class="itens">
      <input type="text" name="nome" placeholder="Nome do user">
    
      <select name="status">
        <option selected disabled value="">Status</option>
            <option value="on">Ativo</option>
            <option value=1>Inativo</option>
  
      </select>
    
    <select name="nivel">
      <option selected disabled value="">Nível</option>
        @foreach($niveis as $n)
            <option value="{{$n -> id}}">{{$n -> nome}}</option>
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



@if(isset($nome) || isset($nivel) || isset($status))
<div class="busca">
  <p class="tit">Buscando por:</p> 

  @if(isset($nome) && empty($nivel) && empty($status))
  <li class="ponto">Nome: 
      <i class="result"> {{$nome}} </i>
  </li>
  @endif

  @if(isset($nivel) && empty($nome))
  <li class="ponto">Nível: 
      <i class="result">@foreach($niveis as $n) @if($n -> id == $nivel) {{$n -> nome}} @endif @endforeach</i>
  </li>
  @endif

  @if(isset($status) && empty($nome))
  <li class="ponto">Status: 
      <i class="result"> @if($status == 'on') Ativo @elseif($status == 1) Inativo @endif</i>
  </li>
  @endif


</div>
@else 
<div class="busca">
  <p class="tit">Buscando por:<i class="result">Tudo</i></p> 
</div>
@endif

  

  <table style="margin:3%">

  @if($users -> count() > 1)
  <caption class="cont"><h4>Usuários: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$users -> count()}}</font></h4></caption>
  @endif

  <thead>
    <tr>
      <th>Nome
      <th>Username
      <th>Nível
      <th>Status
      <th style="center">Ações
  </thead>
  @foreach($users as $u)

  <tbody>
    <tr> <!-- <tr class="disabled">  -->
     
      <td>{{$u -> name}}
      <td>{{$u -> username}}
      <td>@foreach($niveis as $n) @if($n->id == $u->id_nivel) {{$n -> nome}} @endif @endforeach
      <td>@if($u->status == false) <font style="padding: 2px; border-radius: 3px; background-color: green">Ativo</font> @else <font style="padding: 2px; border-radius: 3px;background-color: red">Inativo</font>@endif
      <td>
            <a href="/master/edit/usuario/{{$u->id}}" style="text-decoration: none; color:#7B4EA5; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-edit icon'></i> </a> 
            <a href="/master/edit/usuario-senha/{{$u->id}}" style="text-decoration: none; color:#7B4EA5; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-lock icon'></i> </a>     
             </td>
    </tr>
      
  </tbody>

  @endforeach
</table>
@endsection