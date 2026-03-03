<div class="loading-container" id="modalEditLoading">
    <div class="loading-spinner"></div>
    <p class="loading-text">Carregando...</p>
</div>

<form id="formEditPreCadastro" style="display: none;">
    <input type="hidden" id="editCongregacao" value="{{ encryptId(auth()->user()->congregacao_id) }}">
    <input type="hidden" id="editPessoaId">

    <div class="form-row">
        <div class="form-group">
            <label for="editNome">Nome <span class="required">*</span></label>
            <input type="text" id="editNome" class="input" required>
        </div>

        <div class="form-group">
            <label for="editDataNasc">Data de Nascimento <span class="required">*</span></label>
            <input type="date" id="editDataNasc" class="input" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="editSexo">Sexo <span class="required">*</span></label>
            <select id="editSexo" class="select" required>
                <option value="">Selecionar</option>
                <option value="1">Masculino</option>
                <option value="2">Feminino</option>
            </select>
        </div>

        <div class="form-group">
            <label for="editTelefone">Telefone</label>
            <input type="text" id="editTelefone" class="input" maxlength="11" placeholder="Ex: 84999999999">
        </div>
    </div>

    <!-- Checkbox Menor de Idade -->
    <div class="form-group form-group-checkbox">
        <label class="checkbox-label">
            <input type="checkbox" id="editMenorIdade" onchange="toggleResponsavelFields()">
            <span>Menor de idade</span>
        </label>
    </div>

    <!-- Campos do Responsável (visíveis apenas se menor de idade) -->
    <div id="responsavelFields" class="form-section" style="display: none;">
        <h4 class="form-section-title">Dados do Responsável</h4>
        <div class="form-row">
            <div class="form-group">
                <label for="editNomeResponsavel">Nome do Responsável <span class="required">*</span></label>
                <input type="text" id="editNomeResponsavel" class="input">
            </div>

            <div class="form-group">
                <label for="editTelefoneResponsavel">Telefone do Responsável <span class="required">*</span></label>
                <input type="text" id="editTelefoneResponsavel" class="input" maxlength="11" placeholder="Ex: 84999999999">
            </div>
        </div>
    </div>

    <!-- Checkbox Tem Filhos -->
    <div class="form-group form-group-checkbox">
        <label class="checkbox-label">
            <input type="checkbox" id="editPaternidadeMaternidade">
            <span>Tem filhos (Paternidade/Maternidade)</span>
        </label>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="editClasse">Classe <span class="required">*</span></label>
            <select id="editClasse" class="select" required>
                <option value="">Selecionar</option>
            </select>
        </div>

        <div class="form-group">
            <label for="editCidade">Cidade</label>
            <input type="text" id="editCidade" class="input">
        </div>table-wrapper
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="editEstado">Estado <span class="required">*</span></label>
            <select id="editEstado" class="select" required>
                <option value="">Selecionar</option>
            </select>
        </div>

        <div class="form-group">
            <label for="editOcupacao">Ocupação</label>
            <input type="text" id="editOcupacao" class="input">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="editFormacao">Formação <span class="required">*</span></label>
            <select id="editFormacao" class="select" required>
                <option value="">Selecionar</option>
            </select>
        </div>

        <div class="form-group">
            <label for="editCurso">Curso</label>
            <input type="text" id="editCurso" class="input" placeholder="Ex: Engenharia, Direito, etc.">
        </div>
    </div>

    <!-- Checkbox Interesse em ser Professor -->
    <div class="form-group form-group-checkbox">
        <label class="checkbox-label">
            <input type="checkbox" id="editInteresseProfessor" onchange="toggleProfessorFields()">
            <span>Interesse em ser professor</span>
        </label>
    </div>

    <!-- Campos do Professor (visíveis apenas se tem interesse) -->
    <div id="professorFields" class="form-section" style="display: none;">
        <h4 class="form-section-title">Informações para Professor</h4>

        <div class="form-row">
            <div class="form-group">
                <label for="editFrequenciaEbd">Sempre frequentou a EBD? <span class="required">*</span></label>
                <select id="editFrequenciaEbd"  class="select">
                    <option value="">Selecionar</option>
                    <option value="1">Sim</option>
                    <option value="2">Não</option>
                    <option value="3">Mais ou menos</option>
                </select>
            </div>

            <div class="form-group">
                <label for="editCursoTeo">Possui curso de teologia? <span class="required">*</span></label>
                <select id="editCursoTeo" class="select">
                    <option value="">Selecionar</option>
                    <option value="1">Sim</option>
                    <option value="2">Não</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="editProfEbd">É/foi professor da EBD? <span class="required">*</span></label>
                <select id="editProfEbd" class="select">
                    <option value="">Selecionar</option>
                    <option value="1">Sim</option>
                    <option value="2">Não</option>
                </select>
            </div>

            <div class="form-group">
                <label for="editProfComum">É/foi professor comum? <span class="required">*</span></label>
                <select id="editProfComum" class="select">
                    <option value="">Selecionar</option>
                    <option value="1">Sim</option>
                    <option value="2">Não</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="editPublico">Para qual público prefere dar aula? <span class="required">*</span></label>
                <select id="editPublico" class="select">
                    <option value="">Selecionar</option>
                </select>
            </div>
        </div>
    </div>
</form>
