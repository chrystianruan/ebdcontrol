@extends('layouts.mainMaster')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/cadastroAviso.css">
<div class="container" >
        <header>Edição de Usuário - {{date('d/m/Y')}}</header>
        <form action="/master/update/usuario/{{$user -> id}}" method="POST" style=" min-height: 240px">
            <input type="hidden" value="{{ url('/') }}" id="url">
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
                    </div>
                    <div class="input-field" style="margin: 5px">
                            <label style="text-align:left">Nível de acesso <font style="color:red;font-weight: bold;">*</font></label>
                            <select class="inputprof" id="nivel" required name="id_nivel">
                            <option disabled value="">Selecionar</option>
                                @foreach($niveis as $n)
                                    <option @if($user->permissao_id == $n -> id) selected @endif value="{{$n -> id}}"> {{$n -> name}}</option>
                                @endforeach
                            </select>
                    </div>

                    <div class="input-field div-select-salas" style="margin: 5px; display: none">
                        <label style="text-align:left">Nível de acesso <font style="color:red;font-weight: bold;">*</font></label>
                        <select id="select-salas" name="sala">

                        </select>
                    </div>

                        <div class="input-field" style="margin: 5px">
                            <label style="text-align:left">Status <font style="color:red;font-weight: bold;">*</font></label>
                            <select class="inputprof" required name="status">
                            <option disabled value="">Selecionar</option>
                                @if($user->status == 0)
                                <option selected value="0">Ativo</option>
                                <option value="1">Inativo</option>
                                @else
                                <option value="0">Ativo</option>
                                <option selected value="1">Inativo</option>
                                @endif
                                </select>

                        </div>




                        <button type="submit" class="sumbit">
                            <span class="btnText">Enviar</span>
                            <i class="uil uil-navigator"></i>
                        </button>


                </div>
            </div>


        </form>

</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script>
        $("#nivel").change(function() {
            if (this.value == 4) {
                $.ajax({
                    url: $('#url').val()+"/api/salas/congregacao/{{ base64_encode(auth()->user()->congregacao_id) }}",
                    type: 'GET',
                    dataType: 'json',
                    success: data => {
                        var option;
                        option += '<option selected value="" disabled>Selecionar</option>'
                        $.each(data, function(i, obj){
                            option += `<option value="${obj.id}">${obj.nome}</option>`;
                        })
                        $('#select-salas').append(option);
                    },
                    error: data => {
                        alert(data)
                    }
                });
                $('.div-select-salas').show();
                $('#select-salas').attr('required','required');
            } else {
                $('.div-select-salas').hide();
                $('#select-salas').removeAttr('required');
            }
        });
    </script>



    @endsection
