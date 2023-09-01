@extends('layouts.main')

@section('title', 'Início')

@section('content')
    <link rel="stylesheet" href="/css/cadastroClasse.css">
    <div class="row" style="margin: 2%">
        <div class="col-75">
            <div class="container">
                <h2>Formulário de cadastro</h2>
                <form action="/admin/cadastro/pessoa" method="POST">
                    @csrf
                    <div class="col-50">
                        <div class="caution" style="padding: 5px">
                            <p><i class="fa fa-exclamation-circle"></i> Antes de se cadastrar algúem, certifique-se de que a pessoa já não esteja cadastrado(a) em <a href="/admin/filtro/pessoa">pessoas</a>.</p>
                        </div>
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

                        <label style="padding: 5px; background-color: #C3E6CB; border-radius: 10px; border: 1px solid #ccc">
                            <input type="checkbox"  id="scales" @if(old('scales')) checked @endif name="scales"> Menor de idade
                        </label>

                        <label for="id_sala"><i class="fa fa-genderless"></i>Classe <font style="color:red;font-weight: bold">*</font></label>
                        <select name="id_sala" required>
                            <option selected disabled value="">Selecionar</option>
                            @foreach($salas as $sala)
                                @if($sala -> id > 2)
                                    <option @if($sala->id == old('id_sala')) selected @endif value="{{$sala -> id}}">{{$sala -> nome}} - {{$sala -> tipo}}</option>
                                @endif
                            @endforeach

                        </select>
                        <label for="nome"><i class="fa fa-user"></i>Nome <font style="color:red;font-weight: bold">*</font></label>
                        <input type="text" id="nome" required name="nome" placeholder="Digite o nome do aluno" value="{{old('nome')}}">

                        <div class="input-field" id="nomeResp" style="display:none">

                            <label>Nome do responsável <font style="color:red;font-weight: bold">*</font></label>
                            <input type="text" name="responsavel" id="nome_responsavel" value="{{old('responsavel')}}" placeholder="Digite o nome do responsável do aluno">

                        </div>

                        <div class="input-field" id="numeroResp" style="display:none">

                            <label>Número do responsável <font style="color:red;font-weight: bold">*</font></label>
                            <input type="text" name="telefone_responsavel" id="telefone_responsavel" value="{{old('telefone_responsavel')}}" minlength=11 maxlength=11 pattern="([0-9]{11})" placeholder="Digite o número do responsável do aluno">

                        </div>

                        <label for="sexo"><i class="fa fa-genderless"></i>Sexo <font style="color:red;font-weight: bold">*</font></label>
                        <select name="sexo" required>
                            <option selected disabled value="">Selecionar</option>
                            <option @if(old('sexo') == 1) selected @endif value="1">Masculino</option>
                            <option @if(old('sexo') == 2) selected @endif value="2">Feminino</option>

                        </select>

                        <label>Tem filhos?<span style="color:red;font-weight: bold">*</span></label>
                        <select name="filhos" required>
                            <option selected disabled value="">Selecionar</option>
                            <option @if(old('filhos') == 1) selected @endif value="1">Não</option>
                            <option @if(old('filhos') == 2) selected @endif value="2">Sim</option>

                        </select>
                        <label for="data_nasc"><i class="fa fa-calendar"></i>Data de nascimento <font style="color:red;font-weight: bold">*</font></label>
                        <input type="date" id="data_nasc" required name="data_nasc" value="{{old('data_nasc')}}">

                        <label for="ocupacao"><i class="fa fa-black-tie"></i>Ocupação</label>
                        <input type="text" id="ocupacao" name="ocupacao"  value="{{old('ocupacao')}}" placeholder="Ex.: estudante, professor, policial...">

                        <div class="row">
                            <div class="col-50">
                                <label for="cidade">Cidade <font style="color:red;font-weight: bold">*</font></label>
                                <input type="text" id="cidade" name="cidade" placeholder="Digite a cidade" value="Parnamirim">
                            </div>
                            <div class="col-50">
                                <label for="estado">Estado <font style="color:red;font-weight: bold">*</font></label>
                                <select name="id_uf" required>
                                    <option disabled value="">Selecionar</option>
                                    <option selected value=20>RN</option>
                                    @foreach($ufs as $uf)
                                        <option @if(old('id_uf') == $uf -> id) selected @endif value="{{$uf -> id}}">{{$uf -> nome}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div id="numero_pessoa">
                            <label for="fname"><i class="fa fa-phone"></i>N° de Telefone (com DDD) <span style="color: blue"></span> </label>
                            <input type="text" id="field" name="telefone" minlength=11 maxlength=11 pattern="([0-9]{11})" placeholder="Digite o n° de telefone" value="{{old('telefone')}}">
                        </div>
                    </div>

                    <div class="col-50">
                        <h3>Informações Gerais</h3>

                        <label for="formations"><i class="fa fa-book"></i>Formação <font style="color:red;font-weight: bold">*</font></label>
                        <select name="id_formation" required>
                            <option selected disabled value="">Selecionar</option>
                            @foreach($formations as $formation)
                                <option @if(old('id_formation') == $formation -> id) selected @endif value="{{$formation -> id}}">{{$formation -> nome}}</option>
                            @endforeach

                        </select>
                        <label for="cursos">Curso(s) (técnico ou superior)</label>
                        <input type="text" id="cursos" name="cursos" placeholder="Curso - Ano de conclusão">

                        <label for="interesse">Interesse em ser professor da EBD? <font style="color:red;font-weight: bold">*</font></label>
                        <select required name="interesse" id="interesse">
                            <option selected disabled value="">Selecionar</option>
                            <option @if(old('interesse') == 1) selected @endif value="1">Sim</option>
                            <option @if(old('interesse') == 2) selected @endif value="2">Não</option>
                            <option @if(old('interesse') == 3) selected @endif value="3"> Talvez</option>
                        </select>
                    </div>



                    <div class="col-50" id="registerp" style="display:none">
                        <h3>Informações necessárias para interessado(a) em ser <span style="color: blue">possível</span> professor</h3>


                        <label>Sempre frequentou a EBD? <font style="color:red;font-weight: bold">*</font></label>
                        <select class="inputprof" name="frequencia_ebd">
                            <option selected disabled value="">Selecionar</option>
                            <option  @if(old('frequencia_ebd') == 1) selected @endif value=1>Sim</option>
                            <option  @if(old('frequencia_ebd') == 2) selected @endif value=2>Não</option>
                            <option  @if(old('frequencia_ebd') == 3) selected @endif value=3>Mais ou menos</option>
                        </select>

                        <label>Possui curso de teologia? <font style="color:red;font-weight: bold">*</font></label>
                        <select class="inputprof" name="curso_teo">
                            <option selected disabled value="">Selecionar</option>
                            <option @if(old('curso_teo') == 1) selected @endif value=1>Sim</option>
                            <option @if(old('curso_teo') == 2) selected @endif value=2>Não</option>
                        </select>

                        <label>É/foi professor da EBD? <font style="color:red;font-weight: bold">*</font></label>
                        <select class="inputprof" name="prof_ebd">
                            <option selected disabled value="">Selecionar</option>
                            <option value=1>Sim</option>
                            <option value=2>Não</option>
                        </select>

                        <label>É/foi professor secular? <font style="color:red;font-weight: bold">*</font></label>
                        <select class="inputprof" name="prof_comum">
                            <option selected disabled value="">Selecionar</option>
                            <option value=1>Sim</option>
                            <option value=2>Não</option>
                        </select>

                        <label>Para qual público prefere dar aula? <font style="color:red;font-weight: bold">*</font></label>
                        <select class="inputprof" name="id_public">
                            <option selected disabled value="">Selecionar</option>
                            @foreach($publicos as $publico)
                                <option value="{{$publico -> id}}">{{$publico -> nome}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="submit" value="Cadastrar" class="btn">
                </form>
            </div>


        </div>
    </div>
    <script
        src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>

    <script>

    </script>
@endsection
