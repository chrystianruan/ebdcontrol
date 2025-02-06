@extends('layouts.mainSuperMaster')

@section('title', 'Início')

@section('content')

    <link rel="stylesheet" href="/css/filtrosPessoa.css">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div style="margin: 15px">

        <form action="/super-master/filters/users" method="POST">
            @csrf
            <div class="fields">

                <div class="itens">
                    <legend class="title">Filtrar por: </legend>
                </div>

                <div class="itens">
                    <input type="text" name="nome" placeholder="Nome do user">

                    <select name="status">
                        <option selected disabled value="">Status</option>
                        <option value="0">Ativo</option>
                        <option value=1>Inativo</option>

                    </select>
                    <fieldset style="border-radius: 10px">
                        <select name="setor" id="setor">
                            <option selected disabled value="">Setor</option>
                            @foreach($setores as $setor)
                            <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
                            @endforeach
                        </select>
                        <select name="congregacao" id="congregacao">
                            <option selected disabled value="">Congregação</option>

                        </select>
                    </fieldset>
                    <select name="supermaster">
                        <option selected disabled value="">Permissão</option>
                        @foreach($permissoes as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
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

        @if($users -> count() > 1)
            <caption class="cont"><h4>Usuários: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$users -> count()}}</font></h4></caption>
        @endif

        <thead>
        <tr>
            <th>Nome
            <th>Matrícula
            <th>Senha Temporária </th>
            <th>Reset</th>
            <th>Permissão</th>
            <th>Congregação/Setor
            <th>Status
            <th style="text-align: center">Ações
        </thead>
        @foreach($users as $u)

            <tbody>
            <tr> <!-- <tr class="disabled">  -->

                <td>@if($u->pessoa_id) @if ($u->pessoa) {{ $u->pessoa->nome }} @else Pessoa apagada @endif @else Sem dados @endif
                <td>{{ $u->matricula }}
                <td>{{ $u->password_temp }}</td>
                <td>
                    @if($u->reset_password == false)
                        <i style="padding: 2px; border-radius: 3px; background-color: green" class="bx bx-user-check icon"></i>
                    @else
                        <i class="bx bx-user-x icon" style="padding: 2px; border-radius: 3px; background-color: green" class="bx bx-user-x icon"> </i>
                    @endif
                </td>
                <td>
                    <span style="padding: 2px; border-radius: 3px; background-color: #3498db">{{ $u->permissao->name }}</span>
                </td>
                <td>{{ $u->nome_congregacao }}/{{ $u->nome_setor }}
                <td>@if($u->status == false) <font style="padding: 2px; border-radius: 3px; background-color: green">Ativo</font> @else <font style="padding: 2px; border-radius: 3px;background-color: red">Inativo</font>@endif
                <td>
                    <a href="/super-master/edit/user/{{$u->id}}" style="text-decoration: none; color:#7B4EA5; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-edit icon'></i> </a>
                    <a style="text-decoration: none; color:#7B4EA5; margin: 5px;float: left; cursor:pointer;" id="btn-reset-password-{{ $u->id  }}" class="btn-reset-password"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-reset icon'></i> </a>
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

        $('.btn-reset-password').click(function () {
            var response = confirm('Deseja realmente resetar a senha do usuário?');
            if (response) {
                let userId = this.id.replace("btn-reset-password-", "");
                $.ajax({
                    type: 'PUT',
                    url: '{{ url('/super-master/reset-password/user') }}/'+userId,
                    dataType: 'json',
                    success: function (data) {
                        alert(data.response);
                        window.location.reload();
                    }
                })
            }
        });

    </script>
@endsection
