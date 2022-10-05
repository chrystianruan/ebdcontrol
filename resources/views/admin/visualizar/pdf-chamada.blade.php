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
        margin: 10px auto;
        position: relative;
    }
    .center {
        text-align: center

    }

    .center p {
        margin: 0px 5px;
    }


    .infos {
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

    .tables {
        position: relative;
    
    }
    .big-table {
        position: relative;
        width: 85%;
        float: left;
    }

    .small-table {
        position: relative;
        width: 15%;
        float: left;
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
        color: @if(100 * $chamada->presentes / $chamada->matriculados <= 50) red 
        @elseif(100 * $chamada->presentes / $chamada->matriculados > 50 && 100 * $chamada->presentes / $chamada->matriculados <= 75) orange
        @elseif(100 * $chamada->presentes / $chamada->matriculados > 75 && 100 * $chamada->presentes / $chamada->matriculados <= 100) green
        @else blue @endif
    }

    #assist_total {
        color: @if(100 * $chamada->assist_total / $chamada->matriculados <= 50) red 
        @elseif(100 * $chamada->assist_total / $chamada->matriculados > 50 && 100 * $chamada->assist_total / $chamada->matriculados <= 75) orange
        @elseif(100 * $chamada->assist_total / $chamada->matriculados > 75 && 100 * $chamada->assist_total / $chamada->matriculados <= 100) green
        @else blue @endif
    }

    #biblias {
        color: @if(100 * $chamada->biblias / $chamada->assist_total <= 50) red 
        @elseif(100 * $chamada->biblias / $chamada->assist_total > 50 && 100 * $chamada->biblias / $chamada->assist_total <= 75) orange
        @elseif(100 * $chamada->biblias / $chamada->assist_total > 75 && 100 * $chamada->biblias / $chamada->assist_total <= 100) green
        @else blue @endif
    }

    #revistas {
        color: @if(100 * $chamada->revistas / $chamada->assist_total <= 50) red 
        @elseif(100 * $chamada->revistas / $chamada->assist_total > 50 && 100 * $chamada->revistas / $chamada->assist_total <= 75) orange
        @elseif(100 * $chamada->revistas / $chamada->assist_total > 75 && 100 * $chamada->revistas / $chamada->assist_total <= 100) green
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
    
                <h3>Igreja Evangélica Assembleia de Deus em Parnamirim/RN</h3>
                <h4>Departamento de Escola Bíblica Dominical</h4>

        
        </div>
        <div class="infos">
            <p>Frequência da classe: <span style="font-weight: bolder">{{ $chamada->nome }}</span>
            
            </p>
            <p >Data: <span style="font-weight: bolder">{{date('d/m/Y', strtotime($chamada->created_at))}}</span></p>
        
        </div>
        <div class="tables">
            <table class="big-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                    </tr>
                </thead>

                <tbody>
                   
                    @foreach($chamada->nomes as $pessoa)
                    <tr>
                        <td> {{ $pessoa['nome'] }}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            <table class="small-table">
                <thead>
                    <tr>
                        <th>Presença</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($chamada->presencas as $presenca)
                    <tr>
                        <td style="text-align: center">@if($presenca == 1) <span style="color: green; font-weight: bolder">Sim</span> @else <span style="color: red; font-weight: bolder">Não</span> @endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
   
        <div class="resume">

            <p>Matriculados: <span class="result">{{ $chamada->matriculados }}</span></p>
            <p>Presentes: <span class="result">{{ $chamada->presentes }}</span> <span class="color" id="presentes">({{  number_format(100 * $chamada->presentes / $chamada->matriculados, 1, ',', '.') }}%)</span> </p> 
            <p>Visitantes: <span class="result">{{ $chamada->visitantes }}</span></p> 
            <p>Assistência total: <span class="result">{{ $chamada->assist_total }}</span> <span class="color" id="assist_total">({{  number_format(100 * $chamada->assist_total / $chamada->matriculados, 1, ',', '.') }}%)</span> </p> 
            <p>Bíblias: <span class="result">{{ $chamada->biblias }}</span> <span class="color" id="biblias">({{  number_format(100 * $chamada->biblias / $chamada->assist_total, 1, ',', '.') }}%)</span> </p> 
            <p>Revistas: <span class="result">{{ $chamada->revistas }}</span>  <span class="color" id="revistas">({{ number_format(100 * $chamada->revistas / $chamada->assist_total, 1, ',', '.') }}%)</span> </p> 
    
            <hr>

            <div>Legenda: <br><br>
                <div class="center">
                 <span class="caption" style="background-color: red;color:red  ">F</span> = Ruim/Péssimo |
                 <span class="caption" style="background-color: orange; color:orange ">F</span> = Médio |
                 <span class="caption" style="background-color: green;color:green ">F</span> = Bom |
                 <span class="caption" style="background-color: blue;color:blue ">F</span> = Muito Bom/Excelente
            </div>
            </p> 
        
        </div>

    
    </div>


     
        <p class="small">Documento gerado automaticamente em <span class="span-emphasis">{{date('d/m/Y')}}</span> às <span class="span-emphasis">{{date('H:i:s')}}</span>, pelo sistema de administração <span class="span-emphasis">EBDControl</span></p>

</body>
</html>