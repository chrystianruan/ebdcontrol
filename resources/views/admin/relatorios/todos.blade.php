@extends('layouts.main')

@section('title', 'Início')

@section('content')
<link rel="stylesheet" href="{{ cacheBust('css/filtros.css') }}">
<link rel="stylesheet" href="{{ cacheBust('css/cards-list.css') }}">
<link rel="stylesheet" href="{{ cacheBust('css/formGroup.css') }}">
<link rel="stylesheet" href="{{ cacheBust('css/buttonsAdmin.css') }}">
<div class="container-intern">
    <div>
        <form action="/admin/relatorios" method="GET" id="form-filtro-relatorios">
            <div class="fields">
                <div class="filter-header">
                    <span class="title">Filtros: </span>
                </div>
                <div class="itens">
                    <div>
                        <select name="mes" class="select" id="filtro-rel-mes" required>
                            <option selected disabled value="">Mês *</option>
                            @foreach($meses_abv as $num => $nome)
                                <option value="{{$num}}">{{$nome}} ({{$num}})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="ano" class="select" id="filtro-rel-ano" required>
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
        <p class="tit">Buscando por:
            <i class="result">
                @foreach($meses_abv as $num => $nome)
                    @if($mes == $num) {{$nome}} @endif
                @endforeach
                / {{$ano}}
            </i>
        </p>
    </div>

    @if(date('w') == 0 || date('Y-m-d') == $dateChamadaDia)
        @if($chamadas->count() != $salas->count())
            <div class="orientation">
                <div class="aaa">
                    @if($salas->count() - $chamadas->count() == $salas->count())
                        <p><i class='bx bx-info-circle' style="font-size: 1.1em; vertical-align: middle;"></i> Nenhuma classe enviou a chamada hoje.</p>
                    @elseif($salas->count() - $chamadas->count() != $salas->count() && $salas->count() - $chamadas->count() != 0)
                        <p><i class='bx bx-info-circle' style="font-size: 1.1em; vertical-align: middle;"></i> Faltam {{ count($classesFaltantes) }} {{ count($classesFaltantes) == 1 ? 'classe' : 'classes' }}: @for($i = 0; $i < count($classesFaltantes); $i++)<span>{{ $classesFaltantes[$i]['nome'] }}@if($i+1 != count($classesFaltantes)), @endif</span>@endfor</p>
                    @endif
                </div>
            </div>
        @endif
    @endif

    <div class="table-container">
    @if($relatorios -> count() > 0)

        <div class="cards-list-header">
            <h3>{{ $relatorios->count() }} {{ $relatorios->count() == 1 ? 'relatório encontrado' : 'relatórios encontrados' }}</h3>
        </div>

        <div class="cards-grid">
            @foreach($relatorios as $r)
                @php
                    $percentual = $r->matriculados > 0 ? round(100 * $r->presentes / $r->matriculados, 1) : 0;
                    $barClass = $percentual >= 70 ? 'high' : ($percentual >= 40 ? 'medium' : 'low');
                    $isToday = date('d/m/Y', strtotime($r->created_at)) == date('d/m/Y');
                    $dateFormatted = date('d/m/Y', strtotime($r->created_at));
                    $dateParam = date('Y-m-d', strtotime($r->created_at));
                @endphp
                <div class="list-card">
                    <div class="list-card-header">
                        <span class="list-card-title">
                            {{ $dateFormatted }}
                            @if($isToday)
                                <span class="badge-hoje">Hoje</span>
                            @endif
                        </span>
                        <div class="list-card-actions">
                            <a href="/admin/visualizar/relatorio/{{ $dateParam }}" class="card-action-view" title="Ver relatório">
                                <i class='bx bx-show'></i>
                            </a>
                            <a href="/admin/visualizar/pdf-relatorio/{{ $dateParam }}" class="card-action-pdf" title="Baixar PDF">
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
                            <span class="card-metric-value">{{ $r->presentes }}/{{ $r->matriculados }}</span>
                            <span class="card-metric-label">Presentes</span>
                        </div>
                        <div class="card-metric">
                            <span class="card-metric-value visitantes">+{{ $r->visitantes }}</span>
                            <span class="card-metric-label">Visitantes</span>
                        </div>
                        <div class="card-metric">
                            <span class="card-metric-value">{{ $r->presentes + $r->visitantes }}</span>
                            <span class="card-metric-label">Assist. Total</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <div class="cards-empty">
            <i class='bx bx-search-alt'></i>
            <p>Nenhum relatório encontrado</p>
        </div>
    @endif
    </div>

<script>
    document.getElementById('form-filtro-relatorios').addEventListener('submit', function(e) {
        var mes = document.getElementById('filtro-rel-mes');
        var ano = document.getElementById('filtro-rel-ano');
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

@endsection
