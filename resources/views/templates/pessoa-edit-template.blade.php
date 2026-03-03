<div class="loading-container" id="editPessoaLoading">
    <div class="loading-spinner"></div>
    <p class="loading-text">Carregando...</p>
</div>

<form id="formEditPessoa" style="display: none;">
    <input type="hidden" id="editPessoaId">
    <input type="hidden" id="editPessoaCongregacao" value="{{ encryptId(auth()->user()->congregacao_id) }}">
    <input type="hidden" id="editPessoaListSalas" value="[]">

    <!-- Situação -->
    <div class="form-section">
        <h4 class="form-section-title"><i class="bx bx-cog"></i> Operações com o Usuário</h4>
        <div class="form-row">
            <div class="form-group">
                <label for="editPessoaSituacao">Situação <span class="required">*</span></label>
                <select id="editPessoaSituacao" class="select" required>
                    <option value="1">Ativo</option>
                    <option value="2">Inativo</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Informações Pessoais -->
    <div class="form-section">
        <h4 class="form-section-title"><i class="bx bx-user"></i> Informações Pessoais</h4>

        <div class="form-group form-group-checkbox">
            <label class="checkbox-label">
                <input type="checkbox" id="editPessoaMenorIdade" onchange="toggleEditPessoaResponsavel()">
                <span>Menor de idade</span>
            </label>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="editPessoaNome">Nome <span class="required">*</span></label>
                <input type="text" id="editPessoaNome" class="input" required placeholder="Digite o nome">
            </div>
            <div class="form-group">
                <label for="editPessoaDataNasc">Data de Nascimento <span class="required">*</span></label>
                <input type="date" id="editPessoaDataNasc" class="input" required>
            </div>
        </div>

        <!-- Responsável -->
        <div id="editPessoaResponsavelFields" class="form-section" style="display: none;">
            <h4 class="form-section-title">Dados do Responsável</h4>
            <div class="form-row">
                <div class="form-group">
                    <label for="editPessoaNomeResponsavel">Nome do Responsável <span class="required">*</span></label>
                    <input type="text" id="editPessoaNomeResponsavel" class="input">
                </div>
                <div class="form-group">
                    <label for="editPessoaTelefoneResponsavel">Telefone do Responsável <span class="required">*</span></label>
                    <input type="text" id="editPessoaTelefoneResponsavel" class="input" maxlength="11" placeholder="Ex: 84999999999">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="editPessoaSexo">Sexo <span class="required">*</span></label>
                <select id="editPessoaSexo" class="select" required>
                    <option value="" disabled>Selecionar</option>
                    <option value="1">Masculino</option>
                    <option value="2">Feminino</option>
                </select>
            </div>
            <div class="form-group">
                <label for="editPessoaFilhos">Tem filhos? <span class="required">*</span></label>
                <select id="editPessoaFilhos" class="select" required>
                    <option value="" disabled>Selecionar</option>
                    <option value="1">Não</option>
                    <option value="2">Sim</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="editPessoaOcupacao">Ocupação</label>
                <input type="text" id="editPessoaOcupacao" class="input" placeholder="Ex.: estudante, professor...">
            </div>
            <div class="form-group">
                <label for="editPessoaTelefone">Telefone (com DDD)</label>
                <input type="text" id="editPessoaTelefone" class="input" maxlength="11" minlength="11" placeholder="Ex: 84999999999">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="editPessoaCidade">Cidade <span class="required">*</span></label>
                <input type="text" id="editPessoaCidade" class="input" placeholder="Digite a cidade">
            </div>
            <div class="form-group">
                <label for="editPessoaEstado">Estado <span class="required">*</span></label>
                <select id="editPessoaEstado" class="select" required>
                    <option value="" disabled>Selecionar</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Classes -->
    <div class="form-section">
        <h4 class="form-section-title"><i class="bx bx-buildings"></i> Classes</h4>
        <p class="edit-pessoa-classes-hint">
            <i class="bx bx-info-circle"></i>
            As classes e funções <strong>Professor, Aluno e Secretário/Classe</strong> <span style="color: #ef4444">NÃO</span> podem ser repetidas.
        </p>
        <div class="edit-pessoa-classes-table-wrapper">
            <table class="edit-pessoa-classes-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Classe</th>
                        <th>Função</th>
                    </tr>
                </thead>
                <tbody id="editPessoaTbodyClasses"></tbody>
            </table>
        </div>
        <button type="button" class="btn btn-secondary" id="editPessoaBtnAddClasse" style="margin-top: 10px; width: 100%;">
            <i class="bx bx-plus"></i> Adicionar classe
        </button>
    </div>

    <!-- Informações Gerais -->
    <div class="form-section">
        <h4 class="form-section-title"><i class="bx bx-book-open"></i> Informações Gerais</h4>
        <div class="form-row">
            <div class="form-group">
                <label for="editPessoaFormacao">Formação <span class="required">*</span></label>
                <select id="editPessoaFormacao" class="select" required>
                    <option value="" disabled>Selecionar</option>
                </select>
            </div>
            <div class="form-group">
                <label for="editPessoaCursos">Curso(s)</label>
                <input type="text" id="editPessoaCursos" class="input" placeholder="Curso - Ano de conclusão">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="editPessoaInteresse">Interesse em ser professor? <span class="required">*</span></label>
                <select id="editPessoaInteresse" class="select" required onchange="toggleEditPessoaProfessor()">
                    <option value="" disabled>Selecionar</option>
                    <option value="1">Sim</option>
                    <option value="2">Não</option>
                    <option value="3">Talvez</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Interesse Professor -->
    <div class="form-section" id="editPessoaProfessorFields" style="display: none;">
        <h4 class="form-section-title"><i class="bx bx-chalkboard"></i> Informações para Professor</h4>
        <div class="form-row">
            <div class="form-group">
                <label for="editPessoaFrequenciaEbd">Sempre frequentou a EBD? <span class="required">*</span></label>
                <select id="editPessoaFrequenciaEbd" class="select editPessoaInputProf">
                    <option value="" disabled selected>Selecionar</option>
                    <option value="1">Sim</option>
                    <option value="2">Não</option>
                    <option value="3">Mais ou menos</option>
                </select>
            </div>
            <div class="form-group">
                <label for="editPessoaCursoTeo">Curso de teologia? <span class="required">*</span></label>
                <select id="editPessoaCursoTeo" class="select editPessoaInputProf">
                    <option value="" disabled selected>Selecionar</option>
                    <option value="1">Sim</option>
                    <option value="2">Não</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="editPessoaProfEbd">É/foi professor da EBD? <span class="required">*</span></label>
                <select id="editPessoaProfEbd" class="select editPessoaInputProf">
                    <option value="" disabled selected>Selecionar</option>
                    <option value="1">Sim</option>
                    <option value="2">Não</option>
                </select>
            </div>
            <div class="form-group">
                <label for="editPessoaProfComum">É/foi professor secular? <span class="required">*</span></label>
                <select id="editPessoaProfComum" class="select editPessoaInputProf">
                    <option value="" disabled selected>Selecionar</option>
                    <option value="1">Sim</option>
                    <option value="2">Não</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="editPessoaPublico">Para qual público? <span class="required">*</span></label>
                <select id="editPessoaPublico" class="select editPessoaInputProf">
                    <option value="" disabled selected>Selecionar</option>
                </select>
            </div>
        </div>
    </div>
</form>
