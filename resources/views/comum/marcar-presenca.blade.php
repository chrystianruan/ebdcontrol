@extends('layouts.main-comum')

@section('title', 'Início')

@section('content')
    <h4>Marcar Presença</h4>
    @if (!$presente)
    <div class="alert alert-primary">
        <i class="bx bxs-info-circle" style="font-size: 1.2em"></i>
        Para marcar a presença de forma individual, deve-se conceder permissão a localização e preencher o código da classe corretamente.
    </div>
    <form method="POST" action="/comum/marcar-presenca">
        @csrf
        <input type="hidden" id="latitude" name="latitude" value="">
        <input type="hidden" id="longitude" name="longitude" value="">
        <div class="card">
            <div class="card-header">
                <h5>Dados da Presença</h5>
            </div>
            <div class="card-body">
                @if(count($pessoaSalas) > 1)
                    <div class="mb-3">
                        <label class="form-label">Selecione o vínculo desejado</label>
                        <select class="form-select" name="pessoa_sala">
                            <option selected value="" disabled>-- SELECIONE -- </option>
                            @foreach($pessoaSalas as $pessoaSala)
                                <option value="{{ $pessoaSala->id }}">{{ $pessoaSala->sala_nome }} ({{ $pessoaSala->sala_tipo }}) - {{ $pessoaSala->funcao_nome }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="sala" value="{{ $pessoaSalas[0]->sala_id }}">
                    <input type="hidden" name="funcao" value="{{ $pessoaSalas[0]->funcao_id }}">
                    <ul>
                        <li> <span class="fw-bold">Nome: </span> {{ $pessoaSalas[0]->pessoa_nome }} </li>
                        <li> <span class="fw-bold">Classe: </span>{{ $pessoaSalas[0]->sala_nome }} ({{ $pessoaSalas[0]->sala_tipo }}) </li>
                        <li> <span class="fw-bold">Função: </span>{{ $pessoaSalas[0]->funcao_nome }}</li>
                    </ul>
                @endif
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" aria-describedby="textHelp" required value="{{ old('codigo') }}">
                        <div id="textHelp" class="form-text">Digite o código disponibilizado pelo Secretário de Classe ou Professor.</div>
                    </div>
            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" id="btn-marcar-presenca">Marcar Presença</button>
                </div>
            </div>
        </div>
    </form>
    <script src="/js/getLocation.js"></script>
    @else
        <div class="alert alert-success">
            <i class="bx bxs-check-circle" style="font-size: 1.2em"></i>
            Presença do dia já foi marcada com sucesso!
        </div>
    @endif
@endsection
