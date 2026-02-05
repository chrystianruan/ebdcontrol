@push('pessoas.admin.css')
    <link rel="stylesheet" href="/css/filtros.css">
    <link rel="stylesheet" href="/css/formGroup.css">
    <link rel="stylesheet" href="/css/buttonsAdmin.css">
@endpush

<div class="container-intern">
    <div>
        <form action="/admin/filtro/pessoa" method="GET">
            <div class="fields">
                <div class="filter-header">
                    <span class="title">Filtros: </span>
                </div>

                <div class="itens">
                    <div>
                        <input type="text" name="nome" class="input" placeholder="Digite o nome da pessoa">
                    </div>

                    <div>
                        <select name="sexo" class="select">
                            <option selected disabled value="">Sexo</option>
                            <option value="1">Masculino</option>
                            <option value="2">Feminino</option>
                        </select>
                    </div>

                    <div>
                        <select name="paternidade_maternidade" class="select">
                            <option selected disabled value="">Paternidade/Maternidade</option>
                            <option value="Pai">Pai</option>
                            <option value="Mãe">Mãe</option>

                        </select>
                    </div>

                    <div>
                        <select name="sala" class="select">
                            <option selected disabled value="">Classe</option>
                            @foreach($salas as $sala)
                                @if($sala -> id > 2)
                                    <option value="{{$sala -> id}}">{{$sala -> nome}} - {{$sala -> tipo}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="niver" class="select">
                            <option selected disabled value="">Aniversário</option>
                            @foreach($meses_abv as $val => $name)
                                <option value="{{$val}}">{{$val}} - {{$name}}</option>
                            @endforeach

                        </select>
                    </div>

                    <div>
                        <select name="id_funcao" class="select">
                            <option selected disabled value="">Função</option>
                            @foreach($funcoes as $func)
                                <option value="{{ $func->id }}">{{ $func->nome }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div>
                        <select name="interesse" class="select">
                            <option selected disabled value="">Interesse</option>
                            <option value="1">Sim</option>
                            <option value="2">Não</option>
                            <option value="3">Talvez</option>

                        </select>
                    </div>

                    <div>
                        <select name="situacao" class="select">
                            <option selected disabled value="">Situação</option>
                            <option value="1">Ativo</option>
                            <option value="2">Inativo</option>

                        </select>
                    </div>
                </div>
                <div class="div-buttons-filter">
                    <div class="btnFilter">
                        <button type="submit" class="btn btn-secondary">Filtrar</button>
                    </div>

                    <div class="btnFilter">
                        <button type="reset" class="btn btn-danger">Limpar</button>
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
            <li class="ponto">Função:
                <i class="result">
                    @foreach($funcoes as $funcao)
                        @if($funcao -> id == $id_funcao)
                            {{$funcao -> nome}}
                        @endif
                    @endforeach
                </i>
            </li>
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


    @else
        <p class="tit">Buscando por: <i class="result">Tudo</i></p>
    @endif
</div>
    <div class="div-btn-register">
        <button class="btn btn-secondary" onclick="openModalBirthday()">Aniversariantes <i class="bx bx-cake" style="font-size: 1.5em; padding-left: 10px"></i> </button>

        <button class="btn btn-secondary" onclick="openModalPreRegister()">Pré-Cadastros <i class="bx bx-list-ul" style="font-size: 1.5em; padding-left: 10px"></i> </button>

        <button class="btn btn-primary" onclick="openModalRegister()">Cadastrar Pessoa <i class="bx bx-user-plus" style="font-size: 1.5em; padding-left: 10px"></i> </button>
    </div>
    <div class="table-container">

    @if($pessoas->count() > 0)

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th class="container-hideable">Aniversário</th>
                        <th>N° de telefone</th>
                        <th class="container-hideable">Classe/Funcão</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($pessoas as $pessoa)
                    <tr @if($pessoa->situacao == 2) class="disabled" @endif>
                        <td>
                            <strong>{{$pessoa -> nome}}</strong>
                        </td>
                        <td class="container-hideable">
                            {{ date('d/m', strtotime($pessoa->data_nasc)) }}
                        </td>
                        <td>
                            @if($pessoa -> telefone == null)
                               <span class="text-muted"> - </span>
                            @else
                                <a
                                    href="https://api.whatsapp.com/send?phone=55{{ $pessoa->telefone }}"
                                    target="blank"
                                    class="phone-link"
                                    title="Chamar no WhatsApp"
                                >
                                    <i class='bx bxl-whatsapp'></i>
                                    {{$pessoa->getFormattedPhoneNumber() }}
                                </a>
                            @endif
                        </td>
                        <td class="classes-cell container-hideable">
                            <div class="classes-list">
                                @foreach($pessoa->salas as $key=>$sala)
                                    <div class="class-item-badge class-badge-professor">
                                        <span class="class-name">{{ $sala->nome }} </span>
                                        <span class="class-type professor">({{ $pessoa->funcoes[$key]['nome'] }})</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        <td >
                            <div class="table-actions">
                                <button class="action-btn action-btn-view" title="Visualizar">
                                    <i class="bx bx-show icon"></i>
                                </button>
                                <button class="action-btn action-btn-edit" title="Editar">
                                    <i class="bx bx-edit icon"></i>
                                </button>
{{--                                <a href="/admin/visualizar/pessoa/{{$pessoa->id}}" style="text-decoration: none; color:black; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-show icon'></i> </a>--}}
{{--                                <a href="/admin/edit/pessoa/{{$pessoa->id}}" style="text-decoration: none; color:black; margin: 5px;float: left"><i style="font-size: 1.8em;margin: 1px; float:left" class='bx bx-edit icon'></i> </a>--}}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            <div class="pagination-info">
                <strong>{{ $pessoas->count() * $pessoas->currentPage() }}</strong> de <strong> {{ $pessoas->total() }}</strong>
            </div>

            <ul class="pagination pagination-minimal">
                @if(!$pessoas->onFirstPage())
                    <li>
                        <a href="{{ $pessoas->previousPageUrl() }}" class="pagination-btn pagination-btn-prev">
                            <i class="fas fa-chevron-left"></i>
                            <span>Anterior</span>
                        </a>
                    </li>
                @endif

                @php
                    $atual = $pessoas->currentPage();
                    $ultima = $pessoas->lastPage();

                    // Calcular range de 5 páginas ao redor da atual
                    $inicio = max(1, $atual - 2);
                    $fim = min($ultima, $atual + 2);

                    // Ajustar para sempre ter 5 páginas no meio (quando possível)
                    if ($fim - $inicio < 4) {
                        if ($atual < 3) {
                            $fim = min($ultima, 5);
                        } else {
                            $inicio = max(1, $ultima - 4);
                        }
                    }
                @endphp

                @if ($inicio > 1)
                    <li>
                        <a href="{{ $pessoas->url(1) }}" class="pagination-btn @if($atual == 1) active @endif">1</a>
                    </li>

                    @if ($inicio > 2)
                        <li><span class="pagination-ellipsis">...</span></li>
                    @endif
                @endif

                @for ($page = $inicio; $page <= $fim; $page++)
                        <li><a href="{{ $pessoas->url($page) }}" class="pagination-btn @if($page == $atual) active @endif">{{ $page }}</a></li>
                @endfor

                @if ($fim < $ultima)
                    @if ($fim < $ultima - 1)
                        <li><span class="pagination-ellipsis">...</span></li>
                    @endif

                    <li>
                        <a href="{{ $pessoas->url($ultima) }}" class="pagination-btn @if($page == $atual) active @endif">{{ $ultima }}</a>
                    </li>
                @endif

                @if ($pessoas->hasMorePages())
                    <li>
                        <a href="{{$pessoas->nextPageUrl()}}" class="pagination-btn pagination-btn-next">
                            <span>Próximo</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    @else
        <div class="table-header">
            <h3 class="table-title">Pessoas</h3>
            <span class="table-count">0 registros</span>
        </div>
        <div class="table-empty">
            <div class="table-empty-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="table-empty-text">Nenhuma pessoa encontrada</div>
            <div class="table-empty-subtext">Tente ajustar os filtros ou cadastrar uma nova pessoa</div>
        </div>
    @endif
    </div>
</div>

@include('templates.modal-admin-template', [
    'modalId' => 'modalRegister',
    'modalTitle' => 'Cadastro',
    'modalBody' => 'templates.cadastro-template',
    'routeModal' => 'cadastro.pessoa.admin',
    'closeModal' => 'closeModalRegister()',
])

@include('templates.modal-admin-template', [
    'modalId' => 'modalPreRegister',
    'modalTitle' => 'Pré-Cadastros',
    'modalBody' => 'loremipsum',
    'closeModal' => 'closeModalPreRegister()',
])

@push('pessoas-filtro.admin.script')
    <script>
        const modalRegister = document.getElementById('modalRegister');
        const modalPreRegister = document.getElementById('modalPreRegister');


        function openModalRegister() {
            modalRegister.classList.add('active');
        }

        function closeModalRegister() {
            modalRegister.classList.remove('active');
        }

        function openModalPreRegister() {
            modalPreRegister.classList.add('active');
        }

        function closeModalPreRegister() {
            modalPreRegister.classList.remove('active');
        }
    </script>
@endpush
