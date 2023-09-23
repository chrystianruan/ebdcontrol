@extends('layouts.mainClasse')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/chamada.css">
@if(date('w') == 0)
@if($chamadas -> count() == 0)
    @if ($errors->any())
    <div class="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
<form action="/classe/chamada-dia" method="POST">
    @csrf
    <input type="hidden" id="pessoas" name="pessoas_presencas" value="{{ $pessoas }}">
<div style=" overflow-x: auto">
<table style="margin: 3% 3% 0 3%;">
    <caption class="cont"><span style="font-weight: bold"> @foreach($salas as $sala) @if($sala -> id == auth()->user()->id_nivel) {{ $sala -> nome }} @endif @endforeach - {{date('d/m/Y')}}</span></caption>
    <thead>
        <tr>
        <th>Nome</th>
        <th>Anivers.</th>
        <th style="max-width: 50px">Função</th>
        <th>Presente</th>
        </tr>
    </thead>

    <tbody>
        @foreach($pessoas as $p)
        <tr @if($p->id_funcao == 2) style="background-color: rgba(59,52,52,0.73)" @endif>
        <td>{{ $p -> nome}}</td>
        <td>{{ date('d/m', strtotime($p -> data_nasc)) }}</td>
        <td>{{ $p->nome_funcao }}</td>
        <td>
            <select name="presencas[]" id="presenca-{{ $p->id }}" class="presencas">
                <option selected value="0" style="background-color: red">Não</option>
                <option value="1" style="background-color: green">Sim</option>
            </select>
        </tr>
        @endforeach
    </tbody>
  </table>

</div>
<div class="tudo">
<div class="extras">

    <div class="inputs-extras">
        <label>Matriculados</label>
        <input name="matriculados"  type="number" required value="{{ $pessoas -> count() }}" readonly>
    </div>

    <div class="inputs-extras">
        <label>Presentes</label>
        <input name="presentes" type="number" id="presentes" min="0" required readonly value="0">
    </div>

    <div class="inputs-extras">
        <label>Visitantes</label>
        <input name="visitantes" type="number" id="visitantes" min="0" required value="">
    </div>

    <div class="inputs-extras">
        <label>Assist. Total</label>
        <input name="assist_total" type="number" min="0" id="assist_total" readonly required value="0">
    </div>

    <div class="inputs-extras">
        <label>Bíblias</label>
        <input name="biblias" type="number" min="0" required value="">
    </div>

    <div class="inputs-extras">
        <label>Revistas</label>
        <input name="revistas" type="number" min="0" required value="">
    </div>
    <div class="text" style="margin: 1%">
        <label>Observações</label>
        <textarea name="observacoes" maxlength="500">{{old('observacoes')}}</textarea>
    </div>
    <button type="submit" class="sumbit">
        <span class="btnText">Enviar</span>
        <i class="uil uil-navigator"></i>
    </button>
  </div>


</div>

</form>
@else
    <div class="notRegister"> <p> <i style="color: red"class='bx bx-error'></i></i>A chamada da classe @foreach($salas as $s) @foreach($chamadas as $c) @if($c -> id_sala == $s -> id) {{ $s -> nome }} @endif @endforeach @endforeach já foi cadastrada ou hoje não é domingo. </p></div>

@endif
@else
    <div class="notRegister"> Hoje não é domingo </div>
@endif
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
 <script src="/js/chamada.js"></script>
<script>



  </script>
@endsection
