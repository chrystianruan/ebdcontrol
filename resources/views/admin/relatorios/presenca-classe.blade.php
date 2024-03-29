@extends('layouts.main')

@section('title', 'Início')

@section('content')
<link rel="stylesheet" href="/css/filtros.css">
<link rel="stylesheet" href="/css/relatorio-presenca.css">
<input type="hidden" id="url-get-chamadas" value="{{ route('relatorio.per.date') }}">
<input type="hidden" id="url-get-format-data" value="{{ route('format.data.relatorio') }}">
<div style="margin: 15px">
<div class="fields">
    <div class="itens">
        <h4 class="title">Relatório de presenças</h4>
    </div>

    <div class="itens">

        <select id="classe">
            <option selected disabled value="">Classe</option>
          @foreach($classes as $c)
              <option value="{{ base64_encode($c->id) }}"> {{ $c->nome }}</option>
          @endforeach

        </select>
        <input type="text" placeholder="Data início" id="initial_date"
               onfocus="(this.type='date')">
        <input type="text" placeholder="Data fim" id="final_date"
               onfocus="(this.type='date')">
{{--        <select name="sexo">--}}
{{--            <option selected disabled value="">Ordenação</option>--}}
{{--            <option value="1">A-Z (alfabética)</option>--}}
{{--            <option value="2">Mais presentes</option>--}}
{{--            <option value="3">Menos presentes</option>--}}

{{--        </select>--}}


        <div class="btnFilter">
            <button type="button" class="filter" id="gerar-relatorio">Gerar</button>
        </div>
        <div class="btnFilter">
            <button type="button" class="baixar" id="baixar-relatorio">Baixar <i style="color: black" class='bx bxs-file-pdf'></i></button>
        </div>

    </div>
</div>
</div>
<div style="display: none">
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

<div style="overflow-x:scroll" class="container-table" id="container-table">
    <table id="table-render">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Função</th>
            <th>Data de nascimento</th>
            <th>Presenças</th>
        </tr>
        </thead>
        <tbody id="tbody-data">

        </tbody>
    </table>
</div>
<div id="loader" class="loader" style="margin: 15% 50%"></div>
@endsection
@push('scripts-relatorio-presenca ')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js" integrity="sha512-2/YdOMV+YNpanLCF5MdQwaoFRVbTmrJ4u4EpqS/USXAQNUDgI5uwYi6J98WVtJKcfe1AbgerygzDFToxAlOGEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    const { jsPDF } = window.jspdf;
</script>
<script src="/js/relatorio-presenca.js"></script>
@endpush
