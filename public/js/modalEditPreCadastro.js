// ========== MODAL DE EDIÇÃO ==========

function openEditModal(pessoaId) {
    const modal = document.getElementById('modalEditPreCadastro');
    const loading = document.getElementById('modalEditLoading');
    const form = document.getElementById('formEditPreCadastro');

    modal.classList.add('active');
    loading.style.display = 'flex';
    form.style.display = 'none';

    // Carregar dados dos selects
    loadClassesForModal();
    loadEstadosForModal();
    loadFormacoesForModal();
    loadPublicosForModal();

    // Buscar dados da pessoa
    $.ajax({
        url: `/api/pre-cadastros/${pessoaId}`,
        type: 'GET',
        dataType: 'json',
        headers: {
            'Accept': 'application/json',
        },
        success: function(pessoa) {
            fillEditForm(pessoa);
            loading.style.display = 'none';
            form.style.display = 'block';
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar pessoa:', error);
            loading.innerHTML = `
                <div class="text-danger">
                    <i class="bx bx-error"></i>
                    <p>Erro ao carregar dados da pessoa</p>
                </div>
            `;
        }
    });
}

function closeEditModal() {
    const modal = document.getElementById('modalEditPreCadastro');
    modal.classList.remove('active');

    // Resetar campos do responsável
    document.getElementById('responsavelFields').style.display = 'none';
    document.getElementById('editNomeResponsavel').removeAttribute('required');
    document.getElementById('editTelefoneResponsavel').removeAttribute('required');

    // Resetar campos do professor
    document.getElementById('professorFields').style.display = 'none';
    document.getElementById('editFrequenciaEbd').removeAttribute('required');
    document.getElementById('editCursoTeo').removeAttribute('required');
    document.getElementById('editProfEbd').removeAttribute('required');
    document.getElementById('editProfComum').removeAttribute('required');
    document.getElementById('editPublico').removeAttribute('required');
}

function loadClassesForModal() {
    const selectClasse = document.getElementById('editClasse');

    $.ajax({
        url: `/api/salas?congregacao_id=${congregacaoId}`,
        type: 'GET',
        dataType: 'json',
        headers: {
            'Accept': 'application/json',
        },
        success: function(salas) {
            selectClasse.innerHTML = '<option value="">Selecionar</option>';

            salas.forEach(function(sala) {
                if (sala.id > 2) {
                    const option = document.createElement('option');
                    option.value = sala.id;
                    option.textContent = `${sala.nome} - ${sala.tipo}`;
                    selectClasse.appendChild(option);
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar classes:', error);
        }
    });
}

function loadEstadosForModal() {
    const selectEstado = document.getElementById('editEstado');

    $.ajax({
        url: `/api/estados`,
        type: 'GET',
        dataType: 'json',
        headers: {
            'Accept': 'application/json',
        },
        success: function(response) {
            selectEstado.innerHTML = '<option value="">Selecionar</option>';

            response.data.forEach(function(estado) {
                const option = document.createElement('option');
                option.value = estado.id;
                option.textContent = estado.nome;
                selectEstado.appendChild(option);
            });
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar estados:', error);
        }
    });
}

function loadFormacoesForModal() {
    const selectFormacao = document.getElementById('editFormacao');

    $.ajax({
        url: `/api/formacoes`,
        type: 'GET',
        dataType: 'json',
        headers: {
            'Accept': 'application/json',
        },
        success: function(response) {
            selectFormacao.innerHTML = '<option value="">Selecionar</option>';

            response.data.forEach(function(formacao) {
                const option = document.createElement('option');
                option.value = formacao.id;
                option.textContent = formacao.nome;
                selectFormacao.appendChild(option);
            });
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar formações:', error);
        }
    });
}

function loadPublicosForModal() {
    const selectPublico = document.getElementById('editPublico');

    $.ajax({
        url: `/api/publicos`,
        type: 'GET',
        dataType: 'json',
        headers: {
            'Accept': 'application/json',
        },
        success: function(response) {
            selectPublico.innerHTML = '<option value="">Selecionar</option>';

            response.data.forEach(function(publico) {
                const option = document.createElement('option');
                option.value = publico.id;
                option.textContent = publico.nome;
                selectPublico.appendChild(option);
            });
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar públicos:', error);
        }
    });
}

function toggleResponsavelFields() {
    const checkbox = document.getElementById('editMenorIdade');
    const responsavelFields = document.getElementById('responsavelFields');
    const nomeResponsavel = document.getElementById('editNomeResponsavel');
    const telefoneResponsavel = document.getElementById('editTelefoneResponsavel');

    if (checkbox.checked) {
        responsavelFields.style.display = 'block';
        nomeResponsavel.setAttribute('required', 'required');
        telefoneResponsavel.setAttribute('required', 'required');
    } else {
        responsavelFields.style.display = 'none';
        nomeResponsavel.removeAttribute('required');
        telefoneResponsavel.removeAttribute('required');
        nomeResponsavel.value = '';
        telefoneResponsavel.value = '';
    }
}

function toggleProfessorFields() {
    const checkbox = document.getElementById('editInteresseProfessor');
    const professorFields = document.getElementById('professorFields');
    const frequenciaEbd = document.getElementById('editFrequenciaEbd');
    const cursoTeo = document.getElementById('editCursoTeo');
    const profEbd = document.getElementById('editProfEbd');
    const profComum = document.getElementById('editProfComum');
    const publico = document.getElementById('editPublico');

    if (checkbox.checked) {
        professorFields.style.display = 'block';
        frequenciaEbd.setAttribute('required', 'required');
        cursoTeo.setAttribute('required', 'required');
        profEbd.setAttribute('required', 'required');
        profComum.setAttribute('required', 'required');
        publico.setAttribute('required', 'required');
    } else {
        professorFields.style.display = 'none';
        frequenciaEbd.removeAttribute('required');
        cursoTeo.removeAttribute('required');
        profEbd.removeAttribute('required');
        profComum.removeAttribute('required');
        publico.removeAttribute('required');
        frequenciaEbd.value = '';
        cursoTeo.value = '';
        profEbd.value = '';
        profComum.value = '';
        publico.value = '';
    }
}

function fillEditForm(pessoa) {
    document.getElementById('editPessoaId').value = pessoa.id;
    document.getElementById('editNome').value = pessoa.nome || '';
    document.getElementById('editDataNasc').value = pessoa.data_nasc ? pessoa.data_nasc.split('T')[0] : '';
    document.getElementById('editSexo').value = pessoa.sexo || '';
    document.getElementById('editTelefone').value = pessoa.telefone || '';
    document.getElementById('editClasse').value = pessoa.classe || '';
    document.getElementById('editCidade').value = pessoa.cidade || '';
    document.getElementById('editOcupacao').value = pessoa.ocupacao || '';

    document.getElementById('editMenorIdade').checked = pessoa.responsavel != null;
    document.getElementById('editNomeResponsavel').value = pessoa.responsavel || '';
    document.getElementById('editTelefoneResponsavel').value = pessoa.telefone_responsavel || '';
    document.getElementById('editPaternidadeMaternidade').checked = pessoa.paternidade_maternidade != null;
    document.getElementById('editEstado').value = pessoa.id_uf || '';
    document.getElementById('editFormacao').value = pessoa.id_formation || '';
    document.getElementById('editCurso').value = pessoa.curso || '';
    document.getElementById('editInteresseProfessor').checked = pessoa.interesse == 1;

    // Campos do professor
    document.getElementById('editFrequenciaEbd').value = pessoa.frequencia_ebd || '';
    document.getElementById('editCursoTeo').value = pessoa.curso_teo || '';
    document.getElementById('editProfEbd').value = pessoa.prof_ebd || '';
    document.getElementById('editProfComum').value = pessoa.prof_comum || '';
    document.getElementById('editPublico').value = pessoa.id_public || '';

    toggleResponsavelFields();
    toggleProfessorFields();
}

function savePreCadastro() {
    const pessoaId = document.getElementById('editPessoaId').value;
    const form = document.getElementById('formEditPreCadastro');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const menorIdade = document.getElementById('editMenorIdade').checked;
    const interesseProfessor = document.getElementById('editInteresseProfessor').checked;

    const data = {
        nome: document.getElementById('editNome').value,
        congregacao: document.getElementById('editCongregacao').value,
        data_nasc: document.getElementById('editDataNasc').value,
        sexo: parseInt(document.getElementById('editSexo').value) || null,
        telefone: document.getElementById('editTelefone').value,
        sala: parseInt(document.getElementById('editClasse').value) || null,
        cidade: document.getElementById('editCidade').value,
        ocupacao: document.getElementById('editOcupacao').value,
        responsavel: menorIdade ? document.getElementById('editNomeResponsavel').value : null,
        telefone_responsavel: menorIdade ? document.getElementById('editTelefoneResponsavel').value : null,
        filhos: document.getElementById('editPaternidadeMaternidade').checked ? 1 : 0,
        id_uf: parseInt(document.getElementById('editEstado').value) || null,
        id_formation: parseInt(document.getElementById('editFormacao').value) || null,
        cursos: document.getElementById('editCurso').value,
        interesse: interesseProfessor ? 1 : 0,
        frequencia_ebd: interesseProfessor ? parseInt(document.getElementById('editFrequenciaEbd').value) || null : null,
        curso_teo: interesseProfessor ? parseInt(document.getElementById('editCursoTeo').value) || null : null,
        prof_ebd: interesseProfessor ? parseInt(document.getElementById('editProfEbd').value) || null : null,
        prof_comum: interesseProfessor ? parseInt(document.getElementById('editProfComum').value) || null : null,
        id_public: interesseProfessor ? parseInt(document.getElementById('editPublico').value) || null : null,
    };

    const modal = document.getElementById('modalEditPreCadastro');
    const btnSave = modal.querySelector('#btnSaveEdit');
    btnSave.disabled = true;
    btnSave.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Salvando...';


    $.ajax({
        url: `/api/pre-cadastros/${pessoaId}`,
        type: 'PUT',
        dataType: 'json',
        contentType: 'application/json',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        data: JSON.stringify(data),
        success: function() {
            closeEditModal();
            getPreRegisterList();
            alert('Pré-cadastro atualizado com sucesso!');
        },
        error: function(xhr, status, error) {
            console.error('Erro ao salvar:', error, xhr.responseJSON);
            alert('Erro ao salvar as alterações. Tente novamente.');
        },
        complete: function() {
            btnSave.disabled = false;
            btnSave.innerHTML = 'Salvar';
        }
    });
}

// Fechar modal ao clicar fora dele
document.addEventListener('click', function(e) {
    const modal = document.getElementById('modalEditPreCadastro');
    if (e.target === modal) {
        closeEditModal();
    }
});

// Fechar modal com tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
    }
});
