@extends('layouts.mainMaster')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/cadastroAviso.css">
<div class="container" >
        <header>Alteração de Senha de {{$user -> username}}- {{date('d/m/Y')}}</header>
        <form action="/master/update/usuario-senha/{{$user -> id}}" method="POST" style=" min-height: 240px">
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
                    
                    <div class="fields">
                        
                    <div class="input-field" style="margin: 5px">
                            <label style="text-align:left">Nova senha <font style="color:red;font-weight: bold;">*</font></label>
                           <input name="password" type="text">
                       
                        </div>

                       



 
                        <button type="submit" class="sumbit">
                            <span class="btnText">Enviar</span>
                            <i class="uil uil-navigator"></i>
                        </button>


                </div> 
            


        </form>
    </div>   

 

    @endsection