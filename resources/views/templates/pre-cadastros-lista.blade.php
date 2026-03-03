@push('pessoas.admin.css')
    <link rel="stylesheet" href="{{ cacheBust('css/filtros.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/formGroup.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/buttonsAdmin.css') }}">
@endpush

<div class="container-intern">
    <div>
        <form method="GET" onsubmit="event.preventDefault(); getPreRegisterList(); return false;">
        <div class="fields">
            <div class="filter-header">
                <span class="title">Filtros: </span>
            </div>

            <div class="itens">
                <div>
                    <input type="text" name="nome_pre_register" class="input" placeholder="Digite o nome da pessoa" id="nomePessoaPreCadastro">
                </div>

                <div>
                    <select name="classe_pre_register" class="select" id="classePessoaPreCadastro">
                        <option selected disabled value="">Classe</option>
                    </select>
                </div>

            </div>

            <div class="div-buttons-filter">
                <div class="btnFilter">
                    <button onclick="getPreRegisterList()" type="button" class="btn btn-secondary">Filtrar</button>
                </div>

                <div class="btnFilter">
                    <button type="reset" class="btn btn-danger">Limpar</button>
                </div>
            </div>

        </div>
        </form>
</div>

</div>
<div class="table-container" id="lista-pessoas"></div>

@push('preRegister.admin.js')
    <script src="{{ cacheBust('js/preCadastro.js') }}"></script>
    <script src="{{ cacheBust('js/getPreRegisters.js') }}"></script>
    <script src="{{ cacheBust('js/modalEditPreCadastro.js') }}"></script>
@endpush
