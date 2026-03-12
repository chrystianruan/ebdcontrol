<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Chamada - {{ $chamada->sala->nome }} | {{ date('d/m/Y', strtotime($chamada->created_at)) }}</title>
</head>
<style>

    .container div {
        margin-bottom: 3%;
        position: relative;
    }
    /* ===== Cabecalho ===== */
    .header-table { width: 100%; border: none;}
    .header-table td { border: none; vertical-align: middle; padding: 0; }
    .header-table .logo-cell { width: 70px; text-align: center; }
    .header-table .title-cell { text-align: center; }
    .header-table .title-cell h3 { font-size: 14px; margin: 0; }
    .header-table .title-cell span { font-size: 11px; font-weight: normal; }
    .center {
        text-align: center;
        font-size: 12px;
    }
    .center-item {
        margin-left: 2%;
        float: left;
    }

    .center-text {
        margin: 2% auto;
        text-align: center;
        font-size: 12px;

    }

    .center p {
        margin: 0px 5px;
    }


    .infos {
        margin: 5% 0;
        border: 1px solid black;
        padding: 5px 12px;
        font-size: 11px;
    }

    .small {
        font-size: 12px;
    }

    .automatic {
        position: fixed;
        bottom: 0px;
    }

    .span-emphasis {
        font-weight: bolder;
        font-style: italic;
    }


    table {
        border-right:1px solid black;
        border-left:1px solid black;
        border-top:1px solid black;

    }

    th {
        border-bottom: 1px solid black;
        padding: 5px;
        background-color: #ccc;
    }


    td {
        border-bottom: 1px solid black;
        padding: 5px;
        text-align: center;
    }

    .resume {

        border: 1px solid black;
        padding: 20px 15px;

    }

    .color {
        font-weight: bolder;
    }

    @php
        $assistTotalCss = $chamada->presentes + $chamada->visitantes;
        $percPresCss = $chamada->matriculados > 0 ? round(100 * $chamada->presentes / $chamada->matriculados, 1) : 0;
        $percBibCss = $assistTotalCss > 0 ? round(100 * $chamada->biblias / $assistTotalCss, 1) : 0;
        $percRevCss = $assistTotalCss > 0 ? round(100 * $chamada->revistas / $assistTotalCss, 1) : 0;
    @endphp

    #presentes {
        color: @if($percPresCss <= 50) red
        @elseif($percPresCss <= 75) orange
        @elseif($percPresCss <= 100) green
        @else blue @endif
    }

    #biblias {
        color: @if($percBibCss <= 50) red
        @elseif($percBibCss <= 75) orange
        @elseif($percBibCss <= 100) green
        @else blue @endif
    }

    #revistas {
        color: @if($percRevCss <= 50) red
        @elseif($percRevCss <= 75) orange
        @elseif($percRevCss <= 100) green
        @else blue @endif
    }

    .result {
        padding: 1px 3px;
        border-radius: 3px;
        border: 1px solid black;
        background-color: transparent;
        font-weight: bolder
    }

    .caption {
        font-weight: bolder;
        border: 1px solid;
        border-radius: 15px;
        padding: 0px 2px;
    }

    .normal-table {
        width: 100%;
        border-collapse: collapse;
    }

    .footer-fixed {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 5px 0;
        font-size: 12px;
        color: #64748b;
        border-top: 1px solid #e2e8f0;
        background-color: #fff;
        text-align: left;
    }
    .span-emphasis { font-weight: 700; font-style: italic; color: #334155; }
    body { padding-bottom: 40px; }

</style>
<body>
<div class="footer-fixed">
    Documento gerado automaticamente em <span class="span-emphasis">{{ date('d/m/Y') }}</span> as <span class="span-emphasis">{{ date('H:i:s') }}</span>, pelo sistema de administração <span class="span-emphasis">EBDControl</span>
</div>

<div class="container">

    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="img/logo-nova-adpar.jpg" width="80" alt="Logo">
            </td>
            <td class="logo-cell">
                <img src="img/logo_denec_full.jpg" width="75" alt="Logo">
            </td>
            <td class="title-cell">
                <h3>Igreja Evangélica Assembleia de Deus em Parnamirim/RN</h3>
                <span>Departamento de Ensino e Educação Cristã (DENEC)</span>
            </td>
            <td class="logo-cell">

            </td>
            <td class="logo-cell">
                <img src="img/logo_ebd.jpg" width="55" alt="Logo EBD">
            </td>
        </tr>
    </table>

    <div class="infos">
        <p>Frequência da classe: <span style="font-weight: bolder">{{ $chamada->nome }}</span>

        </p>
        <p style="float: right; margin-top: -25px">Data: <span style="font-weight: bolder">{{date('d/m/Y', strtotime($chamada->created_at))}}</span></p>
        <table class="normal-table">
            <thead>
            <tr>
                <th>Matriculados</th>
                <th>Presentes</th>
                <th>Visitantes</th>
                <th>Assist. Total</th>
                <th>Bíblias</th>
                <th>Revistas</th>
            </tr>
            </thead>
            <tbody>
            @php
                $assistTotal = $chamada->presentes + $chamada->visitantes;
                $percPresentes = $chamada->matriculados > 0 ? round(100 * $chamada->presentes / $chamada->matriculados, 1) : 0;
                $percBiblias = $assistTotal > 0 ? round(100 * $chamada->biblias / $assistTotal, 1) : 0;
                $percRevistas = $assistTotal > 0 ? round(100 * $chamada->revistas / $assistTotal, 1) : 0;
            @endphp
            <tr>
                <td style="text-align: center"><span class="result">{{ $chamada->matriculados }}</span></td>
                <td style="text-align: center"><span class="result">{{ $chamada->presentes }}</span> <span class="color" id="presentes">({{ number_format($percPresentes, 1, ',', '.') }}%)</span></td>
                <td style="text-align: center"><span class="result">{{ $chamada->visitantes }}</span></td>
                <td style="text-align: center"><span class="result">{{ $assistTotal }}</span></td>
                <td style="text-align: center"><span class="result">{{ $chamada->biblias }}</span> <span class="color" id="biblias">({{ number_format($percBiblias, 1, ',', '.') }}%)</span></td>
                <td style="text-align: center"><span class="result">{{ $chamada->revistas }}</span> <span class="color" id="revistas">({{ number_format($percRevistas, 1, ',', '.') }}%)</span></td>
            </tr>
            </tbody>
        </table>
        <div class="center-text">
            <span class="caption" style="background-color: red;color:red  ">F</span> = Ruim/Péssimo |
            <span class="caption" style="background-color: orange; color:orange ">F</span> = Médio |
            <span class="caption" style="background-color: green;color:green ">F</span> = Bom |
        </div>


    </div>



    <div class="tables">
        <table class="normal-table">
            <thead>
            <tr>
                <th>Nome</th>
                <th>Data nascimento</th>
                <th>Função</th>
                <th>Presença</th>
            </tr>
            </thead>

            <tbody>

            @foreach($presencas as $p)
                <tr>
                    <td>{{ $p->pessoa->nome }}</td>
                    <td>{{ date('d/m', strtotime($p->pessoa->data_nasc)) }}</td>
                    <td>{{ $p->funcao->nome }}</td>
                    <td> @if($p->presente == 1) <span style="color: rgb(12, 223, 12)">Sim</span> @else <span style="color: red">Não</span> @endif</td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>


</div>

</body>
</html>
