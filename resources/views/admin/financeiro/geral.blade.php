@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/financeiro.css">
<div class="divPositions">

<div class="positions">
    

    <div class="nivers" style="background-color: transparent; border: none; box-shadow: none">
    <h1 style="font-size: 35px;color: #ccc; text-shadow: #fff 0 1px; border: 0.2px solid #ccc; box-shadow: 1px 0.5px #ccc; padding: 10px; border-radius: 10px ">Saldo Atual: 
    <font style="background-color:blue; color: white; border-radius: 10px; padding: 1px"> R$ {{number_format($entradas->sum('valor') - $saidas->sum('valor'), 2, ",", "." )}} </font></h1>
    </div>

    <div class="nivers" >
    <h2 style="color: #ccc; text-shadow: #fff 0 1px; text-align:center">Ano ({{date('Y')}}) </h2> <hr style="margin-bottom: 5px;">
    <h3> <font style="color: green"> (+) Entradas</font> :  @if($entradasAno->count() > 0) <font style="background-color: green; border-radius: 5px; padding: 0.5px"> R$ {{number_format($entradasAno -> sum('valor') , 2, ",", "." )}}</font>  @else Nenhuma entrada  @endif</h3>
    <h3> <font style="color: red">(-) Saídas </font>:  @if($saidasAno->count() > 0) <font style="background-color: red; border-radius: 5px; padding: 0.5px"> R$ {{number_format($saidasAno -> sum('valor') , 2, ",", "." )}}</font>  @else Nenhuma Saída  @endif</h3>
    <hr style="margin: 5%">
    <h3> <font style="color: yellow">(=) Saldo </font>: <font style="background-color: yellow; color: @if($entradasAno->sum('valor') - $saidasAno->sum('valor') > 0) green; @elseif($entradasAno->sum('valor') - $saidasAno->sum('valor') < 0) red; @else black; @endif border-radius: 5px; padding: 0.5px"> R$ {{number_format($entradasAno->sum('valor') - $saidasAno->sum('valor'), 2, ",", "." )}}</font></h3>
    </div>

    

    <div class="nivers" >
    <h2 style="color: #ccc; text-shadow: #fff 0 1px; text-align:center">Mês ({{date('m/Y')}}) </h2> <hr style="margin-bottom: 5px">
    <h3> <font style="color: green"> (+) Entradas</font> :  @if($entradasMes->count() > 0) <font style="background-color: green; border-radius: 5px; padding: 0.5px"> R$ {{number_format($entradasMes -> sum('valor') , 2, ",", "." )}}</font>  @else Nenhuma entrada  @endif</h3>
    <h3> <font style="color: red">(-) Saídas </font>:  @if($saidasMes->count() > 0) <font style="background-color: red; border-radius: 5px; padding: 0.5px"> R$ {{number_format($saidasMes -> sum('valor') , 2, ",", "." )}}</font>  @else Nenhuma Saída  @endif</h3>
    <hr style="margin: 5%">
    <h3> <font style="color: yellow">(=) Saldo </font>: <font style="background-color: yellow; color: @if($entradasMes->sum('valor') - $saidasMes->sum('valor') > 0) green; @elseif($entradasMes->sum('valor') - $saidasMes->sum('valor') < 0) red; @else black; @endif; border-radius: 5px; padding: 0.5px"> R$ {{number_format($entradasMes->sum('valor') - $saidasMes->sum('valor'), 2, ",", "." )}}</font></h3>
    </div>

</div>
</div>

<div class="positions2">


    <div class="graficos" >
    <canvas id="myChart"  height=100></canvas>
    </div>

    <div class="twoGraphics">

        <div class="graficos2">
        <canvas id="myChart1" class="Gpie2" ></canvas>
        </div>

        <div class="graficos2">
        <canvas id="myChart2" class="Gpie2" ></canvas>
        </div>

    </div>

</div>


    


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
<script>
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    
    data: {
       
        labels: ['Janeiro/{{date('y')}}', 
        'Fevereiro/{{date('y')}}' , 
        'Março/{{date('y')}}', 
        'Abril/{{date('y')}}', 
        'Maio/{{date('y')}}', 
        'Junho/{{date('y')}}', 
        'Julho/{{date('y')}}',
        'Agosto/{{date('y')}}',
        'Setembro/{{date('y')}}',
        'Outubro/{{date('y')}}',
        'Novembro/{{date('y')}}',
        'Dezembro/{{date('y')}}'],
        datasets: [{
            type: 'line',
            label: 'Entrada (R$)',
            data: [@foreach($mesesE as $mesE) {{$mesE}}, @endforeach],
            backgroundColor: [
                'green'
            ],
            borderColor: [
                'green'
            
            ],
            borderWidth: 1,
            tension: 0.3
        }, {
            type: 'line',
            label: 'Saída (R$)',
            data: [@foreach($mesesS as $mesS) {{$mesS}}, @endforeach],
            backgroundColor: [
                'red'
            ],
            borderColor: [
                'red'
            
            ],
            borderWidth: 1,
            tension: 0.3



        }, {
            type: 'line',
            label: 'Saldo (R$)',
            data: [@foreach($saldosMeses as $saldos) {{$saldos}}, @endforeach],
            backgroundColor: [
                'yellow'
            ],
            borderColor: [
                'yellow'
            
            ],
            borderWidth: 1,
            tension: 0.3



        }]
    
    

    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

const ctx1 = document.getElementById('myChart1');
const myChart1 = new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: [@foreach($catsEnt as $catEnt) '{{$catEnt->nome}} (R$)', @endforeach],
        datasets: [{
            label: 'Entradas ({{date('Y')}}) - Categorias x Valor(R$)',
            data: [@foreach($catsEnt as $catEnt) {{$catEnt->somaE}}, @endforeach],
            fill: true,
            backgroundColor: 'green',
            borderColor: 'green',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

const ctx2 = document.getElementById('myChart2');
const myChart2 = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: [@foreach($catsSaida as $catSaida) '{{$catSaida->nome}} (R$)', @endforeach],
        datasets: [{
            label: 'Saídas ({{date('Y')}}) - Categorias x Valor(R$)',
            data: [@foreach($catsSaida as $catSaida) {{$catSaida->somaS}}, @endforeach],
            backgroundColor: 'red',
            borderColor: 'red',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});


</script>
@endsection