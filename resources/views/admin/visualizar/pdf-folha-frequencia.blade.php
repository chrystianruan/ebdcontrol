<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Chamada - PDF</title>
</head>
<style>

    .container div {
        margin-bottom: 3%;
        position: relative;
    }
    /* ===== Cabecalho ===== */
    .header-table { width: 100%; border: none; margin-bottom: 5px; margin-top: 5px}
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
        margin-left: 5%;
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
        font-size: 15px;
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

    .big-table {
        font-size: 15px;
        position: relative;
        width: 100%;
    }


    th {
        border-top:  1px solid black;
        border-left:  1px solid black;
        border-bottom: 1px solid black;
        border-right:  1px solid black;
        padding: 5px;
        background-color: #ccc;
    }


    td {
        border-left:  1px solid black;
        border-bottom: 1px solid black;
        border-right:  1px solid black;
        padding: 5px;
    }

    .resume {
        font-size: 13px;
        padding: 20px 80px;

    }

    .color {
        font-weight: bolder;
    }

    .result {
        padding: 1px 3px;
        border-radius: 3px;
        border: 1px solid black;
        background-color: none;
        font-weight: bolder
    }

    .caption {
        font-weight: bolder;
        border: 1px solid;
        border-radius: 15px;
        padding: 0px 2px;

    }
    .box-clean {
        border: 1px solid;
        padding:6%;
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
        <p>Frequência da classe: <span style="font-weight: bolder">{{ $classeSelected->nome }}</span>

        </p>
        <p style="float: right; margin-top: -25px">Data: <span style="font-weight: bolder">{{ date('d/m/Y', strtotime($date)) }}</span></p>

    </div>



    <div class="tables">
        <table class="big-table">
            <thead>
            <tr>
                <th>Nome</th>
                <th style="width: 100px">Função</th>
                <th style="width: 30px">Presença</th>
                <th style="width: 30px">Bíb.</th>
                <th style="width: 30px">Rev.</th>
            </tr>
            </thead>

            <tbody>

            @foreach($pessoas as $key => $pessoa)
                <tr>
                    <td> {{ $pessoa->pessoa_nome }}</td>
                    <td> {{ $pessoa->funcao_nome }}</td>
                    <td>   </td>
                    <td>   </td>
                    <td>   </td>
                </tr>
            @endforeach

            </tbody>
        </table>

    </div>


    </div>

<div class="resume">

    <table class="big-table">
        <thead>
        <tr>
            <th>Matriculados </th>
            <th>Presentes</th>
            <th>Visitantes</th>
            <th>Presentes+Visitantes</th>
            <th>Bíblias</th>
            <th>Revistas</th>
        </tr>
        </thead>
        <tbody>
        <tr style="text-align: center">
            <td style="font-weight: bold"> {{ $pessoas->count() }} </td>
            <td>  </td>
            <td>  </td>
            <td>  </td>
            <td>  </td>
            <td>   </td>
        </tr>
        </tbody>
    </table>
    <p>Observações: </p>
    <div class="box-clean"></div>

</div>
</body>
</html>
