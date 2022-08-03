@extends('layouts.mainClasse')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/chamada.css">

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
<div style=" overflow-x: auto">
<table style="margin: 3% 3% 0 3%;">
    <caption class="cont"><span style="font-weight: bold; color:black"> @foreach($salas as $sala) @if($sala -> id == auth()->user()->id_nivel) {{ $sala -> nome }} @endif @endforeach - {{date('d/m/Y')}}</span></caption>
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
        <tr>
        <td>{{ $p -> nome}}</td>
        <td>{{ date('d/m', strtotime($p -> data_nasc)) }}</td>
        <td>@if($p -> id_funcao == 1) Aluno @elseif($p -> id_funcao == 2) Prof. @elseif($p -> id_funcao == 3) Sec. @elseif($p -> id_funcao == 4) Sec. @elseif($p -> id_funcao == 5) Superint. @else Erro @endif</td>
        <td>
            <select name="presencas[]" id="presencas">
                <option selected value = "1" style="background-color: green">Sim</option>
                <option value = "2" style="background-color: red">Não</option>
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
        <input name="presentes" type="number" id="presentes" min="0" required value="{{old('presentes')}}">
    </div>

    <div class="inputs-extras">
        <label>Visitantes</label>
        <input name="visitantes" type="number" id="visitantes" min="0" required value="{{old('presentes')}}">
    </div>

    <div class="inputs-extras">
        <label>Assist. Total</label>
        <input name="assist_total" type="number" min="0" id="assist_total" readonly required value="{{old('assist_total')}}">
    </div>

    <div class="inputs-extras">
        <label>Bíblias</label>
        <input name="biblias" number" min="0" required value="{{old('biblias')}}">
    </div>

    <div class="inputs-extras">
        <label>Revistas</label>
        <input name="revistas" number" min="0" required value="{{old('revistas')}}">
    </div>
    <div class="text" style="margin: 1%">
        <label>Observações</label>
        <textarea rows="3" cols="40" name="observacoes" maxlength="500">{{old('observacoes')}}</textarea>
    </div>
    <button type="submit" class="sumbit">
        <span class="btnText">Enviar</span>
        <i class="uil uil-navigator"></i>
    </button>
  </div>
 
 
</div>

</form>
@else
    <div class="notRegister"> <p> <i style="color: red"class='bx bx-error'></i></i>A chamada da classe @foreach($salas as $s) @foreach($chamadas as $c) @if($c -> id_sala == $s -> id) {{ $s -> nome }} @endif @endforeach @endforeach já foi cadastrada </p></div>
@endif
  
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script>

    $("#presencas").change(function() {
        if (this.value == 1) {
            $('#presencas').css('background-color', 'green');
            $('#presencas').css('color', 'white');
        } else if(this.value == 2) {
            $('#presencas').css('background-color', 'red');
            $('#presencas').css('color', 'white');
        } else {
        }
    });


    $("#presentes, #visitantes").keyup(function() {
        var presentes = parseInt($('#presentes').val());
        var visitantes = parseInt($('#visitantes').val());
        var soma = presentes + visitantes;

        $('#assist_total').val(soma);
    });

  </script>
@endsection