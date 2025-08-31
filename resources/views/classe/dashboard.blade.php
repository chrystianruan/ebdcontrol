@extends('layouts.mainClasse')

@section('title', 'Início')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="stylesheet" href="/css/inicioClasse.css">
<div id="retorno" class="retorno">
    <p></p>
</div>
@if($chamadaDia->count() == 0 && date('w') == 0 || $chamadaDia->count() == 0 && date('Y-m-d') == $dateChamadaDia)
<div class="orientation">
    <div class="aaa">
        <p><i class="fa fa-exclamation-circle"></i>A chamada do dia ainda não foi realizada <a style="color: rgb(57, 235, 13)" href="/classe/chamada-dia"><i style=""class="fa fa-plus-circle"></i></a></p>
    </div>
</div>
@endif

<div class="dialog" id="modal-presencas">
            <table id="hidden-table">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Função</th>
                    <th>Data de nascimento</th>
                    <th>Presenças</th>
                </tr>
                </thead>
                <tbody id="hidden-tbody-data">

                </tbody>
            </table>
</div>

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
                @if( ($chamadaDia -> sum('biblias') * 100) / $chamadaDia->sum('presentes') + $chamadaDia->sum('visitantes') >= 80) chartreuse
                    @elseif( ($chamadaDia -> sum('biblias') * 100) / $chamadaDia->sum('presentes') + $chamadaDia->sum('visitantes') >= 50
                    && ($chamadaDia->sum('biblias') * 100) / $chamadaDia->sum('presentes') + $chamadaDia->sum('visitantes') < 80) yellow
                    @else red
                    @endif
                    ">{{number_format((($chamadaDia -> sum('biblias') * 100) / ($chamadaDia->sum('presentes') + $chamadaDia->sum('visitantes')) ), 1, ',')}}%</span> trouxeram</li>
            <li>Revistas: <span style="font-weight: bold; color:
                @if( ($chamadaDia -> sum('revistas') * 100) / $chamadaDia->sum('presentes') + $chamadaDia->sum('visitantes')  > 80) chartreuse
                    @elseif( ($chamadaDia -> sum('revistas') * 100) / $chamadaDia->sum('presentes') + $chamadaDia->sum('visitantes')  >= 50
                    && ($chamadaDia -> sum('revistas') * 100) / $chamadaDia->sum('presentes') + $chamadaDia->sum('visitantes')  < 80) yellow
                    @else red
                    @endif
                    ">{{number_format((($chamadaDia -> sum('revistas') * 100) / ($chamadaDia->sum('presentes') + $chamadaDia->sum('visitantes')) ), 1, ',')}}%</span> trouxeram</li>
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
                @if( ($chamadasMes -> sum('biblias') * 100) / $chamadasMes->sum('presentes') + $chamadasMes->sum('visitantes')  >= 80) chartreuse
                    @elseif( ($chamadasMes -> sum('biblias') * 100) / $chamadasMes->sum('presentes') + $chamadasMes->sum('visitantes')  >= 50
                    && ($chamadasMes -> sum('biblias') * 100) / $chamadasMes->sum('presentes') + $chamadasMes->sum('visitantes')  < 80) yellow
                    @else red
                    @endif
                    ">{{number_format((($chamadasMes -> sum('biblias') * 100) / ($chamadasMes->sum('presentes') + $chamadasMes->sum('visitantes')) ), 1, ',')}}%</span> trouxeram</li>
            <li>Revistas: <span style="font-weight: bold; color:
                @if( ($chamadasMes -> sum('revistas') * 100) / $chamadasMes->sum('presentes') + $chamadasMes->sum('visitantes')  > 80) chartreuse
                    @elseif( ($chamadasMes -> sum('revistas') * 100) / $chamadasMes->sum('presentes') + $chamadasMes->sum('visitantes')  >= 50
                    && ($chamadasMes -> sum('revistas') * 100) / $chamadasMes->sum('presentes') + $chamadasMes->sum('visitantes')  < 80) yellow
                    @else red
                    @endif
                    ">{{number_format((($chamadasMes -> sum('revistas') * 100) / ($chamadasMes->sum('presentes') + $chamadasMes->sum('visitantes')) ), 1, ',')}}%</span> trouxeram</li>
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
        @if( ($chamadasAno -> sum('biblias') * 100) / $chamadasAno->sum('presentes') + $chamadasAno->sum('visitantes')  >= 80) chartreuse
            @elseif( ($chamadasAno -> sum('biblias') * 100) / $chamadasAno->sum('presentes') + $chamadasAno->sum('visitantes')  >= 50
            && ($chamadasAno -> sum('biblias') * 100) / $chamadasAno->sum('presentes') + $chamadasAno->sum('visitantes')  < 80) yellow
            @else red
            @endif
            ">{{number_format((($chamadasAno -> sum('biblias') * 100) / ($chamadasAno->sum('presentes') + $chamadasAno->sum('visitantes')) ), 1, ',')}}%</span> trouxeram</li>
    <li>Revistas: <span style="font-weight: bold; color:
        @if( ($chamadasAno -> sum('revistas') * 100) / $chamadasAno->sum('presentes') + $chamadasAno->sum('visitantes')  > 80) chartreuse
            @elseif( ($chamadasAno -> sum('revistas') * 100) / $chamadasAno->sum('presentes') + $chamadasAno->sum('visitantes')  >= 50
            && ($chamadasAno -> sum('revistas') * 100) / $chamadasAno->sum('presentes') + $chamadasAno->sum('visitantes')  < 80) yellow
            @else red
            @endif
            ">{{number_format((($chamadasAno -> sum('revistas') * 100) / ($chamadasAno->sum('presentes') + $chamadasAno->sum('visitantes')) ), 1, ',')}}%</span> trouxeram</li>
        @else
        <li>Nenhum Relatório</li>
        @endif
    </div>

    <div class="info">
        <input type="hidden" id="url-get-chamadas" value="{{ route('relatorios.presenca-classe-post') }}">
        <input type="hidden" id="classe" value="{{ base64_encode(auth()->user()->sala_id) }}">
        <h2>Relatório de presenças</h2> <hr  style="margin-bottom: 2%">

        <h3>Data início</h3>

        <input type="date" class="input-date" name="initial_date" id="initial_date" required>
        <h3>Data fim</h3>
        <input type="date" class="input-date" name="final_date" id="final_date" required>
        <h3></h3>
        <a>
        <button id="baixar-relatorio" class="btn-visualizar-relatorio" style="">Baixar relatório</button>
        </a>
    </div>

    <div class="info" >
        <h2>Importante</h2> <hr  style="margin-bottom: 2%">
        <h3>Código da Classe</h3>
        <li><span style="font-weight: bold; color:chartreuse">{{ $codigoClasse}}</span></li>
        <h3>Aniversariantes do mês ({{date('m')}})</h3>
        <li>@if($niverMes->count() < 1) Nenhum aniversariante nesse mês @else <span style="font-weight: bold; color:chartreuse">{{$niverMes->count()}}</span> nesse mês @endif <a style="color: deepskyblue" href="/classe/aniversariantes"> Aniversariantes </a> </li>
        <h3>Interessados em ser professor</h3>
        <li>@if($interesseProf->count() < 1) Nenhum interessado @else <span style="font-weight: bold; color:chartreuse">{{$interesseProf->count()}}</span> interessado(s) @endif</li>
        <h3>Inativos</h3>
        <li>@if($alunosInativos->count() < 1) Nenhum aluno inativo @else <span style="font-weight: bold; color:chartreuse">{{$alunosInativos->count()}}</span> inativo(s) @endif</li>

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js" integrity="sha512-2/YdOMV+YNpanLCF5MdQwaoFRVbTmrJ4u4EpqS/USXAQNUDgI5uwYi6J98WVtJKcfe1AbgerygzDFToxAlOGEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    const { jsPDF } = window.jspdf;
</script>
<script src="/js/relatorio-presenca.js"></script>
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
            labels: [@foreach($funcoes as $fun) '{{ $fun['funcao_nome'] }}', @endforeach],
            datasets: [{
                label: 'Quantidade',
                data: [@foreach($funcoes as $fun) {{ $fun['quantidade_pessoas'] }}, @endforeach],
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
