<div class="loading-container" id="viewPessoaLoading">
    <div class="loading-spinner"></div>
    <p class="loading-text">Carregando...</p>
</div>

<div id="viewPessoaContent" style="display: none;">
    <!-- Status Badges -->
    <div class="pessoa-badges">
        <span class="pessoa-badge" id="viewBadgeSituacao"></span>
        <span class="pessoa-badge" id="viewBadgeSexo"></span>
        <span class="pessoa-badge pessoa-badge-info" id="viewBadgeMenor" style="display: none;">
            <i class="bx bx-child"></i> Menor de idade
        </span>
    </div>

    <!-- Nome -->
    <div class="pessoa-nome-header">
        <h3 id="viewNome"></h3>
    </div>

    <!-- Classes -->
    <div class="pessoa-classes" id="viewClasses"></div>

    <!-- Info Pessoal -->
    <div class="form-section">
        <h4 class="form-section-title"><i class="bx bx-user"></i> Informações Pessoais</h4>
        <div class="pessoa-info-grid">
            <div class="pessoa-info-item">
                <span class="pessoa-info-label"><i class="bx bx-cake"></i> Idade</span>
                <span class="pessoa-info-value" id="viewIdade"></span>
            </div>
            <div class="pessoa-info-item">
                <span class="pessoa-info-label"><i class="bx bx-calendar"></i> Nascimento</span>
                <span class="pessoa-info-value" id="viewNascimento"></span>
            </div>
            <div class="pessoa-info-item">
                <span class="pessoa-info-label"><i class="bx bx-map"></i> Endereço</span>
                <span class="pessoa-info-value" id="viewEndereco"></span>
            </div>
            <div class="pessoa-info-item">
                <span class="pessoa-info-label"><i class="bx bx-phone"></i> Telefone</span>
                <span class="pessoa-info-value" id="viewTelefone"></span>
            </div>
            <div class="pessoa-info-item">
                <span class="pessoa-info-label"><i class="bx bx-briefcase"></i> Ocupação</span>
                <span class="pessoa-info-value" id="viewOcupacao"></span>
            </div>
            <div class="pessoa-info-item">
                <span class="pessoa-info-label"><i class="bx bx-heart"></i> Paternidade/Maternidade</span>
                <span class="pessoa-info-value" id="viewPaternidade"></span>
            </div>
        </div>
    </div>

    <!-- Responsavel -->
    <div class="form-section" id="viewResponsavelSection" style="display: none;">
        <h4 class="form-section-title"><i class="bx bx-shield"></i> Dados do Responsável</h4>
        <div class="pessoa-info-grid">
            <div class="pessoa-info-item">
                <span class="pessoa-info-label">Nome do Responsável</span>
                <span class="pessoa-info-value" id="viewResponsavelNome"></span>
            </div>
            <div class="pessoa-info-item">
                <span class="pessoa-info-label">Telefone do Responsável</span>
                <span class="pessoa-info-value" id="viewResponsavelTelefone"></span>
            </div>
        </div>
    </div>

    <!-- Info Gerais -->
    <div class="form-section">
        <h4 class="form-section-title"><i class="bx bx-book-open"></i> Informações Gerais</h4>
        <div class="pessoa-info-grid">
            <div class="pessoa-info-item">
                <span class="pessoa-info-label"><i class="bx bx-graduation"></i> Escolaridade</span>
                <span class="pessoa-info-value" id="viewFormacao"></span>
            </div>
            <div class="pessoa-info-item" id="viewCursosItem" style="display: none;">
                <span class="pessoa-info-label"><i class="bx bx-certification"></i> Cursos</span>
                <span class="pessoa-info-value" id="viewCursos"></span>
            </div>
        </div>
    </div>

    <!-- Interesse Professor -->
    <div class="form-section" id="viewInteresseSection" style="display: none;">
        <h4 class="form-section-title"><i class="bx bx-chalkboard"></i> Interesse em ser Professor</h4>
        <div class="pessoa-info-grid">
            <div class="pessoa-info-item">
                <span class="pessoa-info-label">Resposta</span>
                <span class="pessoa-info-value" id="viewInteresse"></span>
            </div>
            <div class="pessoa-info-item">
                <span class="pessoa-info-label">Sempre frequentou a EBD?</span>
                <span class="pessoa-info-value" id="viewFrequenciaEbd"></span>
            </div>
            <div class="pessoa-info-item">
                <span class="pessoa-info-label">Curso de teologia?</span>
                <span class="pessoa-info-value" id="viewCursoTeo"></span>
            </div>
            <div class="pessoa-info-item">
                <span class="pessoa-info-label">Professor da EBD?</span>
                <span class="pessoa-info-value" id="viewProfEbd"></span>
            </div>
            <div class="pessoa-info-item">
                <span class="pessoa-info-label">Professor secular?</span>
                <span class="pessoa-info-value" id="viewProfComum"></span>
            </div>
            <div class="pessoa-info-item">
                <span class="pessoa-info-label">Público preferido</span>
                <span class="pessoa-info-value" id="viewPublico"></span>
            </div>
        </div>
    </div>

    <!-- Dados de Usuario -->
    <div class="form-section" id="viewUserSection" style="display: none;">
        <h4 class="form-section-title"><i class="bx bx-id-card"></i> Dados de Usuário</h4>
        <div class="pessoa-info-grid">
            <div class="pessoa-info-item">
                <span class="pessoa-info-label">Matrícula</span>
                <span class="pessoa-info-value" id="viewMatricula"></span>
            </div>
            <div class="pessoa-info-item">
                <span class="pessoa-info-label">Senha temporária</span>
                <span class="pessoa-info-value" id="viewSenhaTemp"></span>
            </div>
        </div>
    </div>
</div>
