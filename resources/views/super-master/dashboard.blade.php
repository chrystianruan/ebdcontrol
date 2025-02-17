@extends('layouts.mainSuperMaster')

@section('title', 'Início')

@section('content')
<link rel="stylesheet" href="/css/supermaster.css">

<input type="hidden" value="{{ url('/api/congregacoes') }}" id="route-congregacoes-api">
<div class="div-btn-modal" id="div-btn-modal">
<button class="btn-modal" id="btn-modal-congregacao"> Cadastrar congregação </button>
<button class="btn-modal" id="btn-modal-cadastro-classe"> Cadastrar classe em congregação </button>
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
<div class="dialog" id="modal-cadastro-classe">
    <div class="dialog-overlay" tabindex="-1"></div>
    <div class="dialog-content" role="dialog">
        <div role="document">
            <button class="dialog-close" id="dialog-close-cadastro-classe">&times;</button>
            <h1>Cadastro de congregação</h1>
            <hr>
            <div class="row" style="margin: 2%">
                <div class="col-75">
                    <div class="container">
                        <form action="/super-master/new/sala" method="POST">
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
                                <select name="select-setor" id="select-setor" required>
                                    <option selected disabled value="">Selecionar</option>
                                    @foreach ($setores as $s)
                                        <option value="{{ $s->id }}">{{ $s->nome }}</option>
                                    @endforeach
                                </select>

                                <select name="select_congregacao" id="select-congregacao" required>
                                </select>

                                <hr>
                                <label>Classe</label>
                                <input type="text" name="input_nome_sala">
                                <label>Tipo</label>
                                <input type="text" name="input_tipo_sala">

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
@endsection
