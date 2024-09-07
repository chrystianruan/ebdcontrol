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
        margin: 15% 0;
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
        background-color: #ccc:
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

    #presentes {
        color: @if(100 * $chamada->presentes / $chamada->matriculados <= 50) red
        @elseif(100 * $chamada->presentes / $chamada->matriculados > 50 && 100 * $chamada->presentes / $chamada->matriculados <= 75) orange
        @elseif(100 * $chamada->presentes / $chamada->matriculados > 75 && 100 * $chamada->presentes / $chamada->matriculados <= 100) green
        @else blue @endif
    }

    #assist_total {
        color: @if(100 * $chamada->presentes+$chamada->visitantes / $chamada->matriculados <= 50) red
        @elseif(100 * $chamada->presentes+$chamada->visitantes / $chamada->matriculados > 50 && 100 * $chamada->presentes+$chamada->visitantes / $chamada->matriculados <= 75) orange
        @elseif(100 * $chamada->presentes+$chamada->visitantes / $chamada->matriculados > 75 && 100 * $chamada->presentes+$chamada->visitantes / $chamada->matriculados <= 100) green
        @else blue @endif
    }

    #biblias {
        color: @if(100 * $chamada->biblias / $chamada->presentes+$chamada->visitantes <= 50) red
        @elseif(100 * $chamada->biblias / $chamada->presentes+$chamada->visitantes > 50 && 100 * $chamada->biblias / $chamada->presentes+$chamada->visitantes <= 75) orange
        @elseif(100 * $chamada->biblias / $chamada->presentes+$chamada->visitantes > 75 && 100 * $chamada->biblias / $chamada->presentes+$chamada->visitantes <= 100) green
        @else blue @endif
    }

    #revistas {
        color: @if(100 * $chamada->revistas / $chamada->presentes+$chamada->visitantes <= 50) red
        @elseif(100 * $chamada->revistas / $chamada->presentes+$chamada->visitantes > 50 && 100 * $chamada->revistas / $chamada->presentes+$chamada->visitantes <= 75) orange
        @elseif(100 * $chamada->revistas / $chamada->presentes+$chamada->visitantes > 75 && 100 * $chamada->revistas / $chamada->presentes+$chamada->visitantes <= 100) green
        @else blue @endif
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

    .normal-table {
        width: 100%;

        border-collapse: collapse;

    }

</style>
<body>
<div class="container">
    <div class="center">
            <img src="img/logo-nova-adpar.jpg" class="center-item" width="100">
        <h3 class="center-item" >Igreja Evangélica Assembleia de Deus em Parnamirim/RN <br> <span style="font-size: 12px; font-weight: lighter">Departamento de Escola Bíblica Dominical </span></h3>
        <img class="center-item" src="img/logo_ebd.jpg" width="70">

    </div>

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
                <th>Assistência total</th>
                <th>Bíblias</th>
                <th>Revistas</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="text-align: center"><span class="result">{{ $chamada->matriculados }}</span> </td>
                <td style="text-align: center"><span class="result">{{ $chamada->presentes }}</span> <span class="color" id="presentes">({{  number_format(100 * $chamada->presentes / $chamada->matriculados, 1, ',', '.') }}%)</span> </td>
                <td style="text-align: center"><span class="result">{{ $chamada->visitantes }}</span></td>
                <td style="text-align: center"><span class="result">{{ $chamada->presentes+$chamada->visitantes }}</span> <span class="color" id="assist_total">({{  number_format(100 * $chamada->presentes+$chamada->visitantes / $chamada->matriculados, 1, ',', '.') }}%)</span></td>
                <td style="text-align: center"><span class="result">{{ $chamada->biblias }}</span> <span class="color" id="biblias">({{  number_format(100 * $chamada->biblias / $chamada->presentes+$chamada->visitantes, 1, ',', '.') }}%)</span></td>
                <td style="text-align: center"><span class="result">{{ $chamada->revistas }}</span> <span class="color" id="revistas">({{ number_format(100 * $chamada->revistas / $chamada->presentes+$chamada->visitantes, 1, ',', '.') }}%)</span></td>

            </tr>
            </tbody>
        </table>
        <div class="center-text">
            <span class="caption" style="background-color: red;color:red  ">F</span> = Ruim/Péssimo |
            <span class="caption" style="background-color: orange; color:orange ">F</span> = Médio |
            <span class="caption" style="background-color: green;color:green ">F</span> = Bom |
            <span class="caption" style="background-color: blue;color:blue ">F</span> = Muito Bom/Excelente
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
                    <td>@if($p->presente == 1) <span style="color: rgb(12, 223, 12)" class="bx bx-check">Sim</span> @else <span style="color: red" class="bx bx-x">Não</i> @endif</td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>


</div>



<p class="small">Documento gerado automaticamente em <span class="span-emphasis">{{date('d/m/Y')}}</span> às <span class="span-emphasis">{{date('H:i:s')}}</span>, pelo sistema de administração <span class="span-emphasis">EBDControl</span></p>

</body>
</html>
