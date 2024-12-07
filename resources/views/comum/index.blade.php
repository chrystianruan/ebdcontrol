@extends('layouts.main-comum')

@section('title', 'Início')

@section('content')

    <h4>Dashboard</h4>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-sm">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title>
                            <i class="fas fa-user"></i>
                            <span>Vínculos</span>
                        <hr>
                        </h5>
                        <p class="card-text">
                            <ul>
                            @foreach($pessoaSalas as $pessoaSala)
                                <li><span style="font-weight: bold"> {{ $pessoaSala->sala_nome }} ({{ $pessoaSala->sala_tipo }})</span> - {{ $pessoaSala->funcao_nome }}</li>
                            @endforeach
                            </ul>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title>
                            <i class="fas fa-user"></i>
                        <span>Presenças</span>
                        <hr>
                        </h5>
                        <ul>
                            <li><span style="font-weight: bold"> No Mês </span>: {{ $quantidadePresencasMes }}</li>
                            <li><span style="font-weight: bold"> No Ano</span>: {{ $quantidadePresencasAno }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
