<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Relatório - PDF</title>
</head>
<style>

    .container div {
        position: relative;
    }
    .center {
        text-align: center;
        margin-left: 15%;

    }
    .center-item {
        float: left;
        margin-left: 3%;
    }

    .center p {
        margin: 0px 5px;
    }


    .infos {
        margin-top: 10%;
       border: 1px solid black;
       padding: 10px;
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

        width: 100%;

    }

    table {
        border:1px solid black;

    }

    th {
        border-bottom: 1px solid black;
        padding: 5px;
    }


    td {
        border-bottom: 1px solid black;
        border-bottom: 1px solid black;
        border-left: 1px solid;
        padding: 5px;
    }

    .resume {
        border-left: 1px solid black;
        border-right: 1px solid black;
        border-bottom: 1px solid black;
        padding: 1px 15px;
    }

    .color {
        font-weight: bolder;
    }

    #presentes {
        color: @if(100 * $relatorio->presentes / $relatorio->matriculados <= 50) red
        @elseif(100 * $relatorio->presentes / $relatorio->matriculados > 50 && 100 * $relatorio->presentes / $relatorio->matriculados <= 75) orange
        @elseif(100 * $relatorio->presentes / $relatorio->matriculados > 75 && 100 * $relatorio->presentes / $relatorio->matriculados <= 100) green
        @else blue @endif
    }

    #assist_total {
        color: @if(100 * $relatorio->assist_total / $relatorio->matriculados <= 50) red
        @elseif(100 * $relatorio->assist_total / $relatorio->matriculados > 50 && 100 * $relatorio->assist_total / $relatorio->matriculados <= 75) orange
        @elseif(100 * $relatorio->assist_total / $relatorio->matriculados > 75 && 100 * $relatorio->assist_total / $relatorio->matriculados <= 100) green
        @else blue @endif
    }

    #biblias {
        color: @if(100 * $relatorio->biblias / $relatorio->assist_total <= 50) red
        @elseif(100 * $relatorio->biblias / $relatorio->assist_total > 50 && 100 * $relatorio->biblias / $relatorio->assist_total <= 75) orange
        @elseif(100 * $relatorio->biblias / $relatorio->assist_total > 75 && 100 * $relatorio->biblias / $relatorio->assist_total <= 100) green
        @else blue @endif
    }

    #revistas {
        color: @if(100 * $relatorio->revistas / $relatorio->assist_total <= 50) red
        @elseif(100 * $relatorio->revistas / $relatorio->assist_total > 50 && 100 * $relatorio->revistas / $relatorio->assist_total <= 75) orange
        @elseif(100 * $relatorio->revistas / $relatorio->assist_total > 75 && 100 * $relatorio->revistas / $relatorio->assist_total <= 100) green
        @else blue @endif
    }

    .result {
        padding: 1px 3px;
        border-radius: 3px;
        border: 1px solid black;
        background-color: #ccc;
        font-weight: bolder
    }

    .caption {
        font-weight: bolder;
        border: 1px solid;
        border-radius: 15px;
        padding: 1px 4px;

    }

</style>
<body>
    <div class="container">
        <div class="center">
            <img src="img/logo-adpar.jpg" class="center-item" width="70">
            <h3 class="center-item" >Igreja Evangélica Assembleia de Deus em Parnamirim/RN <br> <span style="font-size: 12px; font-weight: lighter">Departamento de Escola Bíblica Dominical </span></h3>
            <img class="center-item" src="img/logo_ebd.jpg" width="70">

        </div>

        <div class="infos">
            <p>Relatório de Frequências

            </p>
            <p >Data: <span style="font-weight: bolder">{{date('d/m/Y', strtotime($relatorio->created_at))}}</span></p>

        </div>
        <div class="tables">
            <table class="big-table">
                <thead>
                    <tr>
                        <th>Classe</th>
                        <th>Matriculados</th>
                        <th>Presentes</th>
                        <th>Visitantes</th>
                        <th>Assist. Total</th>
                        <th>Bíblias</th>
                        <th>Revistas</th>
                        <th>Horário de envio</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($relatorio->salas as $sala)
                    <tr>
                        <td>{{ $sala['nome'] }}</td>
                        <td>{{ $sala['matriculados'] }}</td>
                        <td>{{ $sala['presentes'] }}</td>
                        <td>{{ $sala['visitantes'] }}</td>
                        <td>{{ $sala['assist_total'] }}</td>
                        <td>{{ $sala['biblias'] }}</td>
                        <td>{{ $sala['revistas'] }}</td>
                        <td>{{ date('H:i:s', strtotime($sala['created_at'])) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>Total: </td>
                        <td>{{ $relatorio->matriculados }}</td>
                        <td>{{ $relatorio->presentes }}</td>
                        <td>{{ $relatorio->visitantes }}</td>
                        <td>{{ $relatorio->assist_total }}</td>
                        <td>{{ $relatorio->biblias }}</td>
                        <td>{{ $relatorio->revistas }}</td>
                        <td>*</td>
                    </tr>

                </tbody>
            </table>
        </div>

{{--        <div class="resume">--}}

{{--            <div>Legenda: <br><br>--}}
{{--                <div class="center">--}}
{{--                 <span class="caption" style="background-color: red;color:red  ">F</span> = Ruim/Péssimo |--}}
{{--                 <span class="caption" style="background-color: orange; color:orange ">F</span> = Médio |--}}
{{--                 <span class="caption" style="background-color: green;color:green ">F</span> = Bom |--}}
{{--                 <span class="caption" style="background-color: blue;color:blue ">F</span> = Muito Bom/Excelente--}}
{{--            </div>--}}
{{--            </p>--}}

{{--        </div>--}}


    </div>



        <p class="small">Documento gerado automaticamente em <span class="span-emphasis">{{date('d/m/Y')}}</span> às <span class="span-emphasis">{{date('H:i:s')}}</span>, pelo sistema de administração <span class="span-emphasis">EBDControl</span></p>

</body>
</html>
