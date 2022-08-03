@extends('layouts.mainClasse')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/filtrosClasse.css">

 <div style="margin: 15px; color: white" >

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
</div>


    @if(isset($mes) || isset($ano))
  <div class="busca">
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


  </div>
  @else 
  <div class="busca">
    <p class="tit">Buscando por: <i class="result">Chamada de hoje</i></p> 
  </div>
  @endif

    <div class="cards">
      @foreach ($chamadas as $c)
    <div class="blog_post">
      <div class="container_copy">
        <h3>@if(date('d/m/Y', strtotime($c -> created_at)) == date(('d/m/Y'))) <span style="color: green">Hoje</span> @else {{date('d/m/Y', strtotime($c -> created_at))}} @endif </h3>
        <h1>{{$findSala -> nome}}</h1>
        <p>
          @if(date('d/m/Y', strtotime($c -> created_at)) != date(('d/m/Y')))
          
          No dia {{date('d/m/Y', strtotime($c -> created_at))}}, a classe
          <span style="color: red; font-weight: bold">{{$findSala -> nome}}</span> continha  <span style="color: red; font-weight: bold">{{$c -> matriculados}}</span> 
          matriculados, onde, 
          @if($c -> presentes > 1) <span style="color: red; font-weight: bold">{{$c -> presentes}}</span>
           se fizeram presentes. @elseif($c -> presentes == 1) <span style="color: red; font-weight: bold">1</span> se fez presente.
          @elseif($c -> presentes < 1)<span style="color: red; font-weight: bold">nenhum</span> se fez presente @endif
          @if($c -> visitantes > 0)Também foi recebido <span style="color: red; font-weight: bold">{{$c -> visitantes}}</span> visitante(s).@endif
          @if($c -> biblias > 0 || $c -> revistas > 0) Relacionado ao material, foi verificado que existia(m) <span style="color: red; font-weight: bold">{{$c -> biblias}}</span> Bíblia(s) e <span style="color: red; font-weight: bold">{{$c -> revistas}}</span> revista(s). @endif
          
          @else 

          Hoje, a classe <span style="color: red; font-weight: bold">{{$findSala -> nome}}</span> conteve <span style="color: red; font-weight: bold">{{$c -> matriculados}} </span>
          matriculados, onde, 
          @if($c -> presentes > 1) <span style="color: red; font-weight: bold">{{$c -> presentes}}</span>
             se fizeram presentes. @elseif($c -> presentes == 1) <span style="color: red; font-weight: bold">1</span> se fez presente.
          @elseif($c -> presentes < 1) <span style="color: red; font-weight: bold">nenhum</span> se fez presente @endif
          @if($c -> visitantes > 0)Também foi recebido <span style="color: red; font-weight: bold">{{$c -> visitantes}}</span> visitante(s).@endif
          @if($c -> biblias > 0 || $c -> revistas > 0) Relacionado ao material, foi verificado que existia(m) <span style="color: red; font-weight: bold">{{$c -> biblias}}</span> Bíblia(s) e <span style="color: red; font-weight: bold">{{$c -> revistas}}</span> revista(s). @endif
          
          @endif
        </p>
        <a class="btn_primary" href='/classe/visualizar-chamada/{{$c -> id}}'>Ver detalhes</a>
      </div>
    </div>
    @endforeach
  </div>

     
@endsection