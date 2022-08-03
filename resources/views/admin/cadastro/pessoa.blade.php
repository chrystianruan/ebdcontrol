@extends('layouts.main')

@section('title', 'Início')

@section('content')

<link rel="stylesheet" href="/css/cadastro.css">
<div class="container" style="margin-left:5%;">
        <header>Cadastro de pessoa - {{$dataAtual}}</header>

        <form action="/admin/cadastro/pessoa" method="POST">
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
            <div class="caution">
                <p><i class="fa fa-exclamation-circle"></i> Antes de cadastrar alguém, certifique-se de que ela já não esteja cadastrada em <a href="/admin/filtro/pessoa">pessoas</a>.</p>
                </div>
           
                <div class="details personal">
                    <div style="float:right; margin-right: 2%">
                        <label>Menor de idade?</label>
                        <input type="checkbox"  id="scales" name="scales" @if(isset($check)) checked @endif>
                        </div>
                    <span class="title">Informações pessoais</span>
                    
                    
                    <div class="fields">
                        <div class="input-field">
                            <label>Nome completo <font style="color:red;font-weight: bold">*</font></label>
                            <input type="text" name="nome" required placeholder="Digite o nome" value="{{old('nome')}}">
                        </div>

    

                        <div class="input-field" id="nomeResp" style="display:none">
                        
                        <label>Nome do responsável <font style="color:red;font-weight: bold">*</font></label>
                        <input type="text" name="responsavel" id="responsavel" placeholder="Digite o nome do responsável" value="{{old('responsavel')}}">
                      
                        </div>

                        <div class="input-field" style="width: 130px">
                            <label>Sexo <font style="color:red;font-weight: bold">*</font></label>
                            <select name="sexo" required>
                                <option selected disabled value="">Selecionar</option>
                                <option @if(old('sexo') == 1) selected @endif value="1">Masculino</option>
                                <option  @if(old('sexo') == 2) selected @endif value="2">Feminino</option>
                                
                            </select>
                        </div>


                        <div class="input-field"  style="width: 200px">
                            <label>Data de nascimento <font style="color:red;font-weight: bold">*</font> </label>
                            <input type="date" name="data_nasc" placeholder="Digite a data de nascimento" required value="{{old('data_nasc')}}">
                        </div>

                        <div class="input-field">
                            <label>Ocupação</label>
                            <input type="text" name="ocupacao" placeholder="Ex.: estudante, professor, enfermeiro..." value="{{old('ocupacao')}}">
                        </div>


                        <div class="input-field" style="width: 280px">
                            <label>Cidade</label>
                            <input type="text" name="cidade" placeholder="Digite a cidade" value="Parnamirim" value="{{old('cidade')}}">
                        </div>


                        <div class="input-field" style="width: 80px">
                            <label>UF <font style="color:red;font-weight: bold">*</font> </label>
                            <select name="id_uf" required>
                                <option disabled value="">Selecionar</option>
                                <option selected value=20>RN</option>
                                @foreach($ufs as $uf)
                                <option @if($uf->id == old('id_uf')) selected @endif value="{{$uf -> id}}">{{$uf -> nome}}</option>
                                @endforeach
                                
                            </select>
                        </div>

                        <div class="input-field">
                            <label>N° de telefone (DDD + número)<font style="color:red;font-weight: bold">*</font></label>
                            <input type="text" id="field" name="telefone" minlength=11 maxlength=11 pattern="([0-9]{11})" placeholder="Digite o n° de telefone" value="{{old('telefone')}}">
                        </div>

                        <div class="input-field" style="visibility: hidden;">
                            <input type="hidden" value="">
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
                                <option @if($formation->id == old('id_formation')) selected @endif value="{{$formation -> id}}">{{$formation -> nome}}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="input-field">
                            <label>Curso(s) (técnico ou superior)</label>
                            <input type="text" name="cursos" placeholder="Digite os cursos que possui" value="{{old('cursos')}}">
                        </div>



                        <div class="input-field">
                            <label>Classe <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" required name="id_sala">
                            <option selected disabled value="">Selecionar</option>
                                @foreach($salas as $sala)
                                @if($sala -> id > 2)
                                <option @if($sala->id == old('id_sala')) selected @endif value="{{$sala -> id}}">{{$sala -> nome}} - {{$sala -> tipo}}</option>
                                @endif
                                @endforeach
                                </select>
                       
                        </div>

            

                        <div class="input-field" style="width: 180px">
                            <label>Interesse em ser professor? <font style="color:red; font-weight: bold">*</font></label>
                            <select required name="interesse" id="interesse">
                                <option selected disabled value="">Selecionar</option>
                                <option @if(old('interesse') == 1) selected @endif value="1">Sim</option>
                                <option @if(old('interesse') == 2) selected @endif value="2">Não</option>
                                <option @if(old('interesse') == 3) selected @endif value="3"> Talvez</option>
                              </select>
                        </div>

                        <div class="input-field" style="visibility: hidden; width: 500px">
                            <input type="hidden" value="">
                        </div>

                        
                        </div>
                        </div>


              <div id="registerp" style="display:none">
                    <span class="title">Informações necessárias para <font style="color:blue">possível </font>professor </span>
                    <div class="fields">

                    <div class="input-field" style="width: 200px">
                            <label>Sempre frequentou a EBD? <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" name="frequencia_ebd">
                            <option selected disabled value="">Selecionar</option>
                                <option value=1>Sim</option>
                                <option value=2>Não</option>
                                <option value=3>Mais ou menos</option>
                                </select>
                                
                    </div>

                        <div class="input-field" style="width: 170px">
                            <label>Possui curso de teologia? <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" name="curso_teo">
                            <option selected disabled value="">Selecionar</option>
                                <option value=1>Sim</option>
                                <option value=2>Não</option>
                                </select>
                                
                        </div>


                        <div class="input-field" style="width: 160px">
                            <label>É/foi professor da EBD? <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" name="prof_ebd">
                            <option selected disabled value="">Selecionar</option>
                                <option value=1>Sim</option>
                                <option value=2>Não</option>  
                                </select>
                        
                        </div>

                        <div class="input-field" style="width: 160px">
                            <label>É/foi professor comum? <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" name="prof_comum">
                            <option selected disabled value="">Selecionar</option>
                                <option value=1>Sim</option>
                                <option value=2>Não</option>  
                                </select>
                        
                        </div>

                  
                        <div class="input-field" style="width: 200px">
                            <label>Para qual público prefere dar aula? <font style="color:red;font-weight: bold">*</font></label>
                            <select class="inputprof" name="id_public">
                            <option selected disabled value="">Selecionar</option>
                                @foreach($publicos as $publico)
                                <option value="{{$publico -> id}}">{{$publico -> nome}}</option>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
  $("#field").keyup(function() {
      $("#field").val(this.value.match(/[0-9]*/));
  });
});
    
</script>
<form>
@endsection