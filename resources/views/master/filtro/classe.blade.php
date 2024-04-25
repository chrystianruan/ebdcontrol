@extends('layouts.mainMaster')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/filtrosPessoa.css">

 <div style="margin: 15px">

  <form action="/master/filtro/classe" method="POST">
  @csrf
  <div class="fields">

  <div class="itens">
  <legend class="title">Filtrar por: </legend>
  </div>

    <div class="itens">




    <select name="sala">
      <option selected disabled value="">Classe</option>
      @foreach($classes as $c)
      @if($c -> id > 2)
      <option value="{{$c -> id}}">{{$c -> nome}}</option>
      @endif
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

  @if(isset($salap))
    <p class="tit">Buscando por:</p>

    @if(isset($salap))
    <p class="it">Sala: <i class="result">@foreach($salas as $sala) @if($sala -> id == $salap) {{$sala -> nome}}  @endif @endforeach</i></p>
    @endif

    @else

    <p class="it">Buscando por: <i class="result">Tudo</i></p>

    @endif


  </div>



  <table style="margin:3%">

  @if($salas -> count() - 2 > 1)
  <caption class="cont"><h4>Classes: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$salas -> count() - 2}}</font></h4></caption>
  @endif

  <thead>
    <tr>
      <th>Nome
      <th>Tipo
      <th>Data cadastro
      <th>Ações
  </thead>
  @foreach($salas as $sala)
  @if($sala -> id > 2)
  <tbody>
    <tr> <!-- <tr class="disabled">  -->

      <td>{{$sala -> nome}}
      <td>{{$sala -> tipo}}
      <td>{{date('d/m/Y', strtotime($sala -> created_at))}}
      <td style="width: 130px"><div style="text-align: center">
            <a href="/master/edit/classe/{{$sala->id}}" style="text-decoration: none; color:#7B4EA5; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-edit icon'></i> </a>
{{--            <form action="/master/filtro/classe/{{$sala -> id}}" style="float:left; " method="POST">--}}
{{--            @csrf--}}
{{--            @method('DELETE')--}}
{{--            <button type="submit" style="border: none;color:#7B4EA5;font-size: 1em; background: none"><i style="font-size: 1.8em; margin: 1px; cursor:pointer; margin: 5px; float: left" class='bx bx-trash-alt icon'></i> </button>--}}
            </form> </div> </td>
</tr>

  </tbody>

  @endif
  @endforeach
</table>
@endsection
