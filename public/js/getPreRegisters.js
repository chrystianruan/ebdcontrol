function getPreRegisterList(params = {}) {
    const { congregacao_id, sala = null, nome = null, page = 1 } = params;

    const queryParams = new URLSearchParams();
    queryParams.append('congregacao_id', congregacao_id);
    if (sala) queryParams.append('sala', sala);
    if (nome) queryParams.append('nome', nome);
    queryParams.append('page', page);

    $.ajax({
        url: `/api/pre-cadastro?${queryParams.toString()}`,
        type: 'GET',
        dataType: 'json',
        headers: {
            'Accept': 'application/json',
        },
        success: function(response) {
            console.log('Pessoas:', response.pessoas);
            renderList(response.pessoas);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
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
        const birthday = pessoa.data_nasc ? formatDate(pessoa.data_nasc) : '-';
        const phoneHtml = pessoa.telefone
            ? `<a href="https://api.whatsapp.com/send?phone=55${pessoa.telefone}" target="_blank" class="phone-link" title="Chamar no WhatsApp">
                <i class='bx bxl-whatsapp'></i>
                ${formatPhoneNumber(pessoa.telefone)}
               </a>`
            : '<span class="text-muted"> - </span>';

        let classesHtml = '';
        if (pessoa.salas && pessoa.salas.length > 0) {
            pessoa.salas.forEach((sala, index) => {
                const funcao = pessoa.funcoes && pessoa.funcoes[index] ? pessoa.funcoes[index].nome : '';
                classesHtml += `
                    <div class="class-item-badge class-badge-professor">
                        <span class="class-name">${sala.nome}</span>
                        <span class="class-type professor">(${funcao})</span>
                    </div>
                `;
            });
        }

        tableRows += `
            <tr ${disabledClass}>
                <td><strong>${pessoa.nome}</strong></td>
                <td class="container-hideable">${birthday}</td>
                <td>${phoneHtml}</td>
                <td class="classes-cell container-hideable">
                    <div class="classes-list">${classesHtml}</div>
                </td>
                <td>
                    <div class="table-actions">
                        <button class="action-btn action-btn-view" title="Visualizar" data-id="${pessoa.id}">
                            <i class="bx bx-show icon"></i>
                        </button>
                        <button class="action-btn action-btn-edit" title="Editar" data-id="${pessoa.id}">
                            <i class="bx bx-edit icon"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    container.innerHTML = `
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th class="container-hideable">Aniversário</th>
                        <th>N° de telefone</th>
                        <th class="container-hideable">Classe/Função</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>${tableRows}</tbody>
            </table>
        </div>
        ${renderPagination(paginatedData)}
    `;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    return `${day}/${month}`;
}

function formatPhoneNumber(phone) {
    const cleaned = phone.replace(/\D/g, '');
    if (cleaned.length === 11) {
        return `(${cleaned.slice(0, 2)}) ${cleaned.slice(2, 7)}-${cleaned.slice(7)}`;
    }
    return phone;
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
