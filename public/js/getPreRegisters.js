
$(document).ready(function() {
    loadClasses();
    getPreRegisterList();
});

const congregacaoId = document.getElementById('congregacao-input').value;
const selectClasse = document.getElementById('classePessoaPreCadastro');

function loadClasses() {

    $.ajax({
        url: `/api/salas?congregacao_id=${congregacaoId}`,
        type: 'GET',
        dataType: 'json',
        headers: {
            'Accept': 'application/json',
        },
        success: function(salas) {
            selectClasse.innerHTML = '<option selected disabled value="">Classe</option>';

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
            console.error('Error loading classes:', error);
        }
    });
}
function getPreRegisterList(page = 1) {
    const nome = document.getElementById('nomePessoaPreCadastro').value;

    const queryParams = new URLSearchParams();
    queryParams.append('congregacao_id', congregacaoId);
    if (selectClasse.value) queryParams.append('classe_pre_register', selectClasse.value);
    if (nome) queryParams.append('nome_pre_register', nome);
    queryParams.append('page', page)

    showLoading();
    $.ajax({
        url: `/api/pre-cadastros?${queryParams.toString()}`,
        type: 'GET',
        dataType: 'json',
        headers: {
            'Accept': 'application/json',
        },
        success: function(response) {
            console.log('Pessoas:', response);
            renderList(response);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            hideLoading();
        }
    });
}

function showLoading() {
    const container = document.getElementById('lista-pessoas');
    container.innerHTML = `
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <p class="loading-text">Carregando...</p>
        </div>
    `;
}


function hideLoading() {
    const container = document.getElementById('lista-pessoas');
    container.innerHTML = '';
}

function renderList(paginatedData) {
    const container = document.getElementById('lista-pessoas');

    if (!paginatedData.data || paginatedData.data.length === 0) {
        container.innerHTML = `
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
        `;
        return;
    }

    let tableRows = '';
    paginatedData.data.forEach(pessoa => {
        const disabledClass = pessoa.situacao == 2 ? 'class="disabled"' : '';
        const duplicataClass = pessoa.duplicata ? 'row-duplicata' : '';
        const duplicataAlert = pessoa.duplicata
            ? `<span class="badge badge-warning" title="Possível duplicata encontrada no sistema">
                <i class='bx bx-error-circle'></i> Duplicata
               </span>`
            : '';

        tableRows += `
            <tr ${disabledClass ? disabledClass : `class="${duplicataClass}"`}>
                <td class="checkbox-cell">
                    <input type="checkbox" class="row-checkbox" data-id="${pessoa.id}">
                </td>
                <td>
                    <div class="nome-cell">
                        <strong>${pessoa.nome}</strong>
                        ${duplicataAlert}
                    </div>
                </td>
                <td class="classes-cell">
                    <div class="classes-list">${pessoa.sala.nome}</div>
                </td>
                <td>
                    <div class="table-actions">
                        <button class="action-btn action-btn-edit" title="Editar" data-id="${pessoa.id}">
                            <i class="bx bx-edit icon"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    container.innerHTML = `
        <div class="batch-actions" id="batchActions" style="display: none;">
            <span class="batch-info"><span id="selectedCount">0</span> selecionado(s)</span>
            <button class="btn btn-success btn-sm" onclick="batchApprove()" title="Aprovar selecionados">
                <i class="bx bx-check"></i> <span class="container-hideable">Aprovar</span>
            </button>
            <button class="btn btn-danger btn-sm" onclick="batchDelete()" title="Excluir selecionados">
                <i class="bx bx-trash"></i> <span class="container-hideable">Excluir</span>
            </button>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th class="checkbox-cell">
                            <input type="checkbox" id="selectAll" title="Selecionar todos">
                        </th>
                        <th>Nome</th>
                        <th>Classe</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>${tableRows}</tbody>
            </table>
        </div>
        ${renderPagination(paginatedData)}
    `;

    // Adicionar event listeners para os botões de paginação
    attachPaginationListeners();
}

function attachPaginationListeners() {
    const paginationButtons = document.querySelectorAll('.pagination-btn[data-page]');
    paginationButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const page = parseInt(this.getAttribute('data-page'));
            getPreRegisterList(page);
        });
    });

    // Event listeners para os botões de editar
    const modalPreRegisterEl = document.getElementById('modalPreRegister');
    const editButtons = modalPreRegisterEl.querySelectorAll('.action-btn-edit');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const pessoaId = this.getAttribute('data-id');
            openEditModal(pessoaId);
        });
    });


    // Event listener para o checkbox "Selecionar todos"
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBatchActions();
        });
    }

    // Event listeners para os checkboxes individuais
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBatchActions();
            updateSelectAllState();
        });
    });
}

function updateBatchActions() {
    const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
    const batchActions = document.getElementById('batchActions');
    const selectedCount = document.getElementById('selectedCount');

    if (selectedCheckboxes.length > 0) {
        batchActions.style.display = 'flex';
        selectedCount.textContent = selectedCheckboxes.length;
    } else {
        batchActions.style.display = 'none';
    }
}

function updateSelectAllState() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const allCheckboxes = document.querySelectorAll('.row-checkbox');
    const checkedCheckboxes = document.querySelectorAll('.row-checkbox:checked');

    if (checkedCheckboxes.length === 0) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    } else if (checkedCheckboxes.length === allCheckboxes.length) {
        selectAllCheckbox.checked = true;
        selectAllCheckbox.indeterminate = false;
    } else {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = true;
    }
}

function getSelectedIds() {
    const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
    return Array.from(selectedCheckboxes).map(checkbox => checkbox.getAttribute('data-id'));
}

function batchApprove() {
    const ids = getSelectedIds();
    if (ids.length === 0) return;

    if (!confirm(`Tem certeza que deseja aprovar ${ids.length} pré-cadastro(s)?`)) return;

    showLoading();

    $.ajax({
        url: '/api/pre-cadastros/approve',
        type: 'POST',
        contentType: 'application/json',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        data: JSON.stringify({ ids: ids }),
        success: function(response) {
            getPreRegisterList();
            alert(response.message || `${ids.length} pré-cadastro(s) aprovado(s) com sucesso!`);
            window.location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Erro ao aprovar em lote:', error);
            const errorMsg = xhr.responseJSON?.message || 'Erro ao aprovar os pré-cadastros. Tente novamente.';
            alert(errorMsg);
            getPreRegisterList();
        }
    });
}

function batchDelete() {
    const ids = getSelectedIds();
    if (ids.length === 0) return;

    if (!confirm(`Tem certeza que deseja excluir ${ids.length} pré-cadastro(s)? Esta ação não pode ser desfeita.`)) return;

    showLoading();

    $.ajax({
        url: '/api/pre-cadastros/destroy',
        type: 'DELETE',
        contentType: 'application/json',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        data: JSON.stringify({ ids: ids }),
        success: function(response) {
            getPreRegisterList();
            alert(response.message || `${ids.length} pré-cadastro(s) excluído(s) com sucesso!`);
            window.location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Erro ao excluir em lote:', error);
            const errorMsg = xhr.responseJSON?.message || 'Erro ao excluir os pré-cadastros. Tente novamente.';
            alert(errorMsg);
            getPreRegisterList();
        }
    });
}



function renderPagination(paginatedData) {
    const { current_page, last_page, total, per_page } = paginatedData;
    const showing = Math.min(current_page * per_page, total);

    let pagesHtml = '';
    const inicio = Math.max(1, current_page - 2);
    const fim = Math.min(last_page, current_page + 2);

    if (inicio > 1) {
        pagesHtml += `<li><a href="#" class="pagination-btn" data-page="1">1</a></li>`;
        if (inicio > 2) pagesHtml += `<li><span class="pagination-ellipsis">...</span></li>`;
    }

    for (let page = inicio; page <= fim; page++) {
        const activeClass = page === current_page ? 'active' : '';
        pagesHtml += `<li><a href="#" class="pagination-btn ${activeClass}" data-page="${page}">${page}</a></li>`;
    }

    if (fim < last_page) {
        if (fim < last_page - 1) pagesHtml += `<li><span class="pagination-ellipsis">...</span></li>`;
        pagesHtml += `<li><a href="#" class="pagination-btn" data-page="${last_page}">${last_page}</a></li>`;
    }

    const prevBtn = current_page > 1
        ? `<li><a href="#" class="pagination-btn pagination-btn-prev" data-page="${current_page - 1}"><i class="fas fa-chevron-left"></i><span>Anterior</span></a></li>`
        : '';

    const nextBtn = current_page < last_page
        ? `<li><a href="#" class="pagination-btn pagination-btn-next" data-page="${current_page + 1}"><span>Próximo</span><i class="fas fa-chevron-right"></i></a></li>`
        : '';

    return `
        <div class="pagination-container">
            <div class="pagination-info">
                <strong>${showing}</strong> de <strong>${total}</strong>
            </div>
            <ul class="pagination pagination-minimal">
                ${prevBtn}
                ${pagesHtml}
                ${nextBtn}
            </ul>
        </div>
    `;
}

