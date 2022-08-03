@extends('layouts.mainClasse')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/inicioClasse.css">
@if($chamadaDia->count() == 0)
<div class="orientation">
    <div class="aaa">
        <p><i class="fa fa-exclamation-circle"></i>A chamada do dia ainda não foi realizada <a style="color: rgb(57, 235, 13)" href="/classe/chamada-dia"><i style=""class="fa fa-plus-circle"></i></a></p>
    </div>
</div>
@endif
<div class="grid-container">
   
    <div class="info">
        <h2>Relatório do dia</h2>
        <hr>
        @if($chamadaDia->count() == 1)
            <h3>Matriculados: </h3>
                <p><span style="font-weight: bold; color: 
                @if( ($chamadaDia -> sum('presentes') * 100) / $chamadaDia -> sum('matriculados') >= 80) chartreuse 
                @elseif( ($chamadaDia -> sum('presentes') * 100) / $chamadaDia -> sum('matriculados') >= 50 
                && ($chamadaDia -> sum('presentes') * 100) / $chamadaDia -> sum('matriculados') < 80) yellow
                @else red
                @endif 
                ">{{number_format((($chamadaDia -> sum('presentes') * 100) / $chamadaDia -> sum('matriculados')), 1, ',')}}% </span> se fizeram presentes </p>
            <h3>Visitantes: </h3>
            <p>A sala recebeu <span style="font-weight: bold; color: chartreuse"> @if($chamadaDia -> sum('visitantes') > 0)+@endif{{$chamadaDia -> sum('visitantes')}}</span> visitante(s)</p>
            <h3>Bíblias e revistas</h3>
            
            <li>Bíblias: <span style="font-weight: bold; color: 
                @if( ($chamadaDia -> sum('biblias') * 100) / $chamadaDia -> sum('assist_total') >= 80) chartreuse 
                    @elseif( ($chamadaDia -> sum('biblias') * 100) / $chamadaDia -> sum('assist_total') >= 50 
                    && ($chamadaDia -> sum('biblias') * 100) / $chamadaDia -> sum('assist_total') < 80) yellow
                    @else red
                    @endif 
                    ">{{number_format((($chamadaDia -> sum('biblias') * 100) / $chamadaDia -> sum('assist_total')), 1, ',')}}%</span> trouxeram</li>
            <li>Revistas: <span style="font-weight: bold; color: 
                @if( ($chamadaDia -> sum('revistas') * 100) / $chamadaDia -> sum('assist_total') > 80) chartreuse 
                    @elseif( ($chamadaDia -> sum('revistas') * 100) / $chamadaDia -> sum('assist_total') >= 50 
                    && ($chamadaDia -> sum('revistas') * 100) / $chamadaDia -> sum('assist_total') < 80) yellow
                    @else red
                    @endif 
                    ">{{number_format((($chamadaDia -> sum('revistas') * 100) / $chamadaDia -> sum('assist_total')), 1, ',')}}%</span> trouxeram</li>
        @else 
        <li>Nenhum Relatório</li>
        @endif
    </div>
    
   
    <div class="info">
        <h2>Relatório do mês ({{date('m')}})</h2>
        <hr>
        @if($chamadasMes->count() > 0)
        <h3>Matriculados: </h3>
                <p><span style="font-weight: bold; color: 
                @if( ($chamadasMes -> sum('presentes') * 100) / $chamadasMes -> sum('matriculados') >= 80) chartreuse 
                @elseif( ($chamadasMes -> sum('presentes') * 100) / $chamadasMes -> sum('matriculados') >= 50 
                && ($chamadasMes -> sum('presentes') * 100) / $chamadasMes -> sum('matriculados') < 80) yellow
                @else red
                @endif 
                ">{{number_format((($chamadasMes -> sum('presentes') * 100) / $chamadasMes -> sum('matriculados')), 1, ',')}}% </span> se fizeram presentes </p>
            <h3>Visitantes: </h3>
            <p>A sala recebeu <span style="font-weight: bold; color: chartreuse"> @if($chamadasMes -> sum('visitantes') > 0)+@endif{{$chamadasMes -> sum('visitantes')}}</span> visitante(s)</p>
            <h3>Bíblias e revistas</h3>
            
            <li>Bíblias: <span style="font-weight: bold; color: 
                @if( ($chamadasMes -> sum('biblias') * 100) / $chamadasMes -> sum('assist_total') >= 80) chartreuse 
                    @elseif( ($chamadasMes -> sum('biblias') * 100) / $chamadasMes -> sum('assist_total') >= 50 
                    && ($chamadasMes -> sum('biblias') * 100) / $chamadasMes -> sum('assist_total') < 80) yellow
                    @else red
                    @endif 
                    ">{{number_format((($chamadasMes -> sum('biblias') * 100) / $chamadasMes -> sum('assist_total')), 1, ',')}}%</span> trouxeram</li>
            <li>Revistas: <span style="font-weight: bold; color: 
                @if( ($chamadasMes -> sum('revistas') * 100) / $chamadasMes -> sum('assist_total') > 80) chartreuse 
                    @elseif( ($chamadasMes -> sum('revistas') * 100) / $chamadasMes -> sum('assist_total') >= 50 
                    && ($chamadasMes -> sum('revistas') * 100) / $chamadasMes -> sum('assist_total') < 80) yellow
                    @else red
                    @endif 
                    ">{{number_format((($chamadasMes -> sum('revistas') * 100) / $chamadasMes -> sum('assist_total')), 1, ',')}}%</span> trouxeram</li>
        @else 
        <li>Nenhum Relatório</li>
        @endif
   
    </div>
   
    <div class="info">
        <h2>Relatório do ano ({{date('Y')}})</h2>
        <hr>
        @if($chamadasAno->count() > 0)
        <h3>Matriculados: </h3>
        <p><span style="font-weight: bold; color: 
        @if( ($chamadasAno -> sum('presentes') * 100) / $chamadasAno -> sum('matriculados') >= 80) chartreuse 
        @elseif( ($chamadasAno -> sum('presentes') * 100) / $chamadasAno -> sum('matriculados') >= 50 
        && ($chamadasAno -> sum('presentes') * 100) / $chamadasAno -> sum('matriculados') < 80) yellow
        @else red
        @endif 
        ">{{number_format((($chamadasAno -> sum('presentes') * 100) / $chamadasAno -> sum('matriculados')), 1, ',')}}% </span> se fizeram presentes </p>
    <h3>Visitantes: </h3>
    <p>A sala recebeu <span style="font-weight: bold; color: chartreuse"> @if($chamadasAno -> sum('visitantes') > 0)+@endif{{$chamadasAno -> sum('visitantes')}}</span> visitante(s)</p>
    <h3>Bíblias e revistas</h3>
    
    <li>Bíblias: <span style="font-weight: bold; color: 
        @if( ($chamadasAno -> sum('biblias') * 100) / $chamadasAno -> sum('assist_total') >= 80) chartreuse 
            @elseif( ($chamadasAno -> sum('biblias') * 100) / $chamadasAno -> sum('assist_total') >= 50 
            && ($chamadasAno -> sum('biblias') * 100) / $chamadasAno -> sum('assist_total') < 80) yellow
            @else red
            @endif 
            ">{{number_format((($chamadasAno -> sum('biblias') * 100) / $chamadasAno -> sum('assist_total')), 1, ',')}}%</span> trouxeram</li>
    <li>Revistas: <span style="font-weight: bold; color: 
        @if( ($chamadasAno -> sum('revistas') * 100) / $chamadasAno -> sum('assist_total') > 80) chartreuse 
            @elseif( ($chamadasAno -> sum('revistas') * 100) / $chamadasAno -> sum('assist_total') >= 50 
            && ($chamadasAno -> sum('revistas') * 100) / $chamadasAno -> sum('assist_total') < 80) yellow
            @else red
            @endif 
            ">{{number_format((($chamadasAno -> sum('revistas') * 100) / $chamadasAno -> sum('assist_total')), 1, ',')}}%</span> trouxeram</li>
        @else 
        <li>Nenhum Relatório</li>
        @endif
    </div>
    
    <div class="info" >
        <h2>Importante</h2> <hr  style="margin-bottom: 2%">
        <h3>Aniversariantes do mês ({{date('m')}})</h3>
        <li>@if($niverMes < 1) Nenhum aniversariante nesse mês @else <span style="font-weight: bold; color:chartreuse">{{$niverMes}}</span> nesse mês @endif <a style="color: deepskyblue" href="/classe/aniversariantes"> Aniversariantes </a> </li>
        <h3>Interessados em ser professor</h3>
        <li>@if($interesseProf < 1) Nenhum interessado @else <span style="font-weight: bold; color:chartreuse">{{$interesseProf}}</span> interessado(s) @endif</li>
        <h3>Inativos</h3>
        <li>@if($alunosInativos < 1) Nenhum aluno inativo @else <span style="font-weight: bold; color:chartreuse">{{$alunosInativos}}</span> inativo(s) @endif</li>
      
      </div>
      
    

 <div class="graficoX">
        <canvas id="myChartX" width="500" height="500"></canvas>
</div>
            
<div class="graficoY">
<canvas id="myChart" width="500" height="500"></canvas>
</div>



<div class="graficoY">
<canvas id="myChart2" width="500" height="500"></canvas>
</div>

<div class="graficoY">
    <canvas id="myChart3" width="500" height="500"></canvas>
</div>



</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [@foreach($idadesPessoas as $idades) '{{$idades -> idades}} anos', @endforeach],
            datasets: [{
                label: 'Quantidade',
                data: [@foreach($idadesPessoas as $idades) {{$idades -> qtd}}, @endforeach],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
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

    const ctx2 = document.getElementById('myChart2').getContext('2d');
    const myChart2 = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: [@foreach($formacoes as $f) '{{$f -> nome}}', @endforeach],
            datasets: [{
                label: 'Quantidade',
                data: [@foreach($formacoes as $f) {{$f -> qtdPessoas}}, @endforeach],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
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

    const ctx3 = document.getElementById('myChart3').getContext('2d');
    const myChart3 = new Chart(ctx3, {
        type: 'pie',
        data: {
            labels: [@foreach($funcoes as $fun) '{{$fun -> nome}}', @endforeach],
            datasets: [{
                label: 'Quantidade',
                data: [@foreach($funcoes as $fun) {{$fun -> qtd}}, @endforeach],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
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

    const ctxX = document.getElementById('myChartX').getContext('2d');
    const myChartX = new Chart(ctxX, {
        data: {
            datasets: [{
                type: 'bar',
                label: 'Matriculados',
                data: [@foreach($chamadasMes as $cM) {{$cM -> matriculados}} ,@endforeach],
                backgroundColor: 'rgba(255, 50, 100, 0.8)'
            }, {
                type: 'line',
                label: 'Presentes',
                data: [@foreach($chamadasMes as $cM)  {{$cM -> presentes}}, @endforeach],
                backgroundColor: 'rgba(255, 200, 50, 0.9)',
                borderColor:'rgba(255, 200, 50, 0.9)'
            }],
            labels: [@foreach($chamadasMes as $cM) '{{date('d/m/Y', strtotime($cM -> created_at))}}', @endforeach]
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