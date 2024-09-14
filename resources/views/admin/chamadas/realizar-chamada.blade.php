@extends('layouts.main')

@section('title', 'Início')

@section('content')

    <link rel="stylesheet" href="/css/chamada.css">
    <link rel="stylesheet" href="/css/filtros.css">
    @if(date('w') == 0 || date('Y-m-d') == $dateChamadaDia)
        <input type="hidden" id="buscar-pessoas" value="{{ url('/api/pessoas_sala') }}">
        <div style="margin: 20px">
            <div class="fields">

                <div class="itens">
                    <legend class="title">Selecione a Classe Faltante: </legend>
                </div>

                <div class="itens">

                    <select id="select-sala">
                        <option selected disabled value="">Classe</option>
                        @for($i = 0; $i < count($classesFaltantes); $i++)
                            <option value="{{$classesFaltantes[$i]['id']}}">{{$classesFaltantes[$i]['nome']}}</option>
                        @endfor

                    </select>

                </div>
            </div>
        </div>
        @if(count($classesFaltantes) > 0)
            @if ($errors->any())
                <div class="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="/realizar-chamada" method="POST">
                @csrf
                <input type="hidden" id="pessoas" name="pessoas_presencas" value="">
                <input type="hidden" id="sala" name="sala" value="">
                <input type="hidden" name="route" value="{{ url('/admin/realizar-chamadas') }}">
                <div style="overflow-x: auto; display: none" id="div-table-pessoas">
                    <table style="margin: 3% 3% 0 3%;">
                        <caption class="cont"><span style="font-weight: bold"> <span id="span-nome-classe"> </span> - {{date('d/m/Y')}}</span></caption>
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th style="max-width: 50px">Função</th>
                            <th>Presente</th>
                        </tr>
                        </thead>

                        <tbody id="tbody-table-pessoas">

                        </tbody>
                    </table>

                </div>
                <div class="tudo" id="div-dados-inteiros" style="display: none">
                    <div class="extras">

                        <div class="inputs-extras">
                            <label>Matriculados</label>
                            <input name="matriculados"  type="number" required value="" readonly>
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
                            <input type="number" min="0" id="assist_total" readonly required value="0">
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
            <div class="notRegister"> <p> <i style="color: red"class='bx bx-error'></i></i>A chamada da classe já foi cadastrada </p></div>
        @endif
    @else
        <div class="notRegister"> Hoje não é dia de chamada! </div>
    @endif
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="/js/chamada.js"></script>
    <script src="/js/chamadaAdmin.js"></script>
@endsection
