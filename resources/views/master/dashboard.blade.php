@extends('layouts.mainMaster')

@section('title', 'Início')

@section('content')
<link rel="stylesheet" href="/css/inicioClasse.css">
<div class="grid-container">




<div class="graficoY">
    <canvas id="myChart" width="500" height="500"></canvas>
</div>

<div class="graficoY">
    <canvas id="myChart2" width="500" height="500"></canvas>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [@foreach($qtdUsersAtivos as $qtdAtivos) '{{$qtdAtivos -> niveis}}', @endforeach],
            datasets: [{
                label: 'Quantidade de usuários ativos',
                data: [@foreach($qtdUsersAtivos as $qtdAtivos) {{$qtdAtivos -> qtd}}, @endforeach],
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
        type: 'bar',
        data: {
            labels: [@foreach($qtdUsersInativos as $qtdInativos) '{{$qtdInativos -> niveis}}', @endforeach],
            datasets: [{
                label: 'Quantidade de usuários inativos',
                data: [@foreach($qtdUsersInativos as $qtdInativos) {{$qtdInativos -> qtd}}, @endforeach],
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

</script>
@endsection
