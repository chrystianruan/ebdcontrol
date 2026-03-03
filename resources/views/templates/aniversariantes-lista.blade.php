@push('pessoas.admin.css')
    <link rel="stylesheet" href="{{ cacheBust('css/filtros.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/formGroup.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/buttonsAdmin.css') }}">
@endpush

<div class="container-intern">
    <div>
        <form method="GET" onsubmit="event.preventDefault(); getAniversariantes(); return false;">
            <div class="fields">
                <div class="filter-header">
                    <span class="title">Filtros: </span>
                </div>

                <div class="itens">
                    <div>
                        <select class="select" id="mesAniversariante">
                            <option selected disabled value="">Mês</option>
                            @foreach($meses_abv as $val => $name)
                                <option value="{{ $val }}">{{ $val }} - {{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select class="select" id="classeAniversariante">
                            <option selected disabled value="">Classe</option>
                        </select>
                    </div>

                    <div>
                        <select class="select" id="funcaoAniversariante">
                            <option selected disabled value="">Função</option>
                        </select>
                    </div>

                    <div>
                        <select class="select" id="orderByAniversariante">
                            <option selected disabled value="">Ordenar por</option>
                            <option value="1">Nome</option>
                            <option value="2">Dia de Nascimento</option>
                        </select>
                    </div>
                </div>

                <div class="div-buttons-filter">
                    <div class="btnFilter">
                        <button onclick="getAniversariantes()" type="button" class="btn btn-secondary">Filtrar</button>
                    </div>

                    <div class="btnFilter">
                        <button type="reset" class="btn btn-danger" onclick="clearBirthdayFilters()">Limpar</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<div class="table-container" id="lista-aniversariantes"></div>

@push('aniversariantes.admin.js')
    <script src="{{ cacheBust('js/getAniversariantes.js') }}"></script>
@endpush
