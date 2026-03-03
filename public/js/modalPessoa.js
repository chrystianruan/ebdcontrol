// ========== MODAL DE VISUALIZAÇÃO DE PESSOA ==========

function openViewPessoaModal(pessoaId) {
    const modal = document.getElementById('modalViewPessoa');
    const loading = document.getElementById('viewPessoaLoading');
    const content = document.getElementById('viewPessoaContent');

    modal.classList.add('active');
    loading.style.display = 'flex';
    content.style.display = 'none';

    $.ajax({
        url: `/api/pessoa/${pessoaId}`,
        type: 'GET',
        dataType: 'json',
        headers: { 'Accept': 'application/json' },
        success: function(pessoa) {
            fillViewPessoa(pessoa);
            loading.style.display = 'none';
            content.style.display = 'block';
        },
        error: function() {
            loading.innerHTML = `
                <div style="text-align:center; color:#ef4444;">
                    <i class="bx bx-error" style="font-size:2.5rem;"></i>
                    <p style="margin-top:8px;">Erro ao carregar dados da pessoa</p>
                </div>
            `;
        }
    });
}

function closeViewPessoaModal() {
    const modal = document.getElementById('modalViewPessoa');
    modal.classList.remove('active');
    // Reset loading
    document.getElementById('viewPessoaLoading').style.display = 'flex';
    document.getElementById('viewPessoaLoading').innerHTML = `
        <div class="loading-spinner"></div>
        <p class="loading-text">Carregando...</p>
    `;
    document.getElementById('viewPessoaContent').style.display = 'none';
}

function fillViewPessoa(pessoa) {
    // Nome
    document.getElementById('viewNome').textContent = pessoa.nome;

    // Situação badge
    const badgeSituacao = document.getElementById('viewBadgeSituacao');
    badgeSituacao.textContent = pessoa.situacao == 1 ? 'Ativo' : 'Inativo';
    badgeSituacao.className = 'pessoa-badge ' + (pessoa.situacao == 1 ? 'pessoa-badge-success' : 'pessoa-badge-danger');

    // Sexo badge
    const badgeSexo = document.getElementById('viewBadgeSexo');
    badgeSexo.textContent = pessoa.sexo == 1 ? 'Masculino' : 'Feminino';
    badgeSexo.className = 'pessoa-badge ' + (pessoa.sexo == 1 ? 'pessoa-badge-blue' : 'pessoa-badge-pink');

    // Menor badge
    const badgeMenor = document.getElementById('viewBadgeMenor');
    badgeMenor.style.display = pessoa.responsavel ? 'inline-flex' : 'none';

    // Classes
    const classesContainer = document.getElementById('viewClasses');
    classesContainer.innerHTML = '';
    if (pessoa.salas && pessoa.salas.length > 0) {
        pessoa.salas.forEach(function(s) {
            classesContainer.innerHTML += `
                <div class="pessoa-class-chip">
                    <span class="pessoa-class-chip-name">${s.sala_nome}</span>
                    <span class="pessoa-class-chip-funcao">${s.funcao_nome}</span>
                </div>
            `;
        });
    }

    // Idade
    const nascDate = new Date(pessoa.data_nasc);
    const hoje = new Date();
    let idade = hoje.getFullYear() - nascDate.getFullYear();
    const m = hoje.getMonth() - nascDate.getMonth();
    if (m < 0 || (m === 0 && hoje.getDate() < nascDate.getDate())) idade--;
    document.getElementById('viewIdade').textContent = idade + (idade < 2 ? ' ano' : ' anos');

    // Nascimento
    document.getElementById('viewNascimento').textContent = nascDate.toLocaleDateString('pt-BR');

    // Endereço
    const cidade = pessoa.cidade || '';
    const uf = pessoa.nome_uf || '';
    document.getElementById('viewEndereco').textContent = cidade && uf ? cidade + ' / ' + uf : (cidade || uf || '—');

    // Telefone
    const tel = pessoa.telefone;
    if (tel) {
        const telFormatted = tel.length === 11
            ? `(${tel.substring(0,2)}) ${tel.substring(2,7)}-${tel.substring(7,11)}`
            : tel;
        document.getElementById('viewTelefone').innerHTML = `
            <a href="https://api.whatsapp.com/send?phone=55${tel}" target="_blank" class="phone-link" style="color:#7B4EA5;">
                <i class="bx bxl-whatsapp"></i> ${telFormatted}
            </a>`;
    } else {
        document.getElementById('viewTelefone').innerHTML = '<span style="color:#94a3b8;">Sem dados</span>';
    }

    // Ocupação
    document.getElementById('viewOcupacao').textContent = pessoa.ocupacao || '—';

    // Paternidade/Maternidade
    document.getElementById('viewPaternidade').textContent = pessoa.paternidade_maternidade || 'Não';

    // Responsável
    if (pessoa.responsavel) {
        document.getElementById('viewResponsavelSection').style.display = 'block';
        document.getElementById('viewResponsavelNome').textContent = pessoa.responsavel;
        document.getElementById('viewResponsavelTelefone').textContent = pessoa.telefone_responsavel || '—';
    } else {
        document.getElementById('viewResponsavelSection').style.display = 'none';
    }

    // Formação
    document.getElementById('viewFormacao').textContent = pessoa.nome_formation || '—';

    // Cursos
    if (pessoa.cursos && pessoa.cursos.trim()) {
        document.getElementById('viewCursosItem').style.display = 'block';
        document.getElementById('viewCursos').textContent = pessoa.cursos;
    } else {
        document.getElementById('viewCursosItem').style.display = 'none';
    }

    // Interesse professor
    if (pessoa.interesse == 1 || pessoa.interesse == 3) {
        document.getElementById('viewInteresseSection').style.display = 'block';
        document.getElementById('viewInteresse').textContent = pessoa.interesse == 1 ? 'Sim' : 'Talvez';

        const freqMap = { 1: 'Sim', 2: 'Não', 3: 'Mais ou menos' };
        const simNaoMap = { 1: 'Sim', 2: 'Não' };

        document.getElementById('viewFrequenciaEbd').textContent = freqMap[pessoa.frequencia_ebd] || '—';
        document.getElementById('viewCursoTeo').textContent = simNaoMap[pessoa.curso_teo] || '—';
        document.getElementById('viewProfEbd').textContent = simNaoMap[pessoa.prof_ebd] || '—';
        document.getElementById('viewProfComum').textContent = simNaoMap[pessoa.prof_comum] || '—';
        document.getElementById('viewPublico').textContent = pessoa.nome_publico || '—';
    } else {
        document.getElementById('viewInteresseSection').style.display = 'none';
    }

    // Dados de usuário
    if (pessoa.user && pessoa.user.matricula && pessoa.user.password_temp) {
        document.getElementById('viewUserSection').style.display = 'block';
        document.getElementById('viewMatricula').textContent = pessoa.user.matricula;
        document.getElementById('viewSenhaTemp').textContent = pessoa.user.password_temp;
    } else {
        document.getElementById('viewUserSection').style.display = 'none';
    }
}


// ========== MODAL DE EDIÇÃO DE PESSOA ==========

let editPessoaClasses = [];

function openEditPessoaModal(pessoaId) {
    const modal = document.getElementById('modalEditPessoa');
    const loading = document.getElementById('editPessoaLoading');
    const form = document.getElementById('formEditPessoa');

    modal.classList.add('active');
    loading.style.display = 'flex';
    form.style.display = 'none';

    // Carregar selects
    loadEditPessoaEstados();
    loadEditPessoaFormacoes();
    loadEditPessoaPublicos();

    // Buscar dados da pessoa
    $.ajax({
        url: `/api/pessoa/${pessoaId}`,
        type: 'GET',
        dataType: 'json',
        headers: { 'Accept': 'application/json' },
        success: function(pessoa) {
            fillEditPessoaForm(pessoa);
            loading.style.display = 'none';
            form.style.display = 'block';
        },
        error: function() {
            loading.innerHTML = `
                <div style="text-align:center; color:#ef4444;">
                    <i class="bx bx-error" style="font-size:2.5rem;"></i>
                    <p style="margin-top:8px;">Erro ao carregar dados da pessoa</p>
                </div>
            `;
        }
    });
}

function closeEditPessoaModal() {
    const modal = document.getElementById('modalEditPessoa');
    modal.classList.remove('active');
    // Reset
    document.getElementById('editPessoaLoading').style.display = 'flex';
    document.getElementById('editPessoaLoading').innerHTML = `
        <div class="loading-spinner"></div>
        <p class="loading-text">Carregando...</p>
    `;
    document.getElementById('formEditPessoa').style.display = 'none';
    document.getElementById('editPessoaResponsavelFields').style.display = 'none';
    document.getElementById('editPessoaProfessorFields').style.display = 'none';
    editPessoaClasses = [];

    // Remover loading de classes para evitar duplicação
    const classesLoading = document.getElementById('editPessoaClassesLoading');
    if (classesLoading) classesLoading.remove();

    const btnSave = modal.querySelector('#btnSaveEdit');
    if (btnSave) {
        btnSave.disabled = false;
        btnSave.innerHTML = 'Salvar';
    }
}

// ---- Carregar selects ----

function loadEditPessoaEstados() {
    const select = document.getElementById('editPessoaEstado');
    $.ajax({
        url: '/api/estados',
        type: 'GET',
        dataType: 'json',
        headers: { 'Accept': 'application/json' },
        success: function(response) {
            select.innerHTML = '<option value="" disabled>Selecionar</option>';
            response.data.forEach(function(estado) {
                const opt = document.createElement('option');
                opt.value = estado.id;
                opt.textContent = estado.nome;
                select.appendChild(opt);
            });
        }
    });
}

function loadEditPessoaFormacoes() {
    const select = document.getElementById('editPessoaFormacao');
    $.ajax({
        url: '/api/formacoes',
        type: 'GET',
        dataType: 'json',
        headers: { 'Accept': 'application/json' },
        success: function(response) {
            select.innerHTML = '<option value="" disabled>Selecionar</option>';
            response.data.forEach(function(f) {
                const opt = document.createElement('option');
                opt.value = f.id;
                opt.textContent = f.nome;
                select.appendChild(opt);
            });
        }
    });
}

function loadEditPessoaPublicos() {
    const select = document.getElementById('editPessoaPublico');
    $.ajax({
        url: '/api/publicos',
        type: 'GET',
        dataType: 'json',
        headers: { 'Accept': 'application/json' },
        success: function(response) {
            select.innerHTML = '<option value="" disabled selected>Selecionar</option>';
            response.data.forEach(function(p) {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.nome;
                select.appendChild(opt);
            });
        }
    });
}

// ---- Preencher formulário ----

function fillEditPessoaForm(pessoa) {
    document.getElementById('editPessoaId').value = pessoa.id;
    document.getElementById('editPessoaNome').value = pessoa.nome || '';
    document.getElementById('editPessoaDataNasc').value = pessoa.data_nasc ? pessoa.data_nasc.split('T')[0] : '';
    document.getElementById('editPessoaSexo').value = pessoa.sexo || '';
    document.getElementById('editPessoaSituacao').value = pessoa.situacao || '1';
    document.getElementById('editPessoaTelefone').value = pessoa.telefone || '';
    document.getElementById('editPessoaCidade').value = pessoa.cidade || '';
    document.getElementById('editPessoaOcupacao').value = pessoa.ocupacao || '';
    document.getElementById('editPessoaEstado').value = pessoa.id_uf || '';
    document.getElementById('editPessoaFormacao').value = pessoa.id_formation || '';
    document.getElementById('editPessoaCursos').value = pessoa.cursos || '';
    document.getElementById('editPessoaInteresse').value = pessoa.interesse != null ? pessoa.interesse : '';
    document.getElementById('editPessoaFilhos').value = pessoa.paternidade_maternidade ? '2' : '1';

    // Menor de idade
    document.getElementById('editPessoaMenorIdade').checked = pessoa.responsavel != null;
    document.getElementById('editPessoaNomeResponsavel').value = pessoa.responsavel || '';
    document.getElementById('editPessoaTelefoneResponsavel').value = pessoa.telefone_responsavel || '';
    toggleEditPessoaResponsavel();

    // Campos professor
    document.getElementById('editPessoaFrequenciaEbd').value = pessoa.frequencia_ebd || '';
    document.getElementById('editPessoaCursoTeo').value = pessoa.curso_teo || '';
    document.getElementById('editPessoaProfEbd').value = pessoa.prof_ebd || '';
    document.getElementById('editPessoaProfComum').value = pessoa.prof_comum || '';
    document.getElementById('editPessoaPublico').value = pessoa.id_public || '';
    toggleEditPessoaProfessor();

    // Classes
    editPessoaClasses = pessoa.salas.map(s => ({
        id: s.id,
        sala_id: s.sala_id,
        funcao_id: s.funcao_id
    }));
    document.getElementById('editPessoaListSalas').value = JSON.stringify(editPessoaClasses);
    renderEditPessoaClasses(pessoa.salas);
}

function renderEditPessoaClasses(salas) {
    const tbody = document.getElementById('editPessoaTbodyClasses');
    const wrapper = document.getElementById('editPessoaTbodyClasses').closest('.edit-pessoa-classes-table-wrapper');

    tbody.innerHTML = '';

    // Mostrar loading
    wrapper.style.position = 'relative';
    let loadingEl = document.getElementById('editPessoaClassesLoading');
    if (!loadingEl) {
        loadingEl = document.createElement('div');
        loadingEl.id = 'editPessoaClassesLoading';
        loadingEl.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <p class="loading-text">Carregando classes...</p>
            </div>
        `;
        wrapper.parentNode.insertBefore(loadingEl, wrapper);
    }
    const btnAddClasse = document.getElementById('editPessoaBtnAddClasse');
    loadingEl.style.display = 'block';
    wrapper.style.display = 'none';
    if (btnAddClasse) btnAddClasse.style.display = 'none';

    let pendingLoads = 0;

    salas.forEach(function(s) {
        pendingLoads += 2; // cada classe tem 2 selects para carregar
        addEditPessoaClasseRow(s.id, s.sala_id, s.funcao_id, function() {
            pendingLoads--;
            if (pendingLoads <= 0) {
                loadingEl.style.display = 'none';
                wrapper.style.display = '';
                if (btnAddClasse) btnAddClasse.style.display = '';
            }
        });
    });

    if (salas.length === 0) {
        loadingEl.style.display = 'none';
        wrapper.style.display = '';
        if (btnAddClasse) btnAddClasse.style.display = '';
    }
}

function addEditPessoaClasseRow(id, salaId, funcaoId, onLoadCallback) {
    const tbody = document.getElementById('editPessoaTbodyClasses');
    const tr = document.createElement('tr');
    tr.id = 'editPessoaTr-' + id;
    tr.className = 'tr-tbody-add-classe';
    tr.innerHTML = `
        <td>
            <button class="btn-tr-tbody-delete-classe" type="button" data-id="${id}" onclick="removeEditPessoaClasse('${id}')">
                <i class="bx bx-trash" style="font-size: 1.4em; color: #ef4444;"></i>
            </button>
        </td>
        <td>
            <select id="editPessoaSelectClasse-${id}" class="select editPessoaSelectClasse" data-id="${id}">
                <option value="" disabled selected>Selecionar</option>
            </select>
        </td>
        <td>
            <select id="editPessoaSelectFuncao-${id}" class="select editPessoaSelectFuncao" data-id="${id}">
                <option value="" disabled selected>Selecionar</option>
            </select>
        </td>
    `;
    tbody.appendChild(tr);

    // Preencher selects de classe e função
    loadEditPessoaSelectClasse('editPessoaSelectClasse-' + id, salaId, onLoadCallback);
    loadEditPessoaSelectFuncao('editPessoaSelectFuncao-' + id, funcaoId, onLoadCallback);
}

function loadEditPessoaSelectClasse(selectId, selectedValue, onLoadCallback) {
    const congregacaoId = document.getElementById('congregacao-input').value;
    $.ajax({
        url: `/api/salas?congregacao_id=${congregacaoId}`,
        type: 'GET',
        dataType: 'json',
        headers: { 'Accept': 'application/json' },
        success: function(salas) {
            const select = document.getElementById(selectId);
            select.innerHTML = '<option value="" disabled selected>Selecionar</option>';
            salas.forEach(function(sala) {
                if (sala.id > 2) {
                    const opt = document.createElement('option');
                    opt.value = sala.id;
                    opt.textContent = sala.nome;
                    if (selectedValue && sala.id == selectedValue) opt.selected = true;
                    select.appendChild(opt);
                }
            });
        },
        complete: function() {
            if (typeof onLoadCallback === 'function') onLoadCallback();
        }
    });
}

function loadEditPessoaSelectFuncao(selectId, selectedValue, onLoadCallback) {
    $.ajax({
        url: '/api/funcaos',
        type: 'GET',
        dataType: 'json',
        headers: { 'Accept': 'application/json' },
        success: function(funcoes) {
            const select = document.getElementById(selectId);
            select.innerHTML = '<option value="" disabled selected>Selecionar</option>';
            funcoes.forEach(function(f) {
                const opt = document.createElement('option');
                opt.value = f.id;
                opt.textContent = f.nome;
                if (selectedValue && f.id == selectedValue) opt.selected = true;
                select.appendChild(opt);
            });
        },
        complete: function() {
            if (typeof onLoadCallback === 'function') onLoadCallback();
        }
    });
}

function removeEditPessoaClasse(id) {
    if (editPessoaClasses.length <= 1) {
        alert('Deve existir pelo menos um registro de classe');
        return;
    }
    if (!confirm('Tem certeza que deseja excluir essa classe?')) return;

    document.getElementById('editPessoaTr-' + id).remove();
    const index = editPessoaClasses.findIndex(c => c.id == id);
    if (index !== -1) {
        editPessoaClasses.splice(index, 1);
        document.getElementById('editPessoaListSalas').value = JSON.stringify(editPessoaClasses);
    }
}

// ---- Botão adicionar classe ----
document.addEventListener('DOMContentLoaded', function() {
    const btnAdd = document.getElementById('editPessoaBtnAddClasse');
    if (btnAdd) {
        btnAdd.addEventListener('click', function() {
            const hash = Math.floor(Date.now() * Math.random()).toString(36);
            editPessoaClasses.push({ id: hash, sala_id: null, funcao_id: null });
            document.getElementById('editPessoaListSalas').value = JSON.stringify(editPessoaClasses);
            addEditPessoaClasseRow(hash, null, null);
        });
    }
});

// ---- Listeners mudança de classe/função ----
$(document).on('change', '.editPessoaSelectClasse', function() {
    const id = $(this).data('id');
    const classe = editPessoaClasses.find(c => c.id == id);
    if (classe) {
        classe.sala_id = parseInt(this.value);
    }
    document.getElementById('editPessoaListSalas').value = JSON.stringify(editPessoaClasses);
});

$(document).on('change', '.editPessoaSelectFuncao', function() {
    const id = $(this).data('id');
    const classe = editPessoaClasses.find(c => c.id == id);
    if (classe) {
        classe.funcao_id = parseInt(this.value);
    }
    document.getElementById('editPessoaListSalas').value = JSON.stringify(editPessoaClasses);
});

// ---- Toggles ----

function toggleEditPessoaResponsavel() {
    const checked = document.getElementById('editPessoaMenorIdade').checked;
    const fields = document.getElementById('editPessoaResponsavelFields');
    const nome = document.getElementById('editPessoaNomeResponsavel');
    const tel = document.getElementById('editPessoaTelefoneResponsavel');

    if (checked) {
        fields.style.display = 'block';
        nome.setAttribute('required', 'required');
        tel.setAttribute('required', 'required');
    } else {
        fields.style.display = 'none';
        nome.removeAttribute('required');
        tel.removeAttribute('required');
        nome.value = '';
        tel.value = '';
    }
}

function toggleEditPessoaProfessor() {
    const interesse = document.getElementById('editPessoaInteresse').value;
    const fields = document.getElementById('editPessoaProfessorFields');

    if (interesse == 1 || interesse == 3) {
        fields.style.display = 'block';
        document.querySelectorAll('.editPessoaInputProf').forEach(el => el.setAttribute('required', 'required'));
    } else {
        fields.style.display = 'none';
        document.querySelectorAll('.editPessoaInputProf').forEach(el => {
            el.removeAttribute('required');
            el.value = '';
        });
    }
}

// ---- Salvar ----

function saveEditPessoa() {
    const pessoaId = document.getElementById('editPessoaId').value;
    const form = document.getElementById('formEditPessoa');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const menorIdade = document.getElementById('editPessoaMenorIdade').checked;
    const interesse = parseInt(document.getElementById('editPessoaInteresse').value);
    const interesseProfessor = interesse == 1 || interesse == 3;

    const data = {
        _method: 'PUT',
        nome: document.getElementById('editPessoaNome').value,
        sexo: parseInt(document.getElementById('editPessoaSexo').value) || null,
        data_nasc: document.getElementById('editPessoaDataNasc').value,
        id_uf: parseInt(document.getElementById('editPessoaEstado').value) || null,
        telefone: document.getElementById('editPessoaTelefone').value || null,
        cidade: document.getElementById('editPessoaCidade').value,
        ocupacao: document.getElementById('editPessoaOcupacao').value || null,
        responsavel: menorIdade ? document.getElementById('editPessoaNomeResponsavel').value : null,
        telefone_responsavel: menorIdade ? document.getElementById('editPessoaTelefoneResponsavel').value : null,
        filhos: parseInt(document.getElementById('editPessoaFilhos').value) || 1,
        id_formation: parseInt(document.getElementById('editPessoaFormacao').value) || null,
        cursos: document.getElementById('editPessoaCursos').value || null,
        situacao: parseInt(document.getElementById('editPessoaSituacao').value) || 1,
        interesse: interesse,
        list_salas: document.getElementById('editPessoaListSalas').value,
        frequencia_ebd: interesseProfessor ? parseInt(document.getElementById('editPessoaFrequenciaEbd').value) || null : null,
        curso_teo: interesseProfessor ? parseInt(document.getElementById('editPessoaCursoTeo').value) || null : null,
        prof_ebd: interesseProfessor ? parseInt(document.getElementById('editPessoaProfEbd').value) || null : null,
        prof_comum: interesseProfessor ? parseInt(document.getElementById('editPessoaProfComum').value) || null : null,
        id_public: interesseProfessor ? parseInt(document.getElementById('editPessoaPublico').value) || null : null,
    };

    const modal = document.getElementById('modalEditPessoa');
    const btnSave = modal.querySelector('#btnSaveEdit');
    btnSave.disabled = true;
    btnSave.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Salvando...';

    $.ajax({
        url: `/admin/update/pessoa/${pessoaId}`,
        type: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        data: data,
        success: function() {
            closeEditPessoaModal();
            alert('Pessoa atualizada com sucesso!');

            const tableContainer = document.querySelector('.table-container');
            if (tableContainer) {
                tableContainer.innerHTML = `
                    <div class="loading-container">
                        <div class="loading-spinner"></div>
                        <p class="loading-text">Carregando...</p>
                    </div>
                `;
            }

            window.location.reload();
        },
        error: function(xhr) {
            console.error('Erro ao salvar:', xhr.responseJSON);
            let msg = 'Erro ao salvar as alterações. Tente novamente.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = Object.values(xhr.responseJSON.errors).flat();
                msg = errors.join('\n');
            }
            alert(msg);
        },
        complete: function() {
            btnSave.disabled = false;
            btnSave.innerHTML = 'Salvar';
        }
    });
}

// ---- Event listeners globais ----

// Fechar modais clicando fora
document.addEventListener('click', function(e) {
    if (e.target.id === 'modalViewPessoa') closeViewPessoaModal();
    if (e.target.id === 'modalEditPessoa') closeEditPessoaModal();
});

// Fechar com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const viewModal = document.getElementById('modalViewPessoa');
        const editModal = document.getElementById('modalEditPessoa');
        if (viewModal && viewModal.classList.contains('active')) closeViewPessoaModal();
        if (editModal && editModal.classList.contains('active')) closeEditPessoaModal();
    }
});
