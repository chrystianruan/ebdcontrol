@extends('layouts.mainSuperMaster')

@section('title', 'Início')

@section('content')

    <link rel="stylesheet" href="/css/cadastroAviso.css">
    <div class="container" >
        <header>Edição de Usuário - {{date('d/m/Y')}}</header>
        <form action="/super-master/update/user/{{ $user->id }}" method="POST" style=" min-height: 240px">
            @csrf
            @method('PUT')
            <div class="formFirst">
                @if ($errors->any())
                    <div class="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="details personal">
                    <span class="title">Informações</span>

                    <div class="fields">
                        <div class="input-field" style="margin: 5px">
                            <label style="text-align:left">Setor <span style="color:red;font-weight: bold;">*</span></label>
                            <select id="setor" class="inputprof" required name="id_nivel">
                                <option disabled value="">Selecionar</option>
                                @foreach($setores as $s)
                                    <option @if($user->setor_id == $s->id) selected @endif value="{{ $s->id }}"> {{ $s->nome }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="input-field" style="margin: 5px">
                            <label style="text-align:left">Congregação<span style="color:red;font-weight: bold;">*</span></label>
                            <select id="congregacao" class="inputprof" required name="congregacao">
                                <option disabled value="">Selecionar</option>
                                <option selected value="{{ $user->congregacao_id }}"> {{ $user->congregacao_nome }}</option>

                            </select>

                        </div>
                        <div class="input-field">
                            <label style="text-align:left">Permissão<span style="color:red;font-weight: bold;">*</span></label>
                            <select class="inputprof" required name="supermaster">
                               @foreach($permissoes as $p)
                                   <option @if($user->permissao_id == $p->id) selected @endif value="{{ $p->id }}">{{ $p->name }}</option>
                               @endforeach

                            </select>
                        </div>


                        <div class="input-field" style="margin: 5px">
                            <label style="text-align:left">Status <span style="color:red;font-weight: bold;">*</span></label>
                            <select class="inputprof" required name="status">
                                <option disabled value="">Selecionar</option>
                                @if($user->status == 0)
                                    <option selected value=0>Ativo</option>
                                    <option value=1>Inativo</option>
                                @else
                                    <option value=0>Ativo</option>
                                    <option selected value=1>Inativo</option>
                                @endif
                            </select>

                        </div>




                        <button type="submit" class="sumbit">
                            <span class="btnText">Enviar</span>
                            <i class="uil uil-navigator"></i>
                        </button>


                    </div>


                </div>
            </div>
        </form>
    </div>

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
