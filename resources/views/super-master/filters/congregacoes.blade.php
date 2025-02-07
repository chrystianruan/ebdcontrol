@extends('layouts.mainSuperMaster')

@section('title', 'Início')

@section('content')
    <link rel="stylesheet" href="/css/filtrosPessoa.css">

    <div style="margin: 15px">

        <form action="/super-master/filters/congregacoes" method="POST">
            @csrf
            <div class="fields">

                <div class="itens">
                    <legend class="title">Filtrar por: </legend>
                </div>

                <div class="itens">
                    <input type="text" name="nome" placeholder="Nome do congregação">


                        <select name="setor" id="setor">
                            <option selected disabled value="">Setor</option>
                            @foreach($setores as $setor)
                                <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
                            @endforeach
                        </select>




                    <div class="btnFilter">
                        <button type="submit" class="filter">Filtrar</button>
                    </div>

                    <div class="btnFilter">
                        <button type="reset" class="resett">Limpar tudo</button>
                    </div>

                </div>
            </div>
        </form>
    </div>



    @if(isset($nome) || isset($nivel) || isset($status))
        <div class="busca">
            <p class="tit">Buscando por:</p>

            @if(isset($nome) && empty($nivel) && empty($status))
                <li class="ponto">Nome:
                    <i class="result"> {{$nome}} </i>
                </li>
            @endif

            @if(isset($nivel) && empty($nome))
                <li class="ponto">Nível:
                    <i class="result">@foreach($niveis as $n) @if($n -> id == $nivel) {{$n -> nome}} @endif @endforeach</i>
                </li>
            @endif

            @if(isset($status) && empty($nome))
                <li class="ponto">Status:
                    <i class="result"> @if($status == 'on') Ativo @elseif($status == 1) Inativo @endif</i>
                </li>
            @endif


        </div>
    @else
        <div class="busca">
            <p class="tit">Buscando por:<i class="result">Tudo</i></p>
        </div>
    @endif



    <table style="margin:3%">

        @if($congregacoes -> count() > 1)
            <caption class="cont"><h4>Congregações: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$congregacoes -> count()}}</font></h4></caption>
        @endif

        <thead>
        <tr>
            <th>Nome
            <th>Setor
            <th>Link de cadastro</th>
            <th style="text-align: center">Ação
        </thead>
        @foreach($congregacoes as $c)

            <tbody>
            <tr> <!-- <tr class="disabled">  -->

                <td>{{$c -> nome}}
                <td>{{$c -> setor_nome}}
                <td>
                    <a href="{{ url('/cadastro')."/".base64_encode($c->id) }}" target="_blank" style="background-color: #0056b3; color: white; padding: 5px; border-radius: 5px">
                        {{ url('/cadastro')."/".base64_encode($c->id) }}
                    </a>
                    @if ($c->linkCadastroGeral)
                        <i class="bx bx-link icon" style="color: green; font-size: 1.7em; margin: 5px 10px;">
                    @else
                        <i class="bx bx-unlink icon" style="color: red; font-size: 1.7em; margin: 5px 10px; ">
                    @endif
                </td>
                <td>
                    <a href="/super-master/edit/congregacao/{{$c->id}}" style="text-decoration: none; color:#7B4EA5; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-edit icon'></i> </a>
                </td>
            </tr>

            </tbody>

        @endforeach
    </table>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script>
        $('#setor').change(function () {
            let setorId = $('#setor').val();
            $.ajax({
                type: 'GET',
                url: '{{ url('/api/congregacoes') }}/'+setorId,
                dataType: 'json',
                success: dados => {
                    var option;
                    option += `<option selected disabled value="">Selecionar</option>`;
                    if (dados.length > 0){
                        $.each(dados, function(i, obj){
                            option += `<option value="${obj.id}">${obj.nome}</option>`;
                        })
                    }
                    $('#congregacao').html(option).show();
                }
            })
        });

    </script>

@endsection
