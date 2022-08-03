@extends('layouts.mainMaster')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/cadastroAviso.css">
         
<div class="container" style="width:60%;">
        <header>Cadastro de sala - {{$dataAtual}}</header>
        <form action="/master/cadastro/classe" method="POST" style="overflow-y: hidden; min-height: 300px">
            @csrf
            <div class="form first">
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
                            <label>Nome<font style="color:red;font-weight: bold">*</font></label>
                            <input type="text" name="nome" placeholder="Digite o nome da sala" required>
                        </div>

                        <div class="input-field">
                        
                        <label>Tipo <font style="color:red;font-weight: bold">*</font></label>
                        <input type="text" name="tipo"  placeholder="Digite o tipo de sala" required>
                      
                        </div>


                        <button type="submit" class="sumbit">
                            <span class="btnText">Enviar</span>
                            <i class="uil uil-navigator"></i>
                        </button>
                </div> 
            


        </form>
    </div>

    <script>
setTimeout(function() {
    $('#successMessage').fadeOut('fast');
}, 3000); 

    </script>

    @endsection