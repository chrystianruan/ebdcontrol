@extends('layouts.mainMaster')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/cadastroAviso.css">
<div class="container" >
        <header>Edição de Usuário - {{date('d/m/Y')}}</header>
        <form action="/master/update/usuario/{{$user -> id}}" method="POST" style=" min-height: 240px">
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
                        <div class="input-field">
                            <label style="text-align:left">Nome <font style="color:red;font-weight: bold;">*</font></label>
                            <input type="text" name="name" value="{{ $user->name }}">

                        </div>
                        <div class="input-field">
                            <label style="text-align:left">Nome de usuário <font style="color:red;font-weight: bold;">*</font></label>
                            <input type="text" name="username" value="{{ $user->username }}">

                        </div>
                    <div class="input-field" style="margin: 5px">
                            <label style="text-align:left">Nível de acesso <font style="color:red;font-weight: bold;">*</font></label>
                            <select class="inputprof" required name="id_nivel">
                            <option disabled value="">Selecionar</option>
                                @foreach($niveis as $n)
                                    <option @if($user -> id_nivel == $n -> id) selected @endif value="{{$n -> id}}"> {{$n -> nome}}</option>
                                @endforeach
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



        </form>
    </div>



    @endsection
