@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/cadastroAviso.css">
         
<div class="container" >
        <header>Edição de aviso - {{date('d/m/Y')}}</header>
        <form action="/admin/update/aviso/{{$aviso -> id}}" method="POST" style="min-height: 500px">
            @csrf
            @method('PUT')
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

                        <div class="input-field" >
                            <label>Título<font style="color:red;font-weight: bold">*</font></label>
                            <input type="text" name="titulo" placeholder="Digite o título do aviso" required value="{{$aviso -> titulo}}">
                        </div>
                                    
                        <div class="input-field" >
                        
                            <label>Descrição <font style="color:red;font-weight: bold">*</font></label>
                            <textarea  name="descricao"  style="resize: none;height: auto!important" rows=7 required> {{$aviso -> descricao}}</textarea>
                          
                            </div>


                            <div style="display: flex; flex-wrap; wrap; justify-content: space-around "> 
                            <div class="input-field" style="width: 200px;margin: 5px">
                                <label>Data de Post<font style="color:red;font-weight: bold">*</font></label>
                                <input type="date" name="data_post"  required value="{{date('Y-m-d', strtotime($aviso -> data_post))}}">
                            </div>

                        
                        <div class="input-field" style="width: 150px; margin: 5px">
                            <label>Destinatário <font style="color:red;font-weight: bold">*</font> </label>
                            <select name="destinatario" required>
                                <option disabled value="">Selecionar</option>
                                @if($aviso -> destinatario == 4915)
                                <option selected value="{{$aviso->destinatario}}">Todos</option>
                                <option style="color: blue;" value="1">Master</option>
                                <option style="color: red;" value="2">Admin</option>
                                    @foreach($destinatarios as $d)
                                        @if($d->id > 2)
                                        <option value="{{$d -> id}}">{{$d -> nome}}</option>
                                        @endif
                                    @endforeach
                                @elseif($aviso -> destinatario == 1)
                                    <option value="4915">Todos</option>
                                    <option selected style="color: blue;"   value="{{$aviso->destinatario}}">Master</option>
                                    <option style="color: red;" value="2">Admin</option>
                                    @foreach($destinatarios as $d)
                                        @if($d->id > 2)
                                        <option value="{{$d -> id}}">{{$d -> nome}}</option>
                                        @endif
                                    @endforeach
                                @elseif($aviso -> destinatario == 2)
                                    <option value="4915">Todos</option>
                                    <option selected style="color: red;" value="{{$aviso->destinatario}}">Admin</option>
                                    <option style="color: blue;" value="1">Master</option>
                                    @foreach($destinatarios as $d)
                                        @if($d->id > 2)
                                        <option value="{{$d -> id}}">{{$d -> nome}}</option>
                                        @endif
                                    @endforeach
                                @else
                                <option  value="4915">Todos</option>
                                <option style="color: blue;" value="1">Master</option>
                                <option style="color: red;" value="2">Admin</option>
                                @foreach($destinatarios as $d)
                                    @if($d -> id == $aviso -> destinatario)
                                    <option selected value="{{$aviso->destinatario}}">{{$d-> nome}}</option>
                                    @endif
                                    @if($d -> id > 2)
                                        @if($d->id != $aviso -> destinatario)
                                        <option value="{{$d->id}}">{{$d-> nome}}</option>
                                        @endif
                                    @endif
                                @endforeach
                                
                                @endif

                                   
                            </select>
                        </div>

                        <div class="input-field" style="width: 150px;margin: 5px">
                            <label>Importância<font style="color:red;font-weight: bold">*</font> </label>
                            <select name="importancia" required>
                                <option disabled value="">Selecionar</option>
                                @foreach($importancias as $val => $nome)
                                    <option  @if($aviso -> importancia == $val)selected @endif value="{{$val}}">{{$nome}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                        
                        <button type="submit" class="sumbit">
                            <span class="btnText">Enviar</span>
                            <i class="uil uil-navigator"></i>
                        </button>


            
                </div> 
            


        </form>
    </div>

    @endsection