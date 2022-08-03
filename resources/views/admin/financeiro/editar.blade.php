@extends('layouts.main')

@section('title', 'Início')

@section('content')


<link rel="stylesheet" href="/css/cadastro.css">
         
<div class="container" style="margin-left:15%;width: 70% ">
        <header style="background-color: @if($financeiro->id_financeiro == 1) green; @else red; @endif padding: 5px; border-radius: 5px 5px 0 0; box-shadow: 0 0 0.2em black;" @if($financeiro->id_financeiro == 1) green; @else red; @endif> EDITAR @if($financeiro->id_financeiro == 1)<font style="color: white"> Entrada</font> @else <font style="color: white">Saída</font> @endif (Só edite essa entrada/saída se for estritamente necessário) </header>
        <form action="/admin/financeiro/update/{{$financeiro -> id}}" method="POST" style="min-height: 300px !important; ">
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
                        <span style="font-size: 15px; color:red">Tenha em mente que só poderá editar essa entrada/saída <mark>APENAS ESSA VEZ</mark>, portanto, analise com <mark>cuidado</mark> se todas as informações que serão atualizadas estarão corretas.</span>
                        <div style="display:flex">

                        <div class="input-field" style="width: 50px;">
                            <label>Valor <font style="color:red;font-weight: bold">*</font></label>
                            <input type="text" style="border-radius: 10px 0 0 10px; background-color: #ccc" disabled value="R$">
                        </div>

                        <div class="input-field" style="width: 130px;">
                            <label>⠀</label>
                            <input type="number" style="border-radius: 0 10px 10px 0" name = "valor" step="0.01"  min="0" required placeholder="Valor recebido" value="{{$financeiro->valor}}">
                        </div>

                        <div class="input-field" style="width: 200px;margin-left: 3% ">
                            <label>Tipo da @if($financeiro->id_financeiro == 1) Entrada @else Saída @endif <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" required name="id_tipo">
                                <option disabled value="">Selecionar</option>
                                @foreach($tipos as $t)
                                    <option @if($t -> id == $financeiro -> id_tipo) selected @endif value="{{$t -> id}}">{{$t -> nome}}</option>
                                @endforeach
                                </select>
                        
                        </div>
                        <div class="input-field" style="width: 200px;margin-left: 3%">
                            <label>Categoria da @if($financeiro->id_financeiro == 1) Entrada @else Saída @endif <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" required name="id_cat">   
                            <option disabled value="">Selecionar</option>
                                @foreach($cats as $c)
                                    <option @if($c -> id == $financeiro -> id_cat) selected @endif value="{{$c -> id}}">{{$c -> nome}}</option>
                                @endforeach
                                </select>
                        
                        </div>
                        <div class="input-field"  style="width: 200px; margin-left: 3%">
                            <label>Data da @if($financeiro->id_financeiro == 1) Entrada @else Saída @endif <font style="color:red;font-weight: bold">*</font> </label>
                            <input type="date" name="data_cad" placeholder="Digite a data de nascimento" max="{{date('Y-m-d')}}" required value="{{date('Y-m-d', strtotime($financeiro -> data_cad))}}">
                        </div>

                        </div>
                        <div style="display:flex">
                        

                        <div class="input-field" style="width:600px;">
                        
                        <label>Descrição da @if($financeiro->id_financeiro == 1) Entrada @else Saída @endif <font style="color:red;font-weight: bold">*</font></label>
                        <textarea type="text" required style="resize: vertical;height: auto!important" rows=4 name = "descricao">{{$financeiro->descricao}}</textarea>
                      
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