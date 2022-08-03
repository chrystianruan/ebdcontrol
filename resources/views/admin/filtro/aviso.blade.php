@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/filtros.css">

 <div style="margin: 15px">

  <form action="/admin/filtro/aviso" method="POST">
  @csrf
  <div class="fields">
    
  <div class="itens">
  <legend class="title">Filtrar por: </legend>
  </div>
  
    <div class="itens">
    
    

    
    <select name="destinatario">
      <option selected disabled value="">Destinatário</option>
      @foreach($destinatarios as $d)
      <option value="{{$d -> id}}">{{$d -> nome}}</option>
      @endforeach

    </select>

    <select name="importancia">
      <option selected disabled value="">Importância</option>
      @foreach($importancias as $val => $nome)
      <option value="{{$val}}">{{$nome}}</option>
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

  @if(isset($destEnv))
    <p class="tit">Buscando por:</p> 

    @if(isset($destEnv))
    <p class="it">Destinatário: <i class="result">@foreach($destinatarios as $d) @if($d -> id == $destEnv) {{$d-> nome}}  @endif @endforeach</i></p>
    @endif

    @else

    <p class="it">Buscando por: <i class="result">Tudo</i></p> 

    @endif


  </div>

  

  <table style="margin:3%">

  @if($avisos -> count() > 1)
  <caption class="cont"><h4>Salas: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$avisos -> count()}}</font></h4></caption>
  @endif

  <thead>
    <tr>
      <th>Título
      <th>Descrição
      <th>Data de Post
      <th>Destinatário
      <th  style="text-align: center">Importância
      <th>Ações
  </thead>
  @foreach($avisos as $a)

  <tbody>
    <tr> <!-- <tr class="disabled">  -->
     
      <td>{{$a -> titulo}}
      <td>@if(strlen($a->descricao) > 100) {{substr($a -> descricao, 0, 100)}}... @else {{substr($a -> descricao, 0, 37)}}  @endif
      <td>{{date('d/m/Y', strtotime($a -> data_post))}}
      <td>@if($a -> destinatario == 4915) Todos  @else @foreach ($destinatarios as $d) @if($d -> id == $a -> destinatario) {{$d->nome}} @endif @endforeach @endif
      <td style="text-align: center"> <i  style="color: @if($a -> importancia == 1)red; @elseif($a -> importancia == 2) yellow; @else blue; @endif" class="fa fa-circle"></i>
      <td><div style="text-align: center">
            <a href="/admin/edit/aviso/{{$a->id}}" style="text-decoration: none; color:black; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-edit icon'></i> </a>   
            <form action="/admin/filtro/aviso/{{$a -> id}}" style="float:left; " method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" style="border: none; font-size: 1em; background: none"><i style="font-size: 1.8em; margin: 1px; cursor:pointer; margin: 5px; float: left" class='bx bx-trash-alt icon'></i> </button>
            </form> </div> </td>
    </tr>
      
  </tbody>

  @endforeach
</table>
@endsection