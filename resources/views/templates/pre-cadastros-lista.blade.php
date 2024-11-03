<link rel="stylesheet" href="/css/filtros.css">


<div style="margin: 15px">

    <form action="/admin/filtro/pre-cadastros" method="POST">
        @csrf
        <div class="fields">
            <div class="itens">
                <legend class="title">Filtrar por: </legend>
            </div>


            <div class="itens">
                <input type="text" name="nome" placeholder="Digite o nome da pessoa">

                <select name="classe">
                    <option selected disabled value="">Classe</option>
                    @foreach($salas as $sala)
                        @if($sala -> id > 2)
                            <option value="{{$sala -> id}}">{{$sala -> nome}} - {{$sala -> tipo}}</option>
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
    </form>
</div>

<div class="busca">

    @if(isset($nome) || isset($classe))
        <p class="tit">Buscando por:</p>
        @if(isset($nome))
            <li class="ponto">Nome: <i class="result">{{ $nome }}</i></li>
        @endif

        @if(isset($classe) && empty($nome))
            <li class="ponto">Classe:
                <i class="result">
                    @foreach($salas as $sala)
                        @if($sala -> id == $classe)
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
        <table style="margin:3%; overflow-x: scroll">
            <caption class="cont"><h4>Quantidade: <font style="color:red; background-color: black; border-radius: 5px; padding: 0 10px">{{$pessoas -> count()}}</font></caption>
            <thead>
            <tr>
                <th>Nome
                <th>Idade
                <th>Data Nascimento
                <th>Sexo
                <th>N° de telefone
                <th>Classe

                <th style="text-align: center">Ações
            </thead>
            @foreach($pessoas as $pessoa)
                <tbody>

                <tr @if($pessoa -> situacao == 2) class="disabled" @endif>
                    <td style="width: 350px">
                        <span @if($pessoa->duplicata) style="color: yellow" @endif>{{$pessoa -> nome}}</span> @if($pessoa->duplicata) <i style="font-size: 1.3em;margin: 1px; color: yellow" class='bx bx-error icon'></i> @endif
                    <td  style="width: 100px">@if(floor((strtotime(date('Y-m-d')) - strtotime($pessoa -> data_nasc))/(60 * 60 * 24) /365.25) < 2)
                            {{floor((strtotime(date('Y-m-d')) - strtotime($pessoa->data_nasc))/(60 * 60 * 24) /365.25)}} ano
                        @else
                            {{floor((strtotime(date('Y-m-d')) - strtotime($pessoa->data_nasc))/(60 * 60 * 24) /365.25)}} anos
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

                    <td>
                       {{ $pessoa->sala->nome }}
                    </td>


                    <td style="min-width:100px;"><div style="text-align: center">
                            <a href="/admin/edit/pre-cadastro/{{$pessoa->id}}" style="text-decoration: none; color:black; margin: 5px;float: left; cursor:pointer"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-edit icon'></i> </a>
                            <form action="/admin/approve/pre-cadastro/{{$pessoa -> id}}" id="form-approve-{{ $pessoa->id }}" method="POST">
                                @csrf
                                @method('POST')
                                <button
                                    type=button
                                    class="btn-approve"
                                    id="btn-approve-{{ $pessoa->id }}"
                                    style="background-color: transparent; cursor: pointer; border: none; float: left; margin: 6px; "
                                >
                                    <i style="font-size: 2.2em; color: greenyellow" class='bx bx-check-circle icon'></i>
                                </button>
                            </form>

                            <form action="/admin/remove/pre-cadastro/{{$pessoa -> id}}" id="form-remove-{{ $pessoa->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                    <button
                                        type=button
                                        class="btn-remove"
                                        id="btn-remove-{{ $pessoa->id }}"
                                        style="background-color: transparent; cursor: pointer; border: none; float: left; margin: 6px; "
                                    >
                                        <i style="font-size: 2.2em; color: red" class='bx bx-x-circle icon'></i>
                                    </button>
                            </form>

                        </div>
                    </td>
                </tr>

                </tbody>
            @endforeach
        </table>
    @else
        <div class="ngm">
            <p ><i class='bx bx-stop'></i>Nenhum resultado encontrado!</p>
        </div>
    @endif

    @push('script-edit-pessoa')
    <script src="/js/preCadastro.js"></script>
    @endpush
