@extends('layouts.mainMaster')

@section('title', 'Início')

@section('content')
    <style>
        #map { height: 350px }
    </style>
<link rel="stylesheet" href="/css/supermaster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />

<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

<link rel="stylesheet" href="/css/configuracaoMaster.css">
@include('templates.modal-obter-localizacao')
<div class="container-d">
    <div class="container-infos">
        <h3>Configurações - Congregação</h3>
        <hr>
        <h4>Dados Principais</h4>
        <div class="div-infos-congregacao">
            <input type="hidden" id="latitude_congregacao" value="{{ $congregacao->latitude }}">
            <input type="hidden" id="longitude_congregacao" value="{{ $congregacao->longitude }}">
            <div class="div-info-unique">
                <label>Setor</label>
                <input type="text" value="{{ $congregacao->congregacao }}" disabled>
            </div>
            <div class="div-info-unique">
                <label>Nome da congregação</label>
                <input type="text" value="{{ $congregacao->setor }}" disabled>
            </div>
            <div class="div-info-unique">
                <label>Quantidade de pessoas cadastradas</label>
                <input type="text" value="{{ $matriculados }}" disabled>
            </div>
            <div class="div-info-unique">
                <label>Quantidade de ativos</label>
                <input type="text" value="{{ $ativos }}" disabled>
            </div>
            <div class="div-info-unique">
                <label>Quantidade de inativos</label>
                <input type="text" value="{{ $inativos }}" disabled>
            </div>
        </div>
        <hr>
        <div style="display: flex; justify-content: space-between; flex-wrap: wrap; align-items: center">
            <span>
            <h4>Dados de Endereço </h4>
            </span>
            @if ($congregacao->latitude && $congregacao->longitude)
                <span
                    class="span-abrir-modal-local"
                    id="span-abrir-modal-local"
                    style="font-weight: bold; padding: 10px; background-color: navajowhite; border-radius: 10px; display: flex"
                >
                    Alterar Endereço
                </span>
            @endif
        </div>

        @if ($congregacao->latitude && $congregacao->longitude)
            <ul id="result_dados"></ul>
            <div class="map" style="text-align: center">
                <div id="map"></div>
            </div>
        @else
            <p style="color: red">A congregação ainda não possui endereço cadastrado. <span class="span-abrir-modal-local" id="span-abrir-modal-local" style="font-weight: bold"> Aperte aqui para cadastrar </span> </p>
        @endif

    </div>


</div>
    <script src="/js/generateMap.js"></script>
    <script src="/js/configuracaoCongregacao.js"></script>
    <script src="/js/obterLocalizacao.js"></script>

@endsection
