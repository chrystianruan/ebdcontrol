@extends('layouts.mainMaster')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/filtrosPessoa.css">
<meta name="csrf-token" content="{{ csrf_token() }}" />
 <div style="margin: 15px">

  <form action="/master/filtro/usuario" method="POST">
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

    <select name="permission" id="select-permission">
        <option selected disabled value="">Permissão</option>
        @foreach($permissoes as $p)
            <option value="{{ $p->id }}">{{ $p->name }}</option>
        @endforeach

    </select>

    <select name="sala" id="select-sala">
      <option selected disabled value="">Classe</option>
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



@if(isset($nome) || isset($status) || isset($permission) || isset($sala))
<div class="busca">
  <p class="tit">Buscando por:</p>

  @if(isset($nome))
  <li class="ponto">Nome:
      <i class="result"> {{$nome}} </i>
  </li>
  @endif

  @if(isset($permission))
  <li class="ponto">Permissão:
      <i class="result">{{$permission}}</i>
  </li>
  @endif
    @if(isset($sala))
        <li class="ponto">Classe:
            <i class="result">{{ $sala }}</i>
        </li>
    @endif
  @if(isset($status))
  <li class="ponto">Status:
      <i class="result">{{ $status }}</i>
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
      <th>Nível
      <th>Status
      <th style="text-align: center">Ações
  </thead>


  <tbody>
  @foreach($users as $u)
      @if ($u->permissao_id != 1)
        <tr>

          <td>@if($u->pessoa_id) @if ($u->pessoa) {{ $u->pessoa->nome }} @else Pessoa apagada @endif @else Sem dados @endif
          <td>{{$u->matricula}}
          <td>{{ $u->password_temp }}</td>
            <td>
                @if($u->reset_password == false)
                    <i style="padding: 2px; border-radius: 3px; font-size: 1.5em; background-color: green" class="bx bx-user-check icon"></i>
                @else
                    <i style="padding: 2px; border-radius: 3px; font-size: 1.5em; background-color: red" class="bx bx-user-x icon"> </i>
                @endif
            </td>
          <td>{{ $u->permissao->name }} @if ($u->sala_id) ({{ $u->sala->nome }}) @endif
          <td>@if($u->status == false) <font style="padding: 2px; border-radius: 3px; background-color: green">Ativo</font> @else <font style="padding: 2px; border-radius: 3px;background-color: red">Inativo</font>@endif
          <td>
            <a href="/master/edit/usuario/{{$u->id}}" style="text-decoration: none; color:#7B4EA5; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-edit icon'></i> </a>
            <a style="text-decoration: none; color:#7B4EA5; margin: 5px;float: left; cursor: pointer" id="btn-reset-password-{{ $u->id  }}" class="btn-reset-password"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-reset icon'></i> </a>
          </td>
        </tr>
      @endif
  @endforeach
  </tbody>


</table>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script>

    $('.btn-reset-password').click(function () {
        var response = confirm('Deseja realmente resetar a senha do usuário?');
        console.log(this);
        if (response) {
            let userId = this.id.replace("btn-reset-password-", "");
            $.ajax({
                type: 'PUT',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                url: '{{ url('/master/update/reset-password') }}/'+userId,
                dataType: 'json',
                success: function (data) {
                    alert(data.response);
                    window.location.reload();
                }
            })
        }
    });

    $('#select-permission').change(function () {
        if ($('#select-permission').val() == 4) {
            $.ajax({
                type: 'GET',
                url: '{{ url('/api/salas/congregacao') }}/'+"{{ base64_encode(auth()->user()->congregacao_id) }}",
                dataType: 'json',
                success: dados => {
                    var option;
                    option += `<option selected disabled value="">Classe</option>`;
                    if (dados.length > 0){
                        $.each(dados, function(i, obj){
                            option += `<option value="${obj.id}">${obj.nome}</option>`;
                        })
                    }
                    $('#select-sala').html(option).show();
                }
            })
        }
    });

</script>
@endsection
