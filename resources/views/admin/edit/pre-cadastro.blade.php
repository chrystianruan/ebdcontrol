@extends('layouts.main')

@section('title', 'Início')

@section('content')
    <link rel="stylesheet" href="/css/cadastroClasse.css">
    <input type="hidden" value="{{ base64_encode(auth()->user()->congregacao_id) }}" id="congregacao">
    <div class="row" style="margin: 2%">
        <div class="col-75">
            <div class="container">
                <h2>Formulário de edição</h2>
                <hr>
                <form action="/admin/update/pre-cadastro/{{$pessoa -> id}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="col-50">

                        @if ($errors->any())
                            <div class="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <h3>Informações Pessoais</h3>


                        <label style="padding: 5px; background-color: #C3E6CB; border-radius: 10px; border: 1px solid #ccc">
                            <input type="checkbox"  id="scales" @if($pessoa->responsavel) checked @endif name="scales"> Menor de idade
                        </label>

                        <label for="nome"><i class="fa fa-user"></i>Nome <font style="color:red;font-weight: bold">*</font></label>
                        <input type="text" id="nome" required name="nome" placeholder="Digite o nome do aluno" value="{{$pessoa->nome}}">


                        <div class="input-field" id="nomeResp" @if(!$pessoa->responsavel)style="display: none"@endif>

                            <label>Nome do responsável <font style="color:red;font-weight: bold">*</font></label>
                            <input type="text" name="responsavel" id="nome_responsavel" value="{{$pessoa->responsavel}}" placeholder="Digite o nome do responsável do aluno">

                        </div>

                        <div class="input-field" id="numeroResp"  @if(!$pessoa->responsavel)style="display: none"@endif>

                            <label>Número do responsável <font style="color:red;font-weight: bold">*</font></label>
                            <input type="text" name="telefone_responsavel" id="telefone_responsavel" value="{{$pessoa->telefone_responsavel}}" minlength=11 maxlength=11 pattern="([0-9]{11})" placeholder="Digite o número do responsável do aluno">

                        </div>

                        <label for="sexo"><i class="fa fa-genderless"></i>Sexo <font style="color:red;font-weight: bold">*</font></label>
                        <select name="sexo" required>
                            <option selected disabled value="">Selecionar</option>
                            <option @if($pessoa->sexo == 1) selected @endif value="1">Masculino</option>
                            <option @if($pessoa->sexo == 2) selected @endif value="2">Feminino</option>

                        </select>

                        <label>Tem filhos? <span style="color:red;font-weight: bold">*</span></label>
                        <select name="filhos" required>
                            <option selected disabled value="">Selecionar</option>
                            <option @if($pessoa->paternidade_maternidade == null) selected @endif value="1">Não</option>
                            <option @if($pessoa->paternidade_maternidade) selected @endif value="2">Sim</option>

                        </select>
                        <label for="data_nasc"><i class="fa fa-calendar"></i>Data de nascimento <font style="color:red;font-weight: bold">*</font></label>
                        <input type="date" id="data_nasc" required name="data_nasc" value="{{date('Y-m-d', strtotime($pessoa -> data_nasc))}}">

                        <label for="ocupacao"><i class="fa fa-black-tie"></i>Ocupação</label>
                        <input type="text" id="ocupacao" name="ocupacao"  value="{{ $pessoa->ocupacao }}" placeholder="Ex.: estudante, professor, policial...">

                        <div class="row">
                            <div class="col-50">
                                <label for="cidade">Cidade <font style="color:red;font-weight: bold">*</font></label>
                                <input type="text" id="cidade" name="cidade" placeholder="Digite a cidade" value="{{ $pessoa->cidade }}">
                            </div>
                            <div class="col-50">
                                <label for="estado">Estado <font style="color:red;font-weight: bold">*</font></label>
                                <select name="id_uf" required>
                                    <option disabled value="">Selecionar</option>
                                    <option selected value=20>RN</option>
                                    @foreach($ufs as $uf)
                                        <option @if($pessoa->id_uf == $uf -> id) selected @endif value="{{$uf -> id}}">{{$uf -> nome}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div id="numero_pessoa">
                            <label for="fname"><i class="fa fa-phone"></i>N° de Telefone (com DDD) <span style="color: blue"></span> </label>
                            <input type="text" id="field" name="telefone" minlength=11 maxlength=11 pattern="([0-9]{11})" placeholder="Digite o n° de telefone" value="{{ $pessoa->telefone }}">
                        </div>
                        <div>
                            <label for="classe">Classe <font style="color:red;font-weight: bold">*</font></label>
                            <select name="list_salas" required>
                                <option disabled value="">Selecionar</option>
                                @foreach($salas as $sala)
                                    <option @if($pessoa->classe == $sala->id) selected @endif value="{{$sala -> id}}">{{$sala -> nome}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="col-50">
                        <h3>Informações Gerais</h3>

                        <label for="formations"><i class="fa fa-book"></i>Formação <font style="color:red;font-weight: bold">*</font></label>
                        <select name="id_formation" required>
                            <option selected disabled value="">Selecionar</option>
                            @foreach($formations as $formation)
                                <option @if($pessoa->id_formation == $formation -> id) selected @endif value="{{$formation -> id}}">{{$formation -> nome}}</option>
                            @endforeach

                        </select>
                        <label for="cursos">Curso(s) (técnico ou superior)</label>
                        <input type="text" id="cursos" name="cursos" placeholder="Curso - Ano de conclusão" value=" {{ $pessoa->cursos }}">

                        <label for="interesse">Interesse em ser professor da EBD? <font style="color:red;font-weight: bold">*</font></label>
                        <select required name="interesse" id="interesse">
                            <option selected disabled value="">Selecionar</option>
                            <option @if($pessoa->interesse == 1) selected @endif value="1">Sim</option>
                            <option @if($pessoa->interesse == 2) selected @endif value="2">Não</option>
                            <option @if($pessoa->interesse == 3) selected @endif value="3"> Talvez</option>
                        </select>
                    </div>

                    <hr>

                    <div class="col-50" id="registerp" @if($pessoa->interesse == 2) style="display:none" @endif>
                        <h3>Informações necessárias para interessado(a) em ser <span style="color: blue">possível</span> professor</h3>

                        <label>Sempre frequentou a EBD? <font style="color:red;font-weight: bold">*</font></label>
                        <select class="inputprof" name="frequencia_ebd">
                            <option selected disabled value="">Selecionar</option>
                            <option  @if($pessoa->frequencia_ebd == 1) selected @endif value=1>Sim</option>
                            <option  @if($pessoa->frequencia_ebd == 2) selected @endif value=2>Não</option>
                            <option  @if($pessoa->frequencia_ebd == 3) selected @endif value=3>Mais ou menos</option>
                        </select>

                        <label>Possui curso de teologia? <font style="color:red;font-weight: bold">*</font></label>
                        <select class="inputprof" name="curso_teo">
                            <option selected disabled value="">Selecionar</option>
                            <option @if($pessoa->curso_teo == 1) selected @endif value=1>Sim</option>
                            <option @if($pessoa->curso_teo == 2) selected @endif value=2>Não</option>
                        </select>

                        <label>É/foi professor da EBD? <font style="color:red;font-weight: bold">*</font></label>
                        <select class="inputprof" name="prof_ebd">
                            <option selected disabled value="">Selecionar</option>
                            <option @if($pessoa->prof_ebd == 1) selected @endif value=1>Sim</option>
                            <option @if($pessoa->prof_ebd == 2) selected @endif value=2>Não</option>
                        </select>

                        <label>É/foi professor secular? <font style="color:red;font-weight: bold">*</font></label>
                        <select class="inputprof" name="prof_comum">
                            <option selected disabled value="">Selecionar</option>
                            <option @if($pessoa->prof_comum == 1) selected @endif value=1>Sim</option>
                            <option @if($pessoa->prof_comum == 2) selected @endif value=2>Não</option>
                        </select>

                        <label>Para qual público prefere dar aula? <font style="color:red;font-weight: bold">*</font></label>
                        <select class="inputprof" name="id_public">
                            <option selected disabled value="">Selecionar</option>
                            @foreach($publicos as $publico)
                                <option @if($pessoa->id_public == $publico->id) selected @endif value="{{$publico -> id}}">{{$publico -> nome}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="submit" value="Atualizar" class="btn">
                </form>
            </div>


        </div>
    </div>
    @push('script-edit-pessoa')
        <script src="/js/cadastroPessoa.js"></script>
        <script src="/js/editPessoa.js"></script>
    @endpush
@endsection
