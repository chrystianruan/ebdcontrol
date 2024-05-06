<link rel="stylesheet" href="/css/filtros.css">


<div style="margin: 15px">

    <form action="/filter-pessoa" method="POST">
        @csrf
        <input type="hidden" value="{{ $view }}" name="view">
        <div class="fields">
            <div class="itens">
                <legend class="title">Filtrar por: </legend>
            </div>


            <div class="itens">
                <input type="text" name="nome" placeholder="Digite o nome da pessoa">

                <select name="sexo">
                    <option selected disabled value="">Sexo</option>
                    <option value="1">Masculino</option>
                    <option value="2">Feminino</option>

                </select>

                <select name="paternidade_maternidade">
                    <option selected disabled value="">Paternidade/Maternidade</option>
                    <option value="Pai">Pai</option>
                    <option value="Mãe">Mãe</option>

                </select>

                <select name="sala">
                    <option selected disabled value="">Classe</option>
                    @foreach($salas as $sala)
                        @if($sala -> id > 2)
                            <option value="{{$sala -> id}}">{{$sala -> nome}} - {{$sala -> tipo}}</option>
                        @endif
                    @endforeach
                </select>

                <select name="niver">
                    <option selected disabled value="">Aniversário</option>
                    @foreach($meses_abv as $val => $name)
                        <option value="{{$val}}">{{$val}} - {{$name}}</option>
                    @endforeach

                </select>

                <select name="id_funcao">
                    <option selected disabled value="">Função</option>
                    <option value="1">Aluno</option>
                    <option value="2">Professor</option>
                    <option value="3">Secretário/Classe</option>
                    <option value="4">Secretário/Adm</option>
                    <option value="5">Superintendente</option>

                </select>

                <select name="interesse">
                    <option selected disabled value="">Interesse</option>
                    <option value="1">Sim</option>
                    <option value="2">Não</option>
                    <option value="3">Talvez</option>

                </select>

                <select name="situacao">
                    <option selected disabled value="">Situação</option>
                    <option value="1">Ativo</option>
                    <option value="2">Inativo</option>

                </select>


                <div class="btnFilter">
                    <button type="submit" class="filter">Filtrar</button>
                </div>

                <div class="btnFilter">
                    <button type="reset" class="resett">Limpar tudo</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="busca">

    @if(isset($nome) || isset($sexo) || isset($id_funcao) || isset($situacao) || isset($sala1) || isset($niver) || isset($paternidade_maternidade) || isset($interesse))
        <p class="tit">Buscando por:</p>
        @if(isset($nome))
            <li class="ponto">Nome: <i class="result">{{ $nome }}</i></li>
        @endif

        @if(isset($sexo) && empty($nome))
            <li class="ponto">Sexo: <i class="result">@if($sexo == 1) Masculino @else Feminino @endif</i></li>
        @endif

        @if(isset($paternidade_maternidade) && empty($nome))
            <li class="ponto">Paternidade/Maternidade: <i class="result">{{ $paternidade_maternidade }}</i></li>
        @endif

        @if(isset($sala1) && empty($nome))
            <li class="ponto">Classe:
                <i class="result">
                    @foreach($salas as $sala)
                        @if($sala -> id == $sala1)
                            {{$sala -> nome}}
                        @endif
                    @endforeach
                </i>
            </li>
        @endif

        @if(isset($id_funcao) && empty($nome))
            <li class="ponto">Função: <i class="result">@if($id_funcao == 1) Aluno @elseif($id_funcao == 2) Professor @elseif($id_funcao == 3) Secretário/Classe @elseif($id_funcao == 4) Secretário/Adm @elseif($id_funcao == 5) Superintendente @else Erro @endif</i></li>
        @endif

        @if(isset($interesse) && empty($nome))
            <li class="ponto">Interesse: <i class="result">@if($interesse == 1) Sim @elseif($interesse == 2) Não @else Talvez @endif</i></li>
        @endif

        @if(isset($situacao) && empty($nome))
            <li class="ponto">Situação: <i class="result">@if($situacao == 1) Ativo @else Inativo @endif</i></li>
        @endif




        @if(isset($niver) && empty($nome))
            <li class="ponto">Aniversário: <i class="result">@foreach($meses_abv as $num => $month) @if($niver == $num) {{ $num }} - {{ $month }} @endif @endforeach</i></li>
        @endif

</div>
@else

    <p class="tit">Buscando por: <i class="result">Tudo</i></p>

    @endif


    </div>

    </div>

    @if($pessoas->count() > 0)
        <table style="margin:3%; overflow-x: scroll">
            <caption class="cont"><h4>Matriculados: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$pessoas -> count()}}</font></caption>
            <thead>
            <tr>
                <th>Nome
                <th>Idade
                <th>Data Nascimento
                <th>Sexo
                <th>N° de telefone
                <th>Classe/Funcão

                <th style="text-align: center">Ações
            </thead>
            @foreach($pessoas as $pessoa)
                <tbody>

                <tr @if($pessoa -> situacao == 2) class="disabled" @endif>
                    <td style="width: 350px">{{$pessoa -> nome}}
                    <td  style="width: 100px">@if(floor((strtotime($dataAtual) - strtotime($pessoa -> data_nasc))/(60 * 60 * 24) /365.25) < 2)
                            {{floor((strtotime($dataAtual) - strtotime($pessoa->data_nasc))/(60 * 60 * 24) /365.25)}} ano
                        @else
                            {{floor((strtotime($dataAtual) - strtotime($pessoa->data_nasc))/(60 * 60 * 24) /365.25)}} anos
                    @endif
                    <td>{{date('d/m/Y', strtotime($pessoa -> data_nasc))}}
                    <td>@if($pessoa -> sexo == 1)
                            M
                        @elseif($pessoa -> sexo == 2)
                            F
                        @else
                            Erro
                    @endif
                    <td @if($pessoa -> telefone == null) style="color: gray; text-align: center" @endif>
                        @if($pessoa -> telefone == null)
                            -
                        @else
                            <a class="link-wpp" href="https://api.whatsapp.com/send?phone=55{{ $pessoa->telefone }}" target="blank"> {{$pessoa -> telefone}} </a>

                    @endif

                    <td style="width: 180px">  <div class="wrapper">
                            Ver classes
                            <div class="tooltip">
                                <ul>
                                    @foreach($pessoa->salas as $key=>$sala)
                                        <li>{{ $sala->nome }} <span style="color: blue"> ({{ $pessoa->funcoes[$key]['nome'] }}) </span> </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </td>


                    <td style="min-width:100px;"><div style="text-align: center">
                            <a href="/admin/visualizar/pessoa/{{$pessoa->id}}" style="text-decoration: none; color:black; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-show icon'></i> </a>
                            <a href="/admin/edit/pessoa/{{$pessoa->id}}" style="text-decoration: none; color:black; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-edit icon'></i> </a>
                        </div>
                    </td>
                </tr>

                </tbody>
            @endforeach
        </table>
    @else
        <div class="ngm">
            <p ><i class='bx bx-stop'></i>Não há pessoas cadastradas para os filtros escolhidos!</p>
        </div>
    @endif
