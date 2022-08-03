@extends('layouts.main')

@section('title', 'Início')

@section('content')


<link rel="stylesheet" href="/css/cadastro.css">
         
<div class="container" style="margin-left:15%;width: 70% ">
        <header style="background-color: green; padding: 5px; border-radius: 5px 5px 0 0; box-shadow: 0 0 0.2em black ">Entrada (+) - {{$dataAtual}}<font style="float:right;">$</font></header>
        <form action="/admin/financeiro/entrada" method="POST" style="min-height: 300px !important; ">
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
                    <span class="title">Informações </span>

                    
                    <div class="fields">
                        <span style="font-size: 15px; color:red">As informações requisitadas só poderão ser editadas apenas <mark style="color: ">UMA VEZ</mark>, portanto, cadastre-as com o máximo possível de <mark>cuidado</mark>.</span>
                        <div style="display:flex">

                        <div class="input-field" style="width: 50px;">
                            <label>Valor<font style="color:red;font-weight: bold">*</font></label>
                            <input type="text" style="border-radius: 10px 0 0 10px; background-color: #ccc" disabled value="R$">
                        </div>

                        <div class="input-field" style="width: 130px;">
                            <label>⠀</label>
                            <input type="number" style="border-radius: 0 10px 10px 0" name = "valor" step="0.01"  min="0" required placeholder="Valor recebido">
                        </div>

                        <div class="input-field" style="width: 200px;margin-left: 3% ">
                            <label>Tipo da Entrada <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" required name="id_tipo">
                            <option selected disabled value="">Selecionar</option>
                            @foreach($tipos as $t)
                                <option value="{{$t->id}}">{{$t->nome}}</option>
                            @endforeach 
                                </select>
                        
                        </div>
                        <div class="input-field" style="width: 200px;margin-left: 3%">
                            <label>Categoria da Entrada <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" required name="id_cat">
                            <option selected disabled value="">Selecionar</option>
                            @foreach($cats as $c)
                                <option value="{{$c->id}}">{{$c->nome}}</option>
                            @endforeach 
                                </select>
                        
                        </div>
                        <div class="input-field"  style="width: 200px; margin-left: 3%">
                            <label>Data da Entrada <font style="color:red;font-weight: bold">*</font> </label>
                            <input type="date" name="data_cad" placeholder="Digite a data de nascimento" max="{{date('Y-m-d')}}" required>
                        </div>

                        </div>
                        <div style="display:flex">
                        

                        <div class="input-field" style="width:600px;">
                        
                        <label>Descrição<font style="color:red;font-weight: bold">*</font></label>
                        <textarea type="text" required style="resize: vertical;height: auto!important" rows=4 name = "descricao"></textarea>
                      
                        </div>
                      
                        
                       
                   
                        </div>
        

     
                        <button type="submit" class="sumbit" style="width: 150px; margin-right: 5%">
                            <span class="btnText">Enviar</span>
                            <i class="uil uil-navigator"></i>
                        </button>
                       
                        
                </div> 
            


        </form>
    </div>
    
@endsection