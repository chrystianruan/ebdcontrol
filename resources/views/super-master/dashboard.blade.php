@extends('layouts.mainSuperMaster')

@section('title', 'Início')

@section('content')
<link rel="stylesheet" href="/css/supermaster.css">

<div class="div-btn-modal" id="div-btn-modal">
<button class="btn-modal" id="btn-modal-user"> Cadastrar usuário </button>
<button class="btn-modal" id="btn-modal-congregacao"> Cadastrar congregação </button>
</div>

<div class="dialog" id="modal-user">
    <div class="dialog-overlay" tabindex="-1"></div>
    <div class="dialog-content" role="dialog">
        <div role="document">
            <button class="dialog-close" id="dialog-close-user">&times;</button>
            <h1 id="dialogTitle">Cadastro de usuário Master</h1>
            <hr>
            <div class="row" style="margin: 2%">
                <div class="col-75">
                    <div class="container">
                        <form action="/super-master/cadastro/usuario" method="POST">
                            @csrf
                            <div class="col-50">
                                <h3>Informações Pessoais</h3>
                                @if ($errors->any())
                                    <div class="alert">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <label for="nome"><i class="fa fa-address-book"></i>Nome <font style="color:red;font-weight: bold">*</font></label>
                                <input type="text" id="nome" required name="name" placeholder="Digite o nome do usuário" value="{{old('name')}}">


                                <label for="nivel"><i class="fa fa-level-down"></i>Setor <font style="color:red;font-weight: bold">*</font></label>
                                <select name="setor" id="setor" required>
                                    <option selected disabled value="">Selecionar</option>
                                    @foreach ($setores as $s)
                                        <option value="{{ $s->id }}">{{ $s->nome }}</option>
                                    @endforeach
                                </select>

                                <label for="nivel"><i class="fa fa-level-down"></i>Congregacão <font style="color:red;font-weight: bold">*</font></label>
                                <select name="congregacao" id="congregacao" required> </select>


                                <label for="nivel"><i class="fa fa-admin"></i>SuperMaster <font style="color:red;font-weight: bold">*</font><input type="checkbox" value="1" name="super_master"></label>


                                <fieldset>
                                    <legend style="font-weight: bold">Login</legend>
                                    <label for="username"><i class="fa fa-user"></i>Nome de usuário <font style="color:red;font-weight: bold">*</font></label>
                                    <input type="text" id="username" style="width: 95%" required name="username" placeholder="Digite o username do usuário" value="{{old('username')}}">

                                    <label for="senha"><i class="fa fa-lock"></i>Senha <font style="color:red;font-weight: bold">*</font></label>
                                    <input type="password" id="senha" style="width: 95%" name="password"  placeholder="Padrão: ebd@CPF">
                                </fieldset>


                                <input type="submit" value="Cadastrar" class="btn">
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="dialog" id="modal-congregacao">
    <div class="dialog-overlay" tabindex="-1"></div>
    <div class="dialog-content" role="dialog">
        <div role="document">
            <button class="dialog-close" id="dialog-close-congregacao">&times;</button>
            <h1>Cadastro de congregação</h1>
            <hr>
            <div class="row" style="margin: 2%">
                <div class="col-75">
                    <div class="container">
                        <form action="/super-master/new/congregacao" method="POST">
                            @csrf
                            <div class="col-50">
                                <h3>Informações</h3>
                                @if ($errors->any())
                                    <div class="alert">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif



                                <label for="nivel"><i class="fa fa-level-down"></i>Setor <font style="color:red;font-weight: bold">*</font></label>
                                <select name="setor" id="setor" required>
                                    <option selected disabled value="">Selecionar</option>
                                    @foreach ($setores as $s)
                                        <option value="{{ $s->id }}">{{ $s->nome }}</option>
                                    @endforeach
                                </select>

                                <label for="nivel"><i class="fa fa-level-down"></i>Congregacão <font style="color:red;font-weight: bold">*</font></label>
                                <input name="congregacao" id="congregacao" type="text" required>

                                <input type="submit" value="Cadastrar" class="btn">
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="/js/super-master.js"></script>
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
