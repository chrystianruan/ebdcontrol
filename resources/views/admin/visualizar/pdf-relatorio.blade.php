<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório - {{ date('d/m/Y', strtotime($relatorio->created_at)) }}</title>
</head>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; }

    .container { padding: 0 20px; }

    /* ===== Cabecalho ===== */
    .header-table { width: 100%; border: none; margin-bottom: 20px; margin-top: 20px}
    .header-table td { border: none; vertical-align: middle; padding: 0; }
    .header-table .logo-cell { width: 120px; text-align: center; }
    .header-table .title-cell { text-align: center; }
    .header-table .title-cell h3 { font-size: 14px; margin: 0; }
    .header-table .title-cell span { font-size: 11px; font-weight: normal; }

    .infos {
        margin-top: 10px;
        border: 1px solid #94a3b8;
        padding: 10px;
        background-color: #f8fafc;
        margin-bottom: 14px;
    }
    .infos p { margin: 2px 0; }

    /* ===== Tabelas base ===== */
    table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    th { padding: 7px 8px; text-align: center; font-size: 10px; text-transform: uppercase; letter-spacing: 0.3px; }
    td { padding: 6px 8px; font-size: 11px; }

    /* ===== Tabela principal de classes ===== */
    .main-table th {
        background-color: #6b21a8;
        color: #fff;
        border: 1px solid #5b1a94;
        font-weight: 600;
    }
    .main-table th:first-child { text-align: left; }
    .main-table td {
        border: 1px solid #e2e8f0;
        text-align: center;
    }
    .main-table td:first-child { text-align: left; font-weight: 600; }
    .main-table tbody tr:nth-child(even) { background-color: #f8fafc; }
    .main-table .total-row td {
        background-color: #6b21a8;
        color: #fff;
        font-weight: 700;
        border-color: #5b1a94;
    }
    .main-table .faltante-row td {
        background-color: #fef2f2;
        color: #991b1b;
        font-style: italic;
        border-color: #fecaca;
    }
    .main-table .faltante-row td:first-child {
        font-weight: 700;
    }

    /* ===== Resumo geral ===== */
    .resumo-table td {
        text-align: center;
        border: 1px solid #e2e8f0;
        padding: 8px 6px;
        background-color: #f8fafc;
    }
    .resumo-table .resumo-valor { font-size: 16px; font-weight: 700; color: #1e293b; display: block; }
    .resumo-table .resumo-label { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-top: 2px; }

    /* ===== Titulos de secao ===== */
    .section-title {
        font-size: 12px;
        font-weight: 700;
        padding: 6px 10px;
        margin: 16px 0 8px 0;
        border-left: 4px solid #6b21a8;
        background-color: #f1f5f9;
        color: #334155;
    }

    /* ===== Tabela destaques/piores ===== */
    .dp-table th {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        font-weight: 600;
        text-align: left;
    }
    .dp-table td {
        border: 1px solid #e2e8f0;
        text-align: left;
        padding: 6px 8px;
    }
    .dp-table td:first-child { font-weight: 600; color: #334155; }
    .dp-col-melhor { background-color: #f0fdf4; }
    .dp-col-pior { background-color: #fef2f2; }
    .dp-th-melhor { background-color: #dcfce7 !important; color: #166534 !important; }
    .dp-th-pior { background-color: #fee2e2 !important; color: #991b1b !important; }
    .txt-green { color: #16a34a; font-weight: 700; }
    .txt-red { color: #dc2626; font-weight: 700; }

    /* ===== Destaque geral ===== */
    .destaque-geral-box {
        background-color: #fef9c3;
        border: 1px solid #fde68a;
        padding: 8px 12px;
        margin: 0 0 14px 0;
        font-size: 11px;
        color: #92400e;
    }
    .destaque-geral-box strong { color: #78350f; }

    /* ===== Tabela comparativo ===== */
    .comp-table th {
        background-color: #ede9fe;
        color: #5b21b6;
        border: 1px solid #c4b5fd;
        font-weight: 600;
    }
    .comp-table th:first-child { text-align: left; }
    .comp-table td {
        border: 1px solid #e2e8f0;
        text-align: center;
        padding: 6px 8px;
    }
    .comp-table td:first-child { text-align: left; font-weight: 600; color: #334155; }
    .comp-table tbody tr:nth-child(even) { background-color: #faf5ff; }

    .badge-up { color: #16a34a; font-weight: 700; }
    .badge-down { color: #dc2626; font-weight: 700; }
    .badge-neutral { color: #6b7280; font-weight: 700; }
    .txt-muted { font-size: 9px; color: #94a3b8; }

    .comp-nota {
        font-size: 10px;
        color: #64748b;
        font-style: italic;
        margin: 0 0 8px 0;
        padding: 6px 10px;
        background-color: #f3f0f7;
        border-left: 3px solid #6b21a8;
    }

    /* ===== Quebras de pagina ===== */
    .page-break { page-break-before: always; padding-top: 10px; }

    /* ===== Rodape fixo em todas as paginas ===== */
    .footer-fixed {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 8px 20px;
        font-size: 9px;
        color: #64748b;
        border-top: 1px solid #e2e8f0;
        background-color: #fff;
        text-align: left;
    }
    .span-emphasis { font-weight: 700; font-style: italic; color: #334155; }

    /* Evitar que conteudo sobreponha o rodape */
    body { padding-bottom: 40px; }
</style>
<body>
    {{-- Rodape fixo em todas as paginas --}}
    <div class="footer-fixed">
        Documento gerado automaticamente em <span class="span-emphasis">{{ date('d/m/Y') }}</span> as <span class="span-emphasis">{{ date('H:i:s') }}</span>, pelo sistema de administração <span class="span-emphasis">EBDControl</span>
    </div>

    <div class="container">

        {{-- ===== CABECALHO ===== --}}
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="img/logo-nova-adpar.jpg" width="90" alt="Logo">
                </td>
                <td class="logo-cell">
                    <img src="img/logo_denec_full.jpg" width="90" alt="Logo">
                </td>
                <td class="title-cell">
                    <h3>Igreja Evangélica Assembleia de Deus em Parnamirim/RN</h3>
                    <span>Departamento de Ensino e Educação Cristã (DENEC)</span>
                </td>
                <td class="logo-cell">

                </td>
                <td class="logo-cell">
                    <img src="img/logo_ebd.jpg" width="65" alt="Logo EBD">
                </td>
            </tr>
        </table>

        <div class="infos">
            <p style="font-weight:700; font-size:13px;">Relatório de Frequências</p>
            <p>Data: <span style="font-weight:700">{{ date('d/m/Y', strtotime($relatorio->created_at)) }}</span></p>
        </div>

        {{-- ============================================================ --}}
        {{-- PAGINA 1: Resumo Geral + Melhores e Piores Indices          --}}
        {{-- ============================================================ --}}

        {{-- ===== 1. RESUMO GERAL ===== --}}
        @php
            $assistTotal = $relatorio->presentes + $relatorio->visitantes;
            $percPresenca = $relatorio->matriculados > 0 ? round(100 * $relatorio->presentes / $relatorio->matriculados, 1) : 0;
            $percBiblias = $assistTotal > 0 ? round(100 * $relatorio->biblias / $assistTotal, 1) : 0;
            $percRevistas = $assistTotal > 0 ? round(100 * $relatorio->revistas / $assistTotal, 1) : 0;
        @endphp

        <div class="section-title">Resumo Geral</div>
        <table class="resumo-table">
            <tr>
                <td>
                    <span class="resumo-valor">{{ $relatorio->matriculados }}</span>
                    <span class="resumo-label">Matriculados</span>
                </td>
                <td>
                    <span class="resumo-valor">{{ $relatorio->presentes }}</span>
                    <span class="resumo-label">Presentes</span>
                </td>
                <td>
                    <span class="resumo-valor">{{ $relatorio->visitantes }}</span>
                    <span class="resumo-label">Visitantes</span>
                </td>
                <td>
                    <span class="resumo-valor">{{ $assistTotal }}</span>
                    <span class="resumo-label">Assist. Total</span>
                </td>
                <td>
                    <span class="resumo-valor" style="color: {{ $percPresenca >= 70 ? '#16a34a' : ($percPresenca >= 40 ? '#d97706' : '#dc2626') }}">{{ number_format($percPresenca, 1, ',', '.') }}%</span>
                    <span class="resumo-label">Presenca</span>
                </td>
                <td>
                    <span class="resumo-valor">{{ $relatorio->biblias }}</span>
                    <span class="resumo-label">Bíblias</span>
                </td>
                <td>
                    <span class="resumo-valor">{{ $relatorio->revistas }}</span>
                    <span class="resumo-label">Revistas</span>
                </td>
                <td>
                    <span class="resumo-valor">{{ number_format($percBiblias, 1, ',', '.') }}%</span>
                    <span class="resumo-label">% Bíblias</span>
                </td>
                <td>
                    <span class="resumo-valor">{{ number_format($percRevistas, 1, ',', '.') }}%</span>
                    <span class="resumo-label">% Revistas</span>
                </td>
            </tr>
        </table>

        {{-- ===== 2. DESTAQUES & PIORES INDICES ===== --}}
        @php
            $temDestaques = $destaques['maior_presenca'] || $destaques['maior_visitantes'] || $destaques['maior_biblias'] || $destaques['maior_revistas'];
            $temPiores = $piores['pior_presenca'] || $piores['pior_visitantes'] || $piores['pior_biblias'] || $piores['pior_revistas'];
        @endphp

        @if($temDestaques || $temPiores)
        <div class="section-title">Melhores e Piores Índices</div>
        <table class="dp-table">
            <thead>
                <tr>
                    <th style="width:22%">Categoria</th>
                    <th class="dp-th-melhor" style="width:39%">MELHOR ÍNDICE</th>
                    <th class="dp-th-pior" style="width:39%">PIOR ÍNDICE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Presenca</td>
                    <td class="dp-col-melhor">
                        @if($destaques['maior_presenca'])
                            {{ $destaques['maior_presenca']['sala'] }}
                            <span class="txt-green">({{ number_format($destaques['maior_presenca']['valor'], 1, ',', '.') }}%)</span>
                        @else - @endif
                    </td>
                    <td class="dp-col-pior">
                        @if($piores['pior_presenca'])
                            {{ $piores['pior_presenca']['sala'] }}
                            <span class="txt-red">({{ number_format($piores['pior_presenca']['valor'], 1, ',', '.') }}%)</span>
                        @else - @endif
                    </td>
                </tr>
                <tr>
                    <td>Visitantes</td>
                    <td class="dp-col-melhor">
                        @if($destaques['maior_visitantes'])
                            {{ $destaques['maior_visitantes']['sala'] }}
                            <span class="txt-green">({{ $destaques['maior_visitantes']['valor'] }})</span>
                        @else - @endif
                    </td>
                    <td class="dp-col-pior">
                        @if($piores['pior_visitantes'])
                            {{ $piores['pior_visitantes']['sala'] }}
                            <span class="txt-red">({{ $piores['pior_visitantes']['valor'] }})</span>
                        @else - @endif
                    </td>
                </tr>
                <tr>
                    <td>Bíblias</td>
                    <td class="dp-col-melhor">
                        @if($destaques['maior_biblias'])
                            {{ $destaques['maior_biblias']['sala'] }}
                            <span class="txt-green">({{ $destaques['maior_biblias']['quantidade'] }} ➔ {{ number_format($destaques['maior_biblias']['valor'], 1, ',', '.') }}%)</span>
                        @else - @endif
                    </td>
                    <td class="dp-col-pior">
                        @if($piores['pior_biblias'])
                            {{ $piores['pior_biblias']['sala'] }}
                            <span class="txt-red">({{ $piores['pior_biblias']['quantidade'] }} ➔ {{ number_format($piores['pior_biblias']['valor'], 1, ',', '.') }}%)</span>
                        @else - @endif
                    </td>
                </tr>
                <tr>
                    <td>Revistas</td>
                    <td class="dp-col-melhor">
                        @if($destaques['maior_revistas'])
                            {{ $destaques['maior_revistas']['sala'] }}
                            <span class="txt-green">({{ $destaques['maior_revistas']['quantidade'] }} ➔ {{ number_format($destaques['maior_revistas']['valor'], 1, ',', '.') }}%)</span>
                        @else - @endif
                    </td>
                    <td class="dp-col-pior">
                        @if($piores['pior_revistas'])
                            {{ $piores['pior_revistas']['sala'] }}
                            <span class="txt-red">({{ $piores['pior_revistas']['quantidade'] }} ➔ {{ number_format($piores['pior_revistas']['valor'], 1, ',', '.') }}%)</span>
                        @else - @endif
                    </td>
                </tr>
            </tbody>
        </table>

        @if($destaques['destaque_geral'])
        <div class="destaque-geral-box">
            CLASSE DESTAQUE GERAL: <strong>{{ $destaques['destaque_geral']['sala'] }}</strong>
            - {{ $destaques['destaque_geral']['pontos'] }}/4 categorias
        </div>
        @endif
        @endif

        {{-- ============================================================ --}}
        {{-- PAGINA 2: Comparativo com Relatorio Anterior                 --}}
        {{-- ============================================================ --}}
        <div class="page-break"></div>

        <div class="section-title" style="border-left-color: #7c3aed;">Comparativo com Relatório Anterior</div>

        @if(!$comparativo['tem_anterior'])
            <p class="comp-nota">Este e o primeiro relatório registrado. Nao há dados anteriores para comparacão.</p>
        @else
            <p class="comp-nota">Comparando com o relatório de <strong style="color:#334155">{{ $comparativo['data_anterior'] }}</strong></p>

            @php $comp = $comparativo['comparativo']; @endphp
            <table class="comp-table">
                <thead>
                    <tr>
                        <th style="width:25%">Métrica</th>
                        <th style="width:18%">Atual</th>
                        <th style="width:18%">Anterior</th>
                        <th style="width:39%">Variação</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $metricas = [
                            'Matriculados' => $comp['matriculados'],
                            'Presentes'    => $comp['presentes'],
                            'Visitantes'   => $comp['visitantes'],
                            'Assist. Total'=> $comp['assist_total'],
                            'Biblias'      => $comp['biblias'],
                            'Revistas'     => $comp['revistas'],
                        ];
                    @endphp
                    @foreach($metricas as $label => $m)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ $m['atual'] }}</td>
                        <td>{{ $m['anterior'] }}</td>
                        <td>
                            @if($m['percentual'] > 0)
                                <span class="badge-up">+{{ number_format($m['percentual'], 1, ',', '.') }}%</span>
                            @elseif($m['percentual'] < 0)
                                <span class="badge-down">{{ number_format($m['percentual'], 1, ',', '.') }}%</span>
                            @else
                                <span class="badge-neutral">0%</span>
                            @endif
                            <span class="txt-muted">({{ $m['diferenca'] >= 0 ? '+' : '' }}{{ $m['diferenca'] }})</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="comp-table">
                <thead>
                    <tr>
                        <th style="width:25%">Percentual</th>
                        <th style="width:18%">Atual</th>
                        <th style="width:18%">Anterior</th>
                        <th style="width:39%">Variação (pp)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $percs = [
                            '% Presenca' => $comp['perc_presenca'],
                            '% Biblias'  => $comp['perc_biblias'],
                            '% Revistas' => $comp['perc_revistas'],
                        ];
                    @endphp
                    @foreach($percs as $label => $p)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ number_format($p['atual'], 1, ',', '.') }}%</td>
                        <td>{{ number_format($p['anterior'], 1, ',', '.') }}%</td>
                        <td>
                            @if($p['variacao'] > 0)
                                <span class="badge-up">+{{ number_format($p['variacao'], 1, ',', '.') }}pp</span>
                            @elseif($p['variacao'] < 0)
                                <span class="badge-down">{{ number_format($p['variacao'], 1, ',', '.') }}pp</span>
                            @else
                                <span class="badge-neutral">0pp</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- ============================================================ --}}
        {{-- PAGINA 3+: Detalhamento por Classe                           --}}
        {{-- ============================================================ --}}
        <div class="page-break"></div>

        <div class="section-title">Detalhamento por Classe</div>
        <table class="main-table">
            <thead>
                <tr>
                    <th>Classe</th>
                    <th>Matr.</th>
                    <th>Pres.</th>
                    <th>Visit.</th>
                    <th>Assist.</th>
                    <th>Biblias</th>
                    <th>Revistas</th>
                    <th>% Pres.</th>
                    <th>Horario</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chamadas as $chamada)
                @php
                    $chAssist = $chamada->presentes + $chamada->visitantes;
                    $chPerc = $chamada->matriculados > 0 ? round(100 * $chamada->presentes / $chamada->matriculados, 1) : 0;
                @endphp
                <tr>
                    <td>{{ $chamada->sala->nome }}</td>
                    <td>{{ $chamada->matriculados }}</td>
                    <td>{{ $chamada->presentes }}</td>
                    <td>{{ $chamada->visitantes }}</td>
                    <td>{{ $chAssist }}</td>
                    <td>{{ $chamada->biblias }}</td>
                    <td>{{ $chamada->revistas }}</td>
                    <td style="color: {{ $chPerc >= 70 ? '#16a34a' : ($chPerc >= 40 ? '#d97706' : '#dc2626') }}; font-weight:700">{{ number_format($chPerc, 1, ',', '.') }}%</td>
                    <td>{{ date('H:i:s', strtotime($chamada->created_at)) }}</td>
                </tr>
                @endforeach
                @if(isset($classesFaltantes) && count($classesFaltantes) > 0)
                    @foreach($classesFaltantes as $faltante)
                    <tr class="faltante-row">
                        <td>{{ $faltante['nome'] }}</td>
                        <td colspan="8" style="text-align: center;">Chamada nao enviada</td>
                    </tr>
                    @endforeach
                @endif
                <tr class="total-row">
                    <td>Total</td>
                    <td>{{ $relatorio->matriculados }}</td>
                    <td>{{ $relatorio->presentes }}</td>
                    <td>{{ $relatorio->visitantes }}</td>
                    <td>{{ $assistTotal }}</td>
                    <td>{{ $relatorio->biblias }}</td>
                    <td>{{ $relatorio->revistas }}</td>
                    <td>{{ number_format($percPresenca, 1, ',', '.') }}%</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>

    </div>


</body>
</html>
