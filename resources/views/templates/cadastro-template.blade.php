@push('cadastro-pessoa-css')
    <link rel="stylesheet" href="/css/cadastroClasse.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endpush
<div class="row" style="margin: 2%">
    <input type="hidden" id="url" value="{{ url('/api/pessoas') }}">
    <input type="hidden" value="{{ route('api.pessoa.store.verify-duplicated') }}" id="url-verify">
    <div class="col-75">
        <form action="{{route($route)}}" method="POST" id="form-store">
            @csrf
            <input type="hidden" name="congregacao" id="congregacao" value="{{ $congregacao->congregacao_id }}">
            @if ($route == "cadastro.pessoa.classe")
            <input type="hidden" name="classe" id="classe" value="{{ auth()->user()->sala_id }}">
            @endif
            <div class="form-group">
                <h2>{{$title}} - <span style="color: #1d10a7">{{$congregacao->congregacao_nome}} | {{ $congregacao->setor_nome }} | IEADERN PARNAMIRIM</span></h2>
{{--                    <div class="caution">--}}
{{--                        <p><i class="fa fa-exclamation-circle"></i> Antes de cadastrar alguém, certifique-se de que ela já não esteja cadastrada em <a href="/classe/pessoas">pessoas</a>.</p>--}}
{{--                    </div>--}}
                <br>
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

                <label>
                    <input type="checkbox"  id="scales" @if(old('scales')) checked @endif name="scales"> Menor de idade
                </label>
                <div class="ui-widget">
                    <label for="nome" class="label"><i class="fa fa-user"></i>Nome <font style="color:red;font-weight: bold">*</font></label>
                    <input type="text" class="input" id="nome" required name="nome" placeholder="Digite o nome do aluno" value="{{old('nome')}}">
                </div>
                <div class="input-field" id="nomeResp" style="display:none">

                    <label class="label">Nome do responsável <font style="color:red;font-weight: bold">*</font></label>
                    <input type="text" class="input" name="responsavel" id="nome_responsavel" value="{{old('responsavel')}}" placeholder="Digite o nome do responsável do aluno">

                </div>

                <div class="input-field" id="numeroResp" style="display:none">

                    <label class="label">Número do responsável <font style="color:red;font-weight: bold">*</font></label>
                    <input type="text" class="input" name="telefone_responsavel" id="telefone_responsavel" value="{{old('telefone_responsavel')}}" minlength=11 maxlength=11 pattern="([0-9]{11})" placeholder="Digite o número do responsável do aluno">

                </div>

                <label for="sexo" class="label"><i class="fa fa-genderless"></i>Sexo <font style="color:red;font-weight: bold">*</font></label>
                <select name="sexo" required class="select">
                    <option selected disabled value="">Selecionar</option>
                    <option @if(old('sexo') == 1) selected @endif value="1">Masculino</option>
                    <option @if(old('sexo') == 2) selected @endif value="2">Feminino</option>

                </select>

                <label class="label">Tem filhos?<span style="color:red;font-weight: bold">*</span></label>
                <select class="select" name="filhos" required>
                    <option selected disabled value="">Selecionar</option>
                    <option  @if(old('filhos') == 1) selected @endif value="1">Não</option>
                    <option @if(old('filhos') == 2) selected @endif value="2">Sim</option>

                </select>
                <label for="data_nasc" class="label"><i class="fa fa-calendar"></i>Data de nascimento <font style="color:red;font-weight: bold">*</font></label>
                <input type="date" class="input" id="data_nasc" required name="data_nasc" value="{{old('data_nasc')}}">

                <label for="ocupacao" class="label"><i class="fa fa-black-tie"></i>Ocupação</label>
                <input type="text" class="input" id="ocupacao" name="ocupacao"  value="{{old('ocupacao')}}" placeholder="Ex.: estudante, professor, policial...">

                <div class="row">
                    <div class="col-50">
                        <label for="cidade" class="label">Cidade <font style="color:red;font-weight: bold">*</font></label>
                        <input type="text" class="input" id="cidade" name="cidade" placeholder="Digite a cidade" value="Parnamirim">
                    </div>
                    <div class="col-50">
                        <label for="estado" class="label">Estado <font style="color:red;font-weight: bold">*</font></label>
                        <select name="id_uf" class="select" required>
                            <option disabled value="">Selecionar</option>
                            <option selected value=20>RN</option>
                            @foreach($ufs as $uf)
                                <option @if(old('id_uf') == $uf -> id) selected @endif value="{{$uf -> id}}">{{$uf -> nome}}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div id="numero_pessoa">
                    <label for="fname" class="label"><i class="fa fa-phone"></i>N° de Telefone (com DDD) <span style="color: blue"></span> </label>
                    <input type="text" id="field" name="telefone" class="input" minlength=11 maxlength=11 pattern="([0-9]{11})" placeholder="Digite o n° de telefone" value="{{old('telefone')}}">
                </div>

                @if ($route != "cadastro.pessoa.classe")
                <label class="label">Classe <font style="color:red;font-weight: bold">*</font></label>
                <select class="select" name="classe">
                    <option selected disabled value="">Selecionar</option>
                    @foreach($classes as $c)
                        <option @if(old('classe') == $c->id) selected @endif value="{{ $c->id }}">{{ $c->nome }}</option>
                    @endforeach
                </select>
                @endif
                <div class="form-group">
                    <br>
                    <hr>
                    <br>
                    <h3>Informações Gerais</h3>

                    <label class="label" for="formations"><i class="fa fa-book"></i>Formação <font style="color:red;font-weight: bold">*</font></label>
                    <select class="select" name="id_formation" required>
                        <option selected disabled value="">Selecionar</option>
                        @foreach($formations as $formation)
                            <option @if(old('id_formation') == $formation -> id) selected @endif value="{{$formation -> id}}">{{$formation -> nome}}</option>
                        @endforeach

                    </select>
                    <label class="label" for="cursos">Curso(s) (técnico ou superior)</label>
                    <input type="text" id="cursos" name="cursos" class="input" placeholder="Curso - Ano de conclusão">

                    <label class="label" for="interesse">Interesse em ser professor da EBD? <font style="color:red;font-weight: bold">*</font></label>
                    <select required name="interesse" id="interesse" class="select  ">
                        <option selected disabled value="">Selecionar</option>
                        <option @if(old('interesse') == 1) selected @endif value="1">Sim</option>
                        <option @if(old('interesse') == 2) selected @endif value="2">Não</option>
                        <option @if(old('interesse') == 3) selected @endif value="3"> Talvez</option>
                    </select>
                </div>



                <div class="form-group" id="registerp" style="display:none">
                    <br>
                    <hr>
                    <br>
                    <h3>Informações necessárias para interessado(a) em ser <span style="color: blue">possível</span> professor</h3>


                    <label class="label">Sempre frequentou a EBD? <font style="color:red;font-weight: bold">*</font></label>
                    <select class="select" name="frequencia_ebd">
                        <option selected disabled value="">Selecionar</option>
                        <option  @if(old('frequencia_ebd') == 1) selected @endif value=1>Sim</option>
                        <option  @if(old('frequencia_ebd') == 2) selected @endif value=2>Não</option>
                        <option  @if(old('frequencia_ebd') == 3) selected @endif value=3>Mais ou menos</option>
                    </select>

                    <label class="label">Possui curso de teologia? <font style="color:red;font-weight: bold">*</font></label>
                    <select class="select" name="curso_teo">
                        <option selected disabled value="">Selecionar</option>
                        <option @if(old('curso_teo') == 1) selected @endif value=1>Sim</option>
                        <option @if(old('curso_teo') == 2) selected @endif value=2>Não</option>
                    </select>

                    <label class="label">É/foi professor da EBD? <font style="color:red;font-weight: bold" class="label">*</font></label>
                    <select class="select" name="prof_ebd">
                        <option selected disabled value="">Selecionar</option>
                        <option value=1>Sim</option>
                        <option value=2>Não</option>
                    </select>

                    <label class="label">É/foi professor comum? <font style="color:red;font-weight: bold" class="label">*</font></label>
                    <select class="select" name="prof_comum">
                        <option selected disabled value="">Selecionar</option>
                        <option value=1>Sim</option>
                        <option value=2>Não</option>
                    </select>

                    <label class="label">Para qual público prefere dar aula? <font style="color:red;font-weight: bold" class="label">*</font></label>
                    <select class="select" name="id_public">
                        <option selected disabled value="">Selecionar</option>
                        @foreach($publicos as $publico)
                            <option value="{{$publico -> id}}">{{$publico -> nome}}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </form>
    </div>

</div>

@push('scripts-cadastro')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="/js/pessoas.js"></script>
    <script src="/js/cadastroPessoa.js"></script>
@endpush
