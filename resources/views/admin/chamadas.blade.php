@extends('layouts.main')

@section('title', 'Início')

@section('content')


@push('chamadas.admin.css')
    <link rel="stylesheet" href="/css/filtros.css">
    <link rel="stylesheet" href="/css/formGroup.css">
    <link rel="stylesheet" href="/css/buttonsAdmin.css">
    <link rel="stylesheet" href="/css/modalAdmin.css">
@endpush


<div class="container-intern">
    <div>
      <form action="/admin/chamadas" method="GET">

      <div class="fields">
          <div class="filter-header">
              <span class="title">Filtros: </span>
          </div>

          <div class="itens">
              <div>
                <select name="classe" class="select">
                    <option selected disabled value="">Classe</option>
                      @foreach($salas as $s)
                          <option value="{{$s -> id}}">{{$s -> nome}}</option>
                      @endforeach
                </select>
              </div>

              <div>
                 <select name="mes" class="select">
                    <option selected disabled value="">Mês</option>
                      @foreach($meses_abv as $num => $nome)
                          <option value="{{$num}}">{{$nome}} ({{$num}})</option>
                      @endforeach

                  </select>
              </div>

              <div>
                  <select name="ano" class="select">
                    <option selected disabled value="">Ano</option>
                      @for($i = 2022; $i <= date('Y'); $i++)
                          <option value="{{$i}}">{{$i}}</option>
                      @endfor

                  </select>
              </div>
          </div>
          <div class="div-buttons-filter">
              <div class="btnFilter">
                  <button type="submit" class="btn btn-secondary">Filtrar</button>
              </div>

              <div class="btnFilter">
                  <button type="reset" class="btn btn-danger">Limpar</button>
              </div>
          </div>
      </div>
      </form>
    </div>
</div>

<div class="busca">
    @if(isset($classe) || isset($mes) || isset($ano))
    <p class="tit">Buscando por:</p>

    @if(isset($classe))
    <li class="ponto">Classe:
        <i class="result">@foreach ($salas as $s)  @if($classe == $s -> id) {{$s->nome}} @endif @endforeach</i>
    </li>
    @endif

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
    <p class="tit">Buscando por: <i class="result">Chamadas de hoje</i></p>
    @endif
</div>

<div class="div-btn-register">
  <button class="btn btn-primary" onclick="openModalRegister()">Realizar Chamada <i class="bx bx-list-plus" style="font-size: 1.5em; padding-left: 10px"></i> </button>
</div>

<div class="table-container">

@if($chamadas->count() > 0)
<div class="table-wrapper">
  <table>
{{--  @if($chamadas -> count() > 1)--}}
{{--  <caption class="cont"><h4>Chamadas: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$chamadas -> count()}}</font></h4></caption>--}}
{{--  @endif--}}
      <thead>
        <tr>
          <th>Classe</th>
          <th>Data</th>
          <th style="text-align:center">Matriculados</th>
          <th style="text-align:center">Presentes</th>
          <th style="text-align:center">Visitantes</th>
          <th style="text-align:center">Assist. Total</th>
          <th style="text-align:center">Bíblias</th>
          <th style="text-align:center">Revistas</th>
          <th style="text-align:center">Observações</th>
          <th>Ação</th>
        </tr>
      </thead>
    @foreach($chamadas as $c)
      <tbody>
        <tr>
          <td style="font-weight: bold; color: yellow">
            @foreach($salas as $s)
                @if($s -> id == $c -> id_sala)
                    {{$s -> nome}}
                @endif
            @endforeach
          </td>
          <td>
              @if(date('d/m/Y', strtotime($c -> created_at)) == date('d/m/Y'))
                  <span style="background-color: red; padding: 3px; border-radius: 5px; font-weight: bold">Hoje!</span>
              @else
                {{date('d/m/Y', strtotime($c -> created_at))}}
              @endif
          </td>
          <td style="text-align:center">{{$c -> matriculados}}</td>
          <td style="text-align:center">{{$c -> presentes}}</td>
          <td style="text-align:center">{{$c -> visitantes}}</td>
          <td style="text-align:center">{{ $c->presentes + $c->visitantes }}</td>
          <td style="text-align:center">{{$c -> biblias}}</td>
          <td style="text-align:center">{{$c -> revistas}}</td>
          <td style="text-align:center">
              @if($c->observacoes)
                  <i class='bx bx-message-error' style="color:red; font-size: 1.3em"></i>
              @endif
          </td>
           <td>
               <a href="/admin/visualizar/chamada/{{$c->id}}" style="text-decoration: none; color:black; margin: 5px;"><i style="font-size: 1.8em;margin: 1px;" class='bx bx-show icon'></i> </a>
               <a href="/admin/visualizar/pdf-chamada/{{$c->id}}" style="text-decoration: none; color:black; margin: 5px;"><i style="font-size: 1.8em;margin: 1px;" class='bx bxs-file-pdf'></i> </a>
           </td>
        </tr>
      </tbody>
    @endforeach
    </table>
  </div>
    <div class="pagination-container">
        <div class="pagination-info">
            <strong>{{ $chamadas->count() * $chamadas->currentPage() }}</strong> de <strong> {{ $chamadas->total() }}</strong>
        </div>

        <ul class="pagination pagination-minimal">
            @if(!$chamadas->onFirstPage())
                <li>
                    <a href="{{ $chamadas->previousPageUrl() }}" class="pagination-btn pagination-btn-prev">
                        <i class="fas fa-chevron-left"></i>
                        <span>Anterior</span>
                    </a>
                </li>
            @endif

            @php
                $atual = $chamadas->currentPage();
                $ultima = $chamadas->lastPage();

                // Calcular range de 5 páginas ao redor da atual
                $inicio = max(1, $atual - 2);
                $fim = min($ultima, $atual + 2);

                // Ajustar para sempre ter 5 páginas no meio (quando possível)
                if ($fim - $inicio < 4) {
                    if ($atual < 3) {
                        $fim = min($ultima, 5);
                    } else {
                        $inicio = max(1, $ultima - 4);
                    }
                }
            @endphp

            @if ($inicio > 1)
                <li>
                    <a href="{{ $chamadas->url(1) }}" class="pagination-btn @if($atual == 1) active @endif">1</a>
                </li>

                @if ($inicio > 2)
                    <li><span class="pagination-ellipsis">...</span></li>
                @endif
            @endif

            @for ($page = $inicio; $page <= $fim; $page++)
                <li><a href="{{ $chamadas->url($page) }}" class="pagination-btn @if($page == $atual) active @endif">{{ $page }}</a></li>
            @endfor

            @if ($fim < $ultima)
                @if ($fim < $ultima - 1)
                    <li><span class="pagination-ellipsis">...</span></li>
                @endif

                <li>
                    <a href="{{ $chamadas->url($ultima) }}" class="pagination-btn @if($page == $atual) active @endif">{{ $ultima }}</a>
                </li>
            @endif

            @if ($chamadas->hasMorePages())
                <li>
                    <a href="{{ $chamadas->nextPageUrl() }}" class="pagination-btn pagination-btn-next">
                        <span>Próximo</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @endif
        </ul>
    </div>
@else
            <div class="table-header">
                <h3 class="table-title">Chamadas</h3>
                <span class="table-count">0 registros</span>
            </div>
            <div class="table-empty">
                <div class="table-empty-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="table-empty-text">Nenhuma chamada encontrada</div>
                <div class="table-empty-subtext">Tente ajustar os filtros ou esperar alguma chamada ser realizada</div>
            </div>

@endif
</div>
@endsection
