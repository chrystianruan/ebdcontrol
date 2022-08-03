@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/cadastro.css">
<div class="container" style="margin-left:5%;">
        <header>Edição de pessoa - {{$dataAtual}}</header>

        <form action="/admin/update/pessoa/{{$pessoa -> id}}" method="POST">
            @method('PUT')
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
                    <div style="float:right; margin-right: 2%">
                        <label>Menor de idade?</label>
                        <input type="checkbox"  id="scales"  @if($pessoa -> responsavel != null) checked @endif>
                        </div>
                    <span class="title">Informações pessoais</span>
                    
                    
                    <div class="fields">
                        <div class="input-field">
                            <label>Nome completo <font style="color:red;font-weight: bold">*</font></label>
                            <input type="text" name="nome" required placeholder="Digite o nome" value="{{$pessoa -> nome}}">
                        </div>

    

                        <div class="input-field" id="nomeResp" style="display:@if($pessoa -> responsavel == null)none @endif">
                        
                        <label>Nome do responsável <font style="color:red;font-weight: bold">*</font></label>
                        <input type="text" name="responsavel" id="responsavel" placeholder="Digite o nome do responsável" value="{{$pessoa -> responsavel}}">
                      
                        </div>

                        <div class="input-field" style="width: 130px">
                            <label>Sexo <font style="color:red;font-weight: bold">*</font></label>
                            <select name="sexo" required>
                                <option selected disabled value="">Selecionar</option>
                                <option @if($pessoa->sexo == 1) selected @endif value="1">Masculino</option>
                                <option  @if($pessoa->sexo == 2) selected @endif value="2">Feminino</option>
                                
                            </select>
                        </div>


                        <div class="input-field"  style="width: 200px">
                            <label>Data de nascimento <font style="color:red;font-weight: bold">*</font> </label>
                            <input type="date" name="data_nasc" placeholder="Digite a data de nascimento" required value="{{date('Y-m-d', strtotime($pessoa -> data_nasc))}}">
                        </div>

                        <div class="input-field">
                            <label>Ocupação</label>
                            <input type="text" name="ocupacao" placeholder="Ex.: estudante, professor, enfermeiro..." value="{{$pessoa->ocupacao}}">
                        </div>


                        <div class="input-field" style="width: 280px">
                            <label>Cidade</label>
                            <input type="text" name="cidade" placeholder="Digite a cidade" value="Parnamirim" value="{{$pessoa->cidade}}">
                        </div>


                        <div class="input-field" style="width: 80px">
                            <label>UF <font style="color:red;font-weight: bold">*</font> </label>
                            <select name="id_uf" required>
                                <option disabled value="">Selecionar</option>
                                <option selected value=20>RN</option>
                                @foreach($ufs as $uf)
                                <option @if($uf->id == $pessoa -> id_uf) selected @endif value="{{$uf -> id}}">{{$uf -> nome}}</option>
                                @endforeach
                                
                            </select>
                        </div>

                        <div class="input-field">
                            <label>N° de telefone (DDD + número)<font style="color:red;font-weight: bold">*</font></label>
                            <input type="text" id="field" name="telefone" minlength=11 maxlength=11 pattern="([0-9]{11})" placeholder="Digite o n° de telefone" value="{{$pessoa -> telefone}}">
                        </div>

                        
                        <div class="input-field">
                            <label>Classe <font style="color:red;font-weight: bold">*</font></label>
                            <div class="multipleSelection">
                                <div class="selectBox" 
                                    onclick="showCheckboxes()">
                                    <select>
                                        <option>
                                                @foreach($pessoa -> id_sala as $ids)
                                                    @foreach($salas as $s) 
                                                        @if($ids == $s -> id)
                                                            {{ $s -> nome }},
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                        </option>
                                    </select>
                                    <div class="overSelect"></div>
                                </div>
                      
                                <div id="checkBoxes">
                                    @foreach($salas as $sala)

                                    <label for="first">
                                        <input type="checkbox" @foreach($pessoa -> id_sala as $ids) @if($ids == $sala -> id ) checked @endif @endforeach   name="id_sala[]" value="{{$sala -> id}}" id="first" />
                                        {{$sala -> nome}} - {{$sala -> tipo}}
                                    </label>
                                    
                                    @endforeach

                                </div>
                            </div>
                        </div>



                    

                        
                    </div>
                </div>

                <div class="details ID">
                    <span class="title">Informações gerais</span>

                    <div class="fields">
                    <div class="input-field">
                            <label>Formação <font style="color:red;font-weight: bold">*</font></label>
                            <select name="id_formation" required>
                                <option selected disabled value="">Selecionar</option>
                                @foreach($formations as $formation)
                                <option @if($formation->id == $pessoa -> id_formation) selected @endif value="{{$formation -> id}}">{{$formation -> nome}}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="input-field">
                            <label>Curso(s) (técnico ou superior)</label>
                            <input type="text" name="cursos" placeholder="Digite os cursos que possui" value="{{$pessoa -> cursos}}">
                        </div>


          
                       

            

                        <div class="input-field" >
                            <label>Interesse em ser professor? <font style="color:red; font-weight: bold">*</font></label>
                            <select required name="interesse" id="interesse">
                                <option selected disabled value="">Selecionar</option>
                                <option @if($pessoa->interesse == 1) selected @endif value="1">Sim</option>
                                <option @if($pessoa->interesse == 2) selected @endif value="2">Não</option>
                                <option @if($pessoa->interesse == 3) selected @endif value="3"> Talvez</option>
                              </select>
                        </div>



                        
                        </div>
                        </div>


              <div id="registerp" @if($pessoa->interesse == 2) style="display: none" @endif>
                    <span class="title">Informações necessárias para <font style="color:blue">possível </font>professor </span>
                    <div class="fields">

                    <div class="input-field" style="width: 200px">
                            <label>Sempre frequentou a EBD? <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" name="frequencia_ebd">
                            <option selected disabled value="">Selecionar</option>
                                <option  @if($pessoa->frequencia_ebd == 1) selected @endif value=1>Sim</option>
                                <option  @if($pessoa->frequencia_ebd == 2) selected @endif value=2>Não</option>
                                <option  @if($pessoa->frequencia_ebd == 3) selected @endif value=3>Mais ou menos</option>
                                </select>
                                
                    </div>

                        <div class="input-field" style="width: 170px">
                            <label>Possui curso de teologia? <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" name="curso_teo">
                            <option selected disabled value="">Selecionar</option>
                                <option  @if($pessoa->curso_teo == 1) selected @endif value=1>Sim</option>
                                <option  @if($pessoa->curso_teo == 2) selected @endif value=2>Não</option>
                                </select>
                                
                        </div>


                        <div class="input-field" style="width: 160px">
                            <label>É/foi professor da EBD? <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" name="prof_ebd">
                            <option selected disabled value="">Selecionar</option>
                                <option  @if($pessoa->prof_ebd == 1) selected @endif value=1>Sim</option>
                                <option  @if($pessoa->prof_ebd == 2) selected @endif value=2>Não</option>  
                                </select>
                        
                        </div>

                        <div class="input-field" style="width: 160px">
                            <label>É/foi professor comum? <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" name="prof_comum">
                            <option selected disabled value="">Selecionar</option>
                                <option  @if($pessoa->prof_comum == 1) selected @endif value=1>Sim</option>
                                <option  @if($pessoa->prof_comum == 2) selected @endif value=2>Não</option>  
                                </select>
                        
                        </div>

                  
                        <div class="input-field" style="width: 200px">
                            <label>Para qual público prefere dar aula? <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" name="id_public">
                            <option selected disabled value="">Selecionar</option>
                                @foreach($publicos as $publico)
                                <option @if($pessoa -> id_public == $publico -> id) selected @endif value="{{$publico -> id}}">{{$publico -> nome}}</option>
                                @endforeach
                                </select>
                       
                        </div>
                    </div>
                  </div>
                
                        <button type="submit" class="sumbit">
                            <span class="btnText">Enviar</span>
                            <i class="uil uil-navigator"></i>
                        </button>
                </div> 
            


        </form>
    </div>
    <script
    src="https://code.jquery.com/jquery-3.6.0.js"
    integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>
    <script src="/js/multi-select-dropdown.js"></script>
<script>
    $(document).ready(function() {
  $("#field").keyup(function() {
      $("#field").val(this.value.match(/[0-9]*/));
  });
});


    
</script>
<form>
@endsection