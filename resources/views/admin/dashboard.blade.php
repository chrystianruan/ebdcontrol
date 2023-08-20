@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/inicio.css">

<div class="grid-container">
    <div class="info">
    <h2>Relatório do dia</h2>
    <hr>
    @if($chamadaDia->count() > 0)
        <h3>Matriculados: </h3>
            <p><span style="font-weight: bold; color:
            @if( ($chamadaDia -> sum('presentes') * 100) / $chamadaDia -> sum('matriculados') >= 80) chartreuse
            @elseif( ($chamadaDia -> sum('presentes') * 100) / $chamadaDia -> sum('matriculados') >= 50
            && ($chamadaDia -> sum('presentes') * 100) / $chamadaDia -> sum('matriculados') < 80) yellow
            @else red
            @endif
            ">{{number_format((($chamadaDia -> sum('presentes') * 100) / $chamadaDia -> sum('matriculados')), 1, ',')}}% </span> se fizeram presentes </p>
        <h3>Visitantes: </h3>
        <p>As salas receberam <span style="font-weight: bold; color: chartreuse"> @if($chamadaDia -> sum('visitantes') > 0)+@endif {{ $chamadaDia -> sum('visitantes') }}</span> visitante(s)</p>
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
        <p>As salas receberam <span style="font-weight: bold; color: chartreuse"> @if($chamadasMes -> sum('visitantes') > 0)+@endif{{$chamadasMes -> sum('visitantes')}}</span> visitante(s)</p>
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
<p>As salas receberam <span style="font-weight: bold; color: chartreuse"> @if($chamadasAno -> sum('visitantes') > 0)+@endif{{$chamadasAno -> sum('visitantes')}}</span> visitante(s)</p>
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
  <li>@if($niverMes < 1) Nenhum aniversariante nesse mês @else <span style="font-weight: bold; color:chartreuse">{{$niverMes}}</span> nesse mês @endif <a style="color: deepskyblue" href="/admin/aniversariantes"> Aniversariantes </a> </li>
  <h3>Interessados em ser professor</h3>
  <li>@if($interesseProf < 1) Nenhum interessado @else <span style="font-weight: bold; color:chartreuse">{{$interesseProf}}</span> interessado(s) @endif</li>
  <h3>Inativos</h3>
  <li>@if($alunosInativos < 1) Nenhum aluno inativo @else <span style="font-weight: bold; color:chartreuse">{{$alunosInativos}}</span> inativo(s) @endif</li>

</div>

    <div class="info" >
        <h2>Chamadas Físicas</h2> <hr  style="margin-bottom: 2%">
        <h3>Classe</h3>
       <select class="select-classe" name="classe" id="select-classe" required>
            <option selected disabled value="">Selecionar</option>
           @foreach($salas as $sala)
               <option value="{{ $sala->id }}"> {{ $sala->nome }}</option>
           @endforeach
       </select>

        <h3>Data</h3>

        <input type="date" class="input-date" name="date" id="date" required>
        <h3></h3>
        <a id="a-visualizar-pdf" href="">
            <button class="btn-print">Gerar chamada física</button>
        </a>

    </div>


<div class="graficoY" >
<canvas id="myChart" width="1200" height="1200"></canvas>
</div>


<div class="graficosY" >
    <canvas id="myChartX"  width="1200" height="1200" ></canvas>
</div>



<div class="graficosY">
<canvas id="myChart1"></canvas>
</div>

<div class="graficosY">
<canvas id="myChart2" ></canvas>
</div>


<div class="graficoY" >
<canvas id="myChart3" ></canvas>
</div>

<div class="graficoY" >
    <canvas id="myChart4" ></canvas>
</div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
<script src="/js/dashboard-admin.js"></script>
<script>

const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [@foreach($meses as $mes) @foreach($mesesNome as $ind => $nome) @if($mes->mes == $ind) '{{$nome}}',  @endif @endforeach @endforeach],
        datasets: [{
            label: 'Quantidade de cadastrados - {{$dataAno}}',

            data: [@foreach($meses as $mes) @foreach($mesesNome as $ind => $nome) @if($mes->mes == $ind) {{$mes -> qtd}},  @endif @endforeach @endforeach],
            backgroundColor: [
                'rgb(0,255,255)'
            ],
            borderColor: [
                'rgb(0,255,255)'

            ],
            borderWidth: 1,
            tension: 0.1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },

    }
});

const ctx1 = document.getElementById('myChart1');
const myChart1 = new Chart(ctx1, {
    type: 'pie',
    data: {
        labels: ['Masculino - Ativo', 'Masculino - Inativo', 'Feminino - Ativo', 'Feminino - Inativo'],
        datasets: [{
            label: 'Sexo',
            data: [@foreach($sexos as $sex) {{$sex}}, @endforeach],
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 99, 132, 0.7)'

            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
            ],
            borderWidth: 2
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
    type: 'pie',
    data: {
        labels: [@foreach($funcoes as $funcao) '{{$funcao->nome}}', @endforeach ],
        datasets: [{
            label: 'Funções',
            data: [@foreach($funcoes as $funcao) {{$funcao->qtd}}, @endforeach ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)'

            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'

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

const ctx3 = document.getElementById('myChart3');
const myChart3 = new Chart(ctx3, {
    type: 'pie',
    data: {
        labels: [@foreach($formations as $for) '{{$for -> nome}}', @endforeach],
        datasets: [{
            label: 'Escolaridade',
            data: [@foreach($formations as $for) {{$for->qtd}}, @endforeach ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(0,255,255)',
                'rgba(0,250,154)',
                'rgb(0,255,0)',
                'rgb(192,192,192)',
                'rgb(255,0,255)',
                'rgb(255,255,0)',
                'rgb(128,0,128)',
                'rgb(128,0,0)',
                'rgb(0,128,128)',
                'rgb(255,255,0)',
                'rgb(128,0,128)',
                'rgb(128,0,0)'

            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(0,255,255)',
                'rgba(0,250,154)',
                'rgb(0,255,0)',
                'rgb(192,192,192)',
                'rgb(255,0,255)',
                'rgb(255,255,0)',
                'rgb(128,0,128)',
                'rgb(128,0,0)',
                'rgb(0,128,128)',
                'rgb(255,255,0)',
                'rgb(128,0,128)',
                'rgb(128,0,0)'


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

const ctx4 = document.getElementById('myChart4');
const myChart4= new Chart(ctx4, {
    type: 'pie',
    data: {
        labels: ["Pais", "Mães"],
        datasets: [{
            label: 'Escolaridade',
            data: [{{$quantidadePais}}, {{$quantidadeMaes}} ],
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(0,255,255)',
                'rgba(0,250,154)',
                'rgb(0,255,0)',
                'rgb(192,192,192)',
                'rgb(255,0,255)',
                'rgb(255,255,0)',
                'rgb(128,0,128)',
                'rgb(128,0,0)',
                'rgb(0,128,128)',
                'rgb(255,255,0)',
                'rgb(128,0,128)',
                'rgb(128,0,0)'

            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(0,255,255)',
                'rgba(0,250,154)',
                'rgb(0,255,0)',
                'rgb(192,192,192)',
                'rgb(255,0,255)',
                'rgb(255,255,0)',
                'rgb(128,0,128)',
                'rgb(128,0,0)',
                'rgb(0,128,128)',
                'rgb(255,255,0)',
                'rgb(128,0,128)',
                'rgb(128,0,0)'


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
                type: 'line',
                label: 'Matriculados',
                data: [@foreach($chamadasMesTotal as $cMT) {{$cMT -> mat}} ,@endforeach],
                backgroundColor: 'rgba(255, 50, 100, 0.8)',
                borderColor: 'rgba(255, 50, 100, 0.8)',
                tension: 0.2
            }, {
                type: 'line',
                label: 'Presentes',
                data: [@foreach($chamadasMesTotal as $cMT)  {{$cMT -> pre}}, @endforeach],
                backgroundColor: 'rgba(255, 200, 50, 0.9)',
                borderColor:'rgba(255, 200, 50, 0.9)',
                tension: 0.2
            },
            {
                type: 'line',
                label: 'Visitantes',
                data: [@foreach($chamadasMesTotal as $cMT)  {{$cMT -> vis}}, @endforeach],
                backgroundColor: 'rgba(255, 400, 300, 0.9)',
                borderColor:'rgba(255, 400, 300, 0.9)',
                tension: 0.2
            }],

            labels: [@foreach($chamadasMesTotal as $cMT) '{{($cMT -> data)}}', @endforeach]
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
