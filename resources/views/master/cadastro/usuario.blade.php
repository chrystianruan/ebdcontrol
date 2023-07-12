@extends('layouts.mainMaster')

@section('title', 'Início')

@section('content')
<link rel="stylesheet" href="/css/cadastroClasse.css">

<div class="row" style="margin: 2%">
  <div class="col-75">
    <div class="container">
      <form action="/master/cadastro/usuario" method="POST">
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


            <label for="nivel"><i class="fa fa-level-down"></i>Nível <font style="color:red;font-weight: bold">*</font></label>

            <select name="id_nivel" required>
                <option selected disabled value="">Selecionar</option>
            @foreach ($niveis as $n)
                <option @if(old('id_nivel') == $n ->id) selected @endif @if($n ->id == 1) style="color:blue" @elseif($n ->id == 2) style="color:red" @endif value="{{$n -> id}}"> {{$n -> nome}}</option>
            @endforeach
          </select>
          <fieldset>
            <legend style="font-weight: bold">Login</legend>
          <label for="username"><i class="fa fa-user"></i>Nome de usuário <font style="color:red;font-weight: bold">*</font></label>
          <input type="text" id="username" required name="username" placeholder="Digite o username do usuário" value="{{old('username')}}">

          <label for="senha"><i class="fa fa-lock"></i>Senha <font style="color:red;font-weight: bold">*</font></label>
          <input type="password" id="senha" name="password"  placeholder="Padrão: ebd@CPF">
        </fieldset>


        <input type="submit" value="Cadastrar" class="btn">
          </div>
      </form>
    </div>
  </div>

</div>
<script
src="https://code.jquery.com/jquery-3.6.0.js"
integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
crossorigin="anonymous"></script>

<script>
document.getElementById("username").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM_.".indexOf(chr) < 0)
           return false;
       };
</script>


@endsection
