@extends('layouts.main')

@section('title', 'Início')

@section('content')


@push('chamadas.admin.css')
    <link rel="stylesheet" href="{{ cacheBust('css/filtros.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/cards-list.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/formGroup.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/buttonsAdmin.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/modalAdmin.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/modalChamada.css') }}">
@endpush


<div class="container-intern">
    <input type="hidden" value="{{ encryptId(auth()->user()->congregacao_id)  }}" name="congregacao_id" id="congregacao-input">
    <div>
        <form action="/admin/chamadas" method="GET" id="form-filtro-chamadas">
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
                        <select name="mes" class="select" id="filtro-mes" required>
                            <option selected disabled value="">Mês *</option>
                            @foreach($meses_abv as $num => $nome)
                              <option value="{{$num}}">{{$nome}} ({{$num}})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="ano" class="select" id="filtro-ano" required>
                            <option selected disabled value="">Ano *</option>
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
          Realizar Chamada
          <i class="bx bx-list-check" style="font-size: 1.3em;"></i>
      </button>
      <button class="btn btn-secondary" onclick="openModalChamadaFisica()">
          Gerar Chamada Física
          <i class="bx bx-printer" style="font-size: 1.3em;"></i>
      </button>
    </div>

    <div class="table-container">

    @php
        $totalChamadas = 0;
        foreach($chamadas as $date => $items) {
            $totalChamadas += count($items);
        }
    @endphp

    @if($totalChamadas > 0)

        <div class="cards-list-header">
            <h3>{{ $totalChamadas }} {{ $totalChamadas == 1 ? 'chamada encontrada' : 'chamadas encontradas' }}</h3>
        </div>

        @foreach($chamadas as $date => $items)
            @php
                $dateFormatted = str_replace('-', '/', $date);
                $isDateToday = $dateFormatted == date('d/m/Y');
                $carbonDate = \Carbon\Carbon::createFromFormat('d-m-Y', $date);
                $diaSemana = [
                    0 => 'Domingo', 1 => 'Segunda-feira', 2 => 'Terça-feira',
                    3 => 'Quarta-feira', 4 => 'Quinta-feira', 5 => 'Sexta-feira', 6 => 'Sábado'
                ][$carbonDate->dayOfWeek];
            @endphp

            <div class="date-group">
                <div class="date-divider">
                    <div class="date-divider-label">
                        <i class='bx bx-calendar'></i>
                        <span>{{ $dateFormatted }}</span>
                        <span class="date-divider-day">{{ $diaSemana }}</span>
                        @if($isDateToday)
                            <span class="badge-hoje">Hoje</span>
                        @endif
                        <span class="date-divider-count">{{ count($items) }} {{ count($items) == 1 ? 'chamada' : 'chamadas' }}</span>
                    </div>
                </div>

                <div class="cards-grid">
                    @foreach($items as $c)
                        @php
                            $percentual = $c->matriculados > 0 ? round(100 * $c->presentes / $c->matriculados, 1) : 0;
                            $barClass = $percentual >= 70 ? 'high' : ($percentual >= 40 ? 'medium' : 'low');
                            $nomeSala = '';
                            foreach($salas as $s) {
                                if($s->id == $c->id_sala) {
                                    $nomeSala = $s->nome;
                                    break;
                                }
                            }
                        @endphp
                        <div class="list-card">
                            <div class="list-card-header">
                                <div>
                                    <span class="list-card-title">{{ $nomeSala }}</span>
                                </div>
                                <div class="list-card-actions">
                                    <button class="card-action-view" title="Visualizar" onclick="openModalVisualizarChamada({{ $c->id }})">
                                        <i class='bx bx-show'></i>
                                    </button>
                                    <a href="/admin/visualizar/pdf-chamada/{{ $c->id }}" target="_blank" class="card-action-pdf" title="Baixar PDF">
                                        <i class='bx bxs-file-pdf'></i>
                                    </a>
                                </div>
                            </div>

                            <div class="card-bar-container">
                                <div class="card-bar {{ $barClass }}" style="width: {{ $percentual }}%"></div>
                            </div>

                            <div class="list-card-body">
                                <div class="card-metric">
                                    <span class="card-metric-value presenca">{{ number_format($percentual, 1, ',', '.') }}%</span>
                                    <span class="card-metric-label">Presença</span>
                                </div>
                                <div class="card-metric">
                                    <span class="card-metric-value">{{ $c->presentes }}/{{ $c->matriculados }}</span>
                                    <span class="card-metric-label">Presentes</span>
                                </div>
                                <div class="card-metric">
                                    <span class="card-metric-value visitantes">+{{ $c->visitantes }}</span>
                                    <span class="card-metric-label">Visitantes</span>
                                </div>
                                <div class="card-metric">
                                    <span class="card-metric-value">{{ $c->presentes + $c->visitantes }}</span>
                                    <span class="card-metric-label">Assist. Total</span>
                                </div>
                            </div>

                            @if($c->observacoes)
                                <div class="list-card-footer">
                                    <span class="badge-obs" title="{{ $c->observacoes }}">
                                        <i class='bx bx-message-detail'></i>
                                        {{ Str::limit($c->observacoes, 40) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    @else
        <div class="cards-empty">
            <i class='bx bx-list-ul'></i>
            <p>Nenhuma chamada encontrada</p>
            <p class="cards-empty-sub">Tente ajustar os filtros ou esperar alguma chamada ser realizada</p>
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

@include('templates.modal-admin-template', [
    'modalId' => 'modalVisualizarChamada',
    'modalTitle' => 'Visualizar Chamada',
    'modalBody' => 'templates.visualizar-chamada-modal-template',
    'closeModal' => 'closeModalVisualizarChamada()',
    'actionButton' => '',
    'modalClass' => 'modal-wide',
])

@include('templates.modal-admin-template', [
    'modalId' => 'modalChamadaFisica',
    'modalTitle' => 'Gerar Chamada Física',
    'modalBody' => 'templates.chamada-fisica-modal-template',
    'closeModal' => 'closeModalChamadaFisica()',
    'actionButton' => 'gerarChamadaFisica()',
    'actionButtonLabel' => '<i class="bx bx-printer" style="font-size: 1.2em;"></i> Gerar',
    'modalClass' => '',
])

@push('chamadas.admin.script')
    <script src="{{ cacheBust('js/modalChamada.js') }}"></script>
    <script src="{{ cacheBust('js/modalVisualizarChamada.js') }}"></script>
    <script src="{{ cacheBust('js/modalChamadaFisica.js') }}"></script>
    <script>
        document.getElementById('form-filtro-chamadas').addEventListener('submit', function(e) {
            var mes = document.getElementById('filtro-mes');
            var ano = document.getElementById('filtro-ano');
            var missing = [];

            mes.style.border = '';
            ano.style.border = '';

            if (!mes.value) missing.push(mes);
            if (!ano.value) missing.push(ano);

            if (missing.length > 0) {
                e.preventDefault();
                missing.forEach(function(el) {
                    el.style.border = '2px solid #dc2626';
                });
                alert('Para filtrar, informe o Mês e o Ano.');
            }
        });
    </script>
    @if(session('msg'))
        <script>alert('{{ session('msg') }}');</script>
    @endif
    @if(session('msg2'))
        <script>alert('{{ session('msg2') }}');</script>
    @endif
@endpush

@endsection
