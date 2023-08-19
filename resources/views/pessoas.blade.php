<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Filtro de alunos</title>
    <link rel="stylesheet" href="/css/barClasse.css">
    <link rel="stylesheet" href="/css/filtrosPessoa.css">
    <link rel="icon" type="imagem/png" href="/img/logo_ebd.png" />
</head>
<body>
<div style="margin: 15px">

    <form action="/pessoas" method="POST">
        @csrf

        <div class="fields">
            <span style="background-color: yellow; padding: 5px; border-radius: 5px"> Se ainda não realizou o cadastro, vá para <a href="/cadastro" style="color: blue; font-weight: bolder">Cadastro</a></span>
            <div class="itens">
                <legend class="title">Filtrar por: </legend>
            </div>
            <span style=";margin-left: 15px;font-size: 12px; color: white">(O filtro <mark>Nome</mark> é exclusivo. Portanto, para funcionar corretamente, não poderá ser usado com outros filtros)</span>


            <div class="itens">
                <input type="text" name="nome" placeholder="Digite o nome da pessoa">

                <select name="sexo">
                    <option selected disabled value="">Sexo</option>
                    <option value="1">Masculino</option>
                    <option value="2">Feminino</option>

                </select>

                <select name="niver">
                    <option selected disabled value="">Aniversário</option>
                    @foreach($meses_abv as $val => $name)
                        <option value="{{$val}}">{{$val}} - {{$name}}</option>
                    @endforeach

                </select>

                <select name="sala">
                    <option selected disabled value="">Classe</option>
                    @foreach($salas as $sala)
                        @if($sala -> id > 2)
                            <option @if($sala->id == old('id_sala')) selected @endif value="{{$sala -> id}}">{{$sala -> nome}} - {{$sala -> tipo}}</option>
                        @endif
                    @endforeach

                </select>


                <div class="btnFilter">
                    <button type="submit" class="filter">Filtrar</button>
                </div>

                <div class="btnFilter">
                    <button type="reset" class="resett">Limpar tudo</button>
                </div>
            </div>
        </div>
</div>
</form>
<div class="busca">

    @if(isset($nome) || isset($sexo) || isset($id_funcao) || isset($niver) || isset($situacao) || isset($interesse) || isset($sala1))
        <p class="tit">Buscando por: @if(isset($nome) && (isset($sexo) || isset($id_funcao) || isset($situacao)))<i class="result">Tudo</i> @endif</p>
        @if(isset($nome) && empty($sexo) && empty($id_funcao) && empty($situacao))
            <li class="ponto">Nome: <i class="result">{{$nome}}</i></li>
        @endif

        @if(isset($sexo) && empty($nome))
            <li class="ponto">Sexo: <i class="result">@if($sexo == 1) Masculino @else Feminino @endif</i></li>
        @endif

        @if(isset($niver) && empty($nome))
            <li class="ponto">Aniversário: <i class="result">@foreach($meses_abv as $num => $mes) @if($niver == $num) {{$num}} - {{$mes}} @endif @endforeach</i></li>
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

</div>
@else

    <p class="tit">Buscando por: <i class="result">Tudo</i></p>

    @endif


    </div>

    </div>

    @if($pessoas->count() > 0)
        <table style="margin:3%">
            <caption class="cont"><h4>Qntd.: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$pessoas -> count()}}</font></caption>
            <thead>
            <tr>
                <th >Nome</th>
                <th >Classe</th>

            </tr>
            </thead>

            <tbody>
            @foreach($pessoas as $pessoa)
                <tr @if($pessoa -> situacao == 2) class="disabled" @endif>
                    <td style="width: 350px">{{$pessoa -> nome}}</td>
                    <td style="width: 350px">
                        @foreach($pessoa->id_sala as $id_sal)
                            @foreach($salas as $sal)
                                @if($sal -> id == $id_sal)
                                    * {{$sal->nome}}
                                @endif
                            @endforeach
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    @else
        <div class="ngm">
            <p><i class='fa fa-exclamation-triangle'></i>Não há pessoas cadastradas para os filtros escolhidos!</p>
        </div>
    @endif


</body>
</html>




