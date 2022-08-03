@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/cadastroAviso.css">
         
<div class="container">
        <header>Cadastro de aviso - {{$dataAtual}}</header>
        <form action="/admin/cadastro/aviso" method="POST" style="min-height: 500px">
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
                            <label>Título<font style="color:red;font-weight: bold">*</font></label>
                            <input type="text" name="titulo" placeholder="Digite o título do aviso" required>
                        </div>
                                    
                        <div class="input-field">
                        
                            <label>Descrição <font style="color:red;font-weight: bold">*</font></label>
                            <textarea  name="descricao"  style="resize: none;height: auto!important" rows=7 required> </textarea>
                          
                            </div>


                           <div> 
                            <div class="input-field" >
                                <label>Data de Post<font style="color:red;font-weight: bold">*</font></label>
                                <input type="date" name="data_post"  required>
                            </div>

                        
                        <div class="input-field" >
                            <label>Destinatário <font style="color:red;font-weight: bold">*</font> </label>
                            <select name="destinatario" required>
                                <option selected disabled value="">Selecionar</option>
                                <option value="4915">Todos</option>
                                @foreach($destinatarios as $d)
                                    @if($d->id <= 2)
                                    <option style="color: @if($d->id == 1) blue; @else red; @endif" value="{{$d -> id}}">{{$d -> nome}}</option>
                                    @endif
                                @endforeach
                                @foreach($destinatarios as $d)
                                    @if($d->id > 2)
                                    <option value="{{$d -> id}}">{{$d -> nome}}</option>
                                    @endif
                                @endforeach
                                
                            </select>
                        </div>

                        <div class="input-field">
                            <label>Importância<font style="color:red;font-weight: bold">*</font> </label>
                            <select name="importancia" required>
                                <option selected disabled value="">Selecionar</option>
                                @foreach($importancias as $val => $nome)
                                <option value="{{$val}}">{{$nome}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                        <div style="margin: 2% auto; position: relative; text-align: center">
                        <button type="submit" class="sumbit">
                            <span class="btnText">Enviar</span>
                            <i class="uil uil-navigator"></i>
                        </button>
                    </div>

            
                </div> 
            


        </form>
    </div>

    @endsection