const PROFESSOR_IDS = [2, 6, 7]; // PROFESSOR, PROFESSOR_SUBSTITUTO, AUXILIAR_SALA

let birthdayFiltersLoaded = false;

function initAniversariantes() {
    if (!birthdayFiltersLoaded) {
        loadClassesAniversariantes();
        loadFuncoesAniversariantes();
        birthdayFiltersLoaded = true;
    }
}

function loadClassesAniversariantes() {
    const congregacaoId = document.getElementById('congregacao-input').value;
    const select = document.getElementById('classeAniversariante');

    $.ajax({
        url: `/api/salas?congregacao_id=${congregacaoId}`,
        type: 'GET',
        dataType: 'json',
        headers: { 'Accept': 'application/json' },
        success: function (salas) {
            select.innerHTML = '<option selected disabled value="">Classe</option>';
            salas.forEach(function (sala) {
                if (sala.id > 2) {
                    const option = document.createElement('option');
                    option.value = sala.id;
                    option.textContent = `${sala.nome} - ${sala.tipo}`;
                    select.appendChild(option);
                }
            });
        },
        error: function (xhr, status, error) {
            console.error('Erro ao carregar classes:', error);
        }
    });
}

function loadFuncoesAniversariantes() {
    const select = document.getElementById('funcaoAniversariante');

    $.ajax({
        url: `/api/funcaos`,
        type: 'GET',
        dataType: 'json',
        headers: { 'Accept': 'application/json' },
        success: function (funcoes) {
            select.innerHTML = '<option selected disabled value="">Função</option>';
            funcoes.forEach(function (funcao) {
                const option = document.createElement('option');
                option.value = funcao.id;
                option.textContent = funcao.nome;
                select.appendChild(option);
            });
        },
        error: function (xhr, status, error) {
            console.error('Erro ao carregar funções:', error);
        }
    });
}

function getAniversariantes() {
    const congregacaoId = document.getElementById('congregacao-input').value;
    const mes = document.getElementById('mesAniversariante').value;
    const classe = document.getElementById('classeAniversariante').value;
    const funcao = document.getElementById('funcaoAniversariante').value;
    const orderBy = document.getElementById('orderByAniversariante').value;

    const queryParams = new URLSearchParams();
    queryParams.append('congregacao_id', congregacaoId);
    if (mes) queryParams.append('mes', mes);
    if (classe) queryParams.append('classe', classe);
    if (funcao) queryParams.append('funcao', funcao);
    if (orderBy) queryParams.append('orderBy', orderBy);

    showLoadingAniversariantes();

    $.ajax({
        url: `/api/aniversariantes?${queryParams.toString()}`,
        type: 'GET',
        dataType: 'json',
        headers: { 'Accept': 'application/json' },
        success: function (response) {
            renderAniversariantes(response);
        },
        error: function (xhr, status, error) {
            console.error('Erro ao buscar aniversariantes:', xhr.responseJSON);
            hideLoadingAniversariantes();
        }
    });
}

function showLoadingAniversariantes() {
    const container = document.getElementById('lista-aniversariantes');
    container.innerHTML = `
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <p class="loading-text">Carregando...</p>
        </div>
    `;
}

function hideLoadingAniversariantes() {
    const container = document.getElementById('lista-aniversariantes');
    container.innerHTML = '';
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    return `${day}/${month}`;
}

function isToday(dateString) {
    if (!dateString) return false;
    const date = new Date(dateString);
    const today = new Date();
    return date.getDate() === today.getDate() && date.getMonth() === today.getMonth();
}

function isProfessor(pessoa) {
    if (!pessoa.salas) return false;
    return pessoa.salas.some(sala => PROFESSOR_IDS.includes(sala.funcao_id));
}

function formatPhone(telefone) {
    if (!telefone) return null;
    const tel = String(telefone).replace(/\D/g, '');
    if (tel.length === 11) {
        return `(${tel.substring(0, 2)}) ${tel.substring(2, 7)}-${tel.substring(7, 11)}`;
    }
    return telefone;
}

function getAge(dateString) {
    if (!dateString) return 0;
    const birth = new Date(dateString);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
        age--;
    }
    return age;
}

function renderAniversariantes(pessoas) {
    const container = document.getElementById('lista-aniversariantes');

    if (!pessoas || pessoas.length === 0) {
        container.innerHTML = `
            <div class="table-header">
                <h3 class="table-title">Aniversariantes</h3>
                <span class="table-count">0 registros</span>
            </div>
            <div class="table-empty">
                <div class="table-empty-icon">
                    <i class="bx bx-cake"></i>
                </div>
                <div class="table-empty-text">Nenhum aniversariante encontrado</div>
                <div class="table-empty-subtext">Tente ajustar os filtros</div>
            </div>
        `;
        return;
    }

    let tableRows = '';
    pessoas.forEach(pessoa => {
        const professor = isProfessor(pessoa);
        const professorRowStyle = professor ? 'style="border-left: 4px solid #7B4EA5;"' : '';
        const professorBadge = professor
            ? '<span style="display:inline-block;background:#f3e8ff;color:#7B4EA5;font-size:0.65rem;font-weight:600;padding:2px 6px;border-radius:4px;margin-left:6px;vertical-align:middle;border:1px solid #e9d5ff;">PROFESSOR</span>'
            : '';
        const todayStyle = isToday(pessoa.data_nasc) ? 'style="color: #f0ad4e; font-weight: bolder"' : '';
        const age = getAge(pessoa.data_nasc);
        const isMinor = age < 18;
        const phoneNumber = isMinor ? pessoa.telefone_responsavel : pessoa.telefone;
        const phoneFormatted = formatPhone(phoneNumber);
        const phoneSuffix = isMinor && pessoa.telefone_responsavel ? ' (Responsável)' : '';

        let salasHtml = '';
        if (pessoa.salas && pessoa.salas.length > 0) {
            salasHtml = pessoa.salas.map(sala =>
                `<div class="class-item-badge class-badge-professor">
                    <span class="class-name">${sala.nome}</span>
                    <span class="class-type professor">(${sala.funcao || '-'})</span>
                </div>`
            ).join('');
        }

        tableRows += `
            <tr ${professorRowStyle}>
                <td><strong>${pessoa.nome}</strong>${professorBadge}</td>
                <td ${todayStyle}>${formatDate(pessoa.data_nasc)}</td>
                <td>
                    ${phoneNumber
                        ? `<a href="https://api.whatsapp.com/send?phone=55${phoneNumber}" target="_blank" class="phone-link" title="Chamar no WhatsApp">
                                <i class='bx bxl-whatsapp'></i> ${phoneFormatted}
                           </a>${phoneSuffix}`
                        : '<span class="text-muted">-</span>'
                    }
                </td>
                <td class="classes-cell container-hideable">
                    <div class="classes-list">${salasHtml}</div>
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
                        <th>Aniversário</th>
                        <th>N° de Telefone</th>
                        <th class="container-hideable">Classe/Função</th>
                    </tr>
                </thead>
                <tbody>${tableRows}</tbody>
            </table>
        </div>
    `;
}

function clearBirthdayFilters() {
    document.getElementById('mesAniversariante').selectedIndex = 0;
    document.getElementById('classeAniversariante').selectedIndex = 0;
    document.getElementById('funcaoAniversariante').selectedIndex = 0;
    document.getElementById('orderByAniversariante').selectedIndex = 0;
    getAniversariantes();
}
