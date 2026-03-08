@extends('layouts.main')

@section('title', 'Início')

@section('content')
<link rel="stylesheet" href="{{ cacheBust('css/filtros.css') }}">
<link rel="stylesheet" href="{{ cacheBust('css/cards-list.css') }}">
<link rel="stylesheet" href="{{ cacheBust('css/formGroup.css') }}">
<link rel="stylesheet" href="{{ cacheBust('css/buttonsAdmin.css') }}">
<link rel="stylesheet" href="{{ cacheBust('css/tabs-relatorios.css') }}">
<link rel="stylesheet" href="{{ cacheBust('css/modalAdmin.css') }}">
<link rel="stylesheet" href="{{ cacheBust('css/modalRelatorio.css') }}">
<input type="hidden" id="url-get-chamadas" value="{{ route('relatorios.presenca-classe-post') }}">

<div class="container-intern">

    {{-- ===== TABS HEADER ===== --}}
    <div class="tabs-container">
        <div class="tabs-header">
            <button class="tab-btn active" data-tab="tab-chamadas">
                <i class='bx bx-trending-up'></i> Chamadas
            </button>
            <button class="tab-btn" data-tab="tab-presenca">
                <i class='bx bx-user-check'></i> Presenças
            </button>
        </div>
    </div>

    {{-- ===== TAB 1: Relatórios de chamadas ===== --}}
    <div class="tab-content active" id="tab-chamadas">
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
                        $destaques = $destaquesPorData[$dateParam] ?? null;
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
                                <button type="button" class="card-action-view btn-abrir-modal-relatorio" data-date="{{ $dateParam }}" title="Ver relatório">
                                    <i class='bx bx-show'></i>
                                </button>
                                <a href="/admin/visualizar/pdf-relatorio/{{ $dateParam }}" target="_blank" class="card-action-pdf" title="Baixar PDF">
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

                        @if($destaques)
                        <div class="card-destaques">
                            <div class="card-destaques-title">
                                <i class='bx bx-star'></i> Destaques
                            </div>
                            <div class="card-destaques-list">
                                @if($destaques['maior_presenca'])
                                <div class="card-destaque-item">
                                    <i class='bx bx-user-check destaque-icon presenca'></i>
                                    <div class="destaque-info">
                                        <span class="destaque-label">Presença</span>
                                        <span class="destaque-value">{{ $destaques['maior_presenca']['sala'] }} <em>({{ number_format($destaques['maior_presenca']['valor'], 1, ',', '.') }}%)</em></span>
                                    </div>
                                </div>
                                @endif
                                @if($destaques['maior_visitantes'])
                                <div class="card-destaque-item">
                                    <i class='bx bx-group destaque-icon visitantes'></i>
                                    <div class="destaque-info">
                                        <span class="destaque-label">Visitantes</span>
                                        <span class="destaque-value">{{ $destaques['maior_visitantes']['sala'] }} <em>({{ $destaques['maior_visitantes']['valor'] }})</em></span>
                                    </div>
                                </div>
                                @endif
                                @if($destaques['maior_biblias'])
                                <div class="card-destaque-item">
                                    <i class='bx bx-book-open destaque-icon biblias'></i>
                                    <div class="destaque-info">
                                        <span class="destaque-label">Bíblias</span>
                                        <span class="destaque-value">{{ $destaques['maior_biblias']['sala'] }} <em>({{ $destaques['maior_biblias']['quantidade'] }} ➔ {{ number_format($destaques['maior_biblias']['valor'], 1, ',', '.') }}%)</em></span>
                                    </div>
                                </div>
                                @endif
                                @if($destaques['maior_revistas'])
                                <div class="card-destaque-item">
                                    <i class='bx bx-news destaque-icon revistas'></i>
                                    <div class="destaque-info">
                                        <span class="destaque-label">Revistas</span>
                                        <span class="destaque-value">{{ $destaques['maior_revistas']['sala'] }} <em>({{ $destaques['maior_revistas']['quantidade'] }} ➔ {{ number_format($destaques['maior_revistas']['valor'], 1, ',', '.') }}%)</em></span>
                                    </div>
                                </div>
                                @endif
                            </div>

                            @if($destaques['destaque_geral'])
                            <div class="card-destaque-geral">
                                <i class='bx bx-trophy'></i>
                                <span>{{ $destaques['destaque_geral']['sala'] }}</span>
                                <em>{{ $destaques['destaque_geral']['pontos'] }}/4 categorias</em>
                            </div>
                            @endif
                        </div>
                        @endif
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
    </div>

    {{-- ===== TAB 2: Presença por classe ===== --}}
    <div class="tab-content" id="tab-presenca">
        <div class="fields">
            <div class="filter-header">
                <span class="title">Filtros: </span>
            </div>
            <div>
                <select id="classe" class="select">
                    <option selected disabled value="">Classe</option>
                    @foreach($salas as $c)
                        <option value="{{ encryptId($c->id) }}">{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="text" class="input" placeholder="Data início" id="initial_date" onfocus="(this.type='date')">
            </div>
            <div>
                <input type="text" class="input" placeholder="Data fim" id="final_date" onfocus="(this.type='date')">
            </div>
            <div class="div-buttons-filter">
                <div class="btnFilter">
                    <button type="button" class="btn btn-secondary" id="gerar-relatorio">
                        Gerar
                        <i class='bx bx-bar-chart-alt-2'></i>
                    </button>
                </div>
                <div class="btnFilter">
                    <button type="button" class="btn btn-download" id="baixar-relatorio">
                        Baixar PDF
                        <i class='bx bxs-file-pdf'></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Área de feedback --}}
        <div id="presenca-feedback" style="display: none;"></div>

        {{-- Estado inicial --}}
        <div class="presenca-empty-state" id="presenca-empty-state">
            <i class='bx bx-user-check'></i>
            <p>Nenhum relatório gerado</p>
            <span class="presenca-empty-sub">Selecione a classe e o período desejado para gerar o relatório de presenças</span>
        </div>

        {{-- Tabela oculta para gerar PDF --}}
        <div style="display: none" class="table-wrapper">
            <table id="hidden-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Função</th>
                        <th>Data de nascimento</th>
                        <th>Presenças</th>
                    </tr>
                </thead>
                <tbody id="hidden-tbody-data"></tbody>
            </table>
        </div>

        <div class="table-container" id="container-table" style="display: none;">
            <div class="table-wrapper">
                <table id="table-render">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Função</th>
                            <th>Data de nascimento</th>
                            <th>Presenças</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-data"></tbody>
                </table>
            </div>
        </div>

        <div class="presenca-loader" id="loader">
            <div class="loader"></div>
        </div>
    </div>

</div>

<script>
    // === Tabs ===
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
            document.querySelectorAll('.tab-content').forEach(function(c) { c.classList.remove('active'); });
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });

    // === Validação filtro de chamadas ===
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

{{-- ===== Modal de Visualizar Relatório ===== --}}
<div class="modal-overlay" id="modalVisualizarRelatorio">
    <div class="modal modal-wide">
        <div class="modal-header">
            <h2>Visualizar Relatório</h2>
            <button class="modal-close" onclick="fecharModalRelatorio()">
                <i class="bx bx-x"></i>
            </button>
        </div>
        <div class="modal-body" id="relatorio-modal-body">
            {{-- Conteúdo carregado via JS --}}
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="fecharModalRelatorio()">Fechar</button>
        </div>
    </div>
</div>

<script src="{{ cacheBust('js/modalRelatorio.js') }}"></script>

@endsection

@push('scripts-relatorio-presenca')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js" integrity="sha512-2/YdOMV+YNpanLCF5MdQwaoFRVbTmrJ4u4EpqS/USXAQNUDgI5uwYi6J98WVtJKcfe1AbgerygzDFToxAlOGEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    const { jsPDF } = window.jspdf;
</script>
<script src="{{ cacheBust('js/relatorio-presenca.js') }}"></script>
@endpush

