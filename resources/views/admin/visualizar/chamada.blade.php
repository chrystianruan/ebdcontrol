@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/chamada.css">


<div class="tables">

    <table style="margin: 3% 0 0 3%;">
        <caption class="cont"><h4> @foreach($salas as $s)  @if($chamada -> id_sala == $s -> id) {{ $s -> nome }}  @endif @endforeach</h4></caption>

        <thead>
            <tr>
            <th>Nome</th>
            <th>Anivers.</th>
            <th>Função</th>
            <th>Presença</th>

            </tr>
        </thead>

        <tbody>

            @foreach($presencas as $p)
                <tr>
                    <td>{{ explode(' ', $p->pessoa->nome)[0] }} {{explode(' ',$p->pessoa->nome)[ count(explode(' ', $p->pessoa->nome)) - 1] }}</td>
                    <td>{{ date('d/m', strtotime($p->pessoa->data_nasc)) }}</td>
                    <td>{{ $p->funcao->nome }}</td>
                    <td> @if($p->presente == 1) <i style="color: rgb(12, 223, 12)" class="bx bx-check"></i> @else <i style="color: red" class="bx bx-x"></i> @endif</td>
                </tr>
            @endforeach



        </tbody>
      </table>
    </div>

    <div class="tudo">
    <div class="extras">


        <div class="inputs-extras">
            <label>Matriculados</label>
            <input name="matriculados"  type="number" value="{{$chamada -> matriculados}}" disabled>
        </div>

        <div class="inputs-extras">
            <label>Presentes</label>
            <input name="presentes" type="number" id="presentes" min="0"  value="{{$chamada -> presentes}}" disabled>
        </div>

        <div class="inputs-extras">
            <label>Visitantes</label>
            <input name="visitantes" type="number" id="visitantes" min="0" value="{{$chamada -> visitantes}}" disabled>
        </div>

        <div class="inputs-extras">
            <label>Assist. Total</label>
            <input name="assist_total" type="number" min="0" id="assist_total" value="{{ $chamada->presentes + $chamada->visitantes }}" disabled>
        </div>

        <div class="inputs-extras">
            <label>Bíblias</label>
            <input name="biblias" number" min="0" disabled value="{{$chamada -> biblias}}">
        </div>

        <div class="inputs-extras">
            <label>Revistas</label>
            <input name="revistas" number" min="0" disabled value="{{$chamada -> revistas}}">
        </div>
        <div class="text" style="margin: 1%">
            <label>Observações</label>
            <textarea rows="3" cols="40" name="observacoes" maxlength="500" disabled>{{$chamada -> observacoes}}</textarea>
        </div>
      </div>

    </div>





@endsection
