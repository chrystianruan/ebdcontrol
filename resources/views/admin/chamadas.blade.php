@extends('layouts.main')

@section('title', 'Início')

@section('content')


@push('chamadas.admin.css')
    <link rel="stylesheet" href="{{ cacheBust('css/filtros.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/formGroup.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/buttonsAdmin.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/modalAdmin.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/modalChamada.css') }}">
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
  <button class="btn btn-primary" onclick="openModalRealizarChamada()">
      <i class="bx bx-list-check" style="font-size: 1.3em;"></i>
      Realizar Chamada
  </button>
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
          <th>Matriculados</th>
          <th>Presentes</th>
          <th>Visitantes</th>
          <th>Assist. Total</th>
          <th>Bíblias</th>
          <th>Revistas</th>
          <th>Observações</th>
          <th>Ação</th>
        </tr>
      </thead>
      <tbody>
          @foreach($chamadas as $c)
            <tr>
              <td>
                <strong>
                @foreach($salas as $s)
                    @if($s -> id == $c -> id_sala)
                        {{$s -> nome}}
                    @endif
                @endforeach
                </strong>
              </td>
              <td>
                  @if(date('d/m/Y', strtotime($c -> created_at)) == date('d/m/Y'))
                      <span class="status-badge active">Hoje!</span>
                  @else
                    {{date('d/m/Y', strtotime($c -> created_at))}}
                  @endif
              </td>
              <td>{{$c -> matriculados}}</td>
              <td>{{$c -> presentes}}</td>
              <td>{{$c -> visitantes}}</td>
              <td>{{ $c->presentes + $c->visitantes }}</td>
              <td>{{$c -> biblias}}</td>
              <td>{{$c -> revistas}}</td>
              <td>
                  @if($c->observacoes)
                      <span class="status-badge pending" title="{{ $c->observacoes }}">
                          <i class='bx bx-message-error'></i>
                      </span>
                  @endif
              </td>
              <td>
                  <div class="table-actions">
                      <a href="/admin/visualizar/chamada/{{$c->id}}" class="action-btn action-btn-view" title="Visualizar">
                          <i class='bx bx-show'></i>
                      </a>
                      <a href="/admin/visualizar/pdf-chamada/{{$c->id}}" class="action-btn action-btn-pdf" title="Baixar PDF">
                          <i class='bx bxs-file-pdf'></i>
                      </a>
                  </div>
              </td>
            </tr>
          @endforeach
      </tbody>
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

@include('templates.modal-admin-template', [
    'modalId' => 'modalRealizarChamada',
    'modalTitle' => 'Realizar Chamada',
    'modalBody' => 'templates.realizar-chamada-modal-template',
    'closeModal' => 'closeModalRealizarChamada()',
    'actionButton' => ($isDiaChamada && count($classesFaltantes) > 0) ? 'submitRealizarChamada()' : '',
    'modalClass' => 'modal-wide',
])

@push('chamadas.admin.script')
    <script src="{{ cacheBust('js/modalChamada.js') }}"></script>
    @if(session('msg'))
        <script>alert('{{ session('msg') }}');</script>
    @endif
    @if(session('msg2'))
        <script>alert('{{ session('msg2') }}');</script>
    @endif
@endpush

@endsection
