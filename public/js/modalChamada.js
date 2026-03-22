$(document).ready(function () {
    const modalRealizarChamada = document.getElementById('modalRealizarChamada');
    const selectSala = document.getElementById('modal-select-sala');
    const loadingEl = document.getElementById('modal-loading');
    const contentEl = document.getElementById('modal-chamada-content');
    const errorsEl = document.getElementById('modal-chamada-errors');
    const tbodyPessoas = document.getElementById('modal-tbody-pessoas');
    const pessoasInput = document.getElementById('modal-pessoas');
    const salaInput = document.getElementById('modal-sala');
    const presentesInput = document.getElementById('modal-presentes');
    const visitantesInput = document.getElementById('modal-visitantes');
    const assistTotalInput = document.getElementById('modal-assist-total');
    const matriculadosInput = document.getElementById('modal-matriculados');
    const spanNomeClasse = document.getElementById('modal-span-nome-classe');
    const buscarPessoasUrl = document.getElementById('buscar-pessoas-url');

    if (!modalRealizarChamada) return;

    // Abrir modal
    window.openModalRealizarChamada = function () {
        modalRealizarChamada.classList.add('active');
    };

    // Fechar modal
    window.closeModalRealizarChamada = function () {
        modalRealizarChamada.classList.remove('active');
        resetModalChamada();
    };

    function resetModalChamada() {
        if (selectSala) selectSala.selectedIndex = 0;
        if (contentEl) contentEl.style.display = 'none';
        if (loadingEl) loadingEl.style.display = 'none';
        if (errorsEl) { errorsEl.style.display = 'none'; errorsEl.innerHTML = ''; }
        if (tbodyPessoas) tbodyPessoas.innerHTML = '';
        if (pessoasInput) pessoasInput.value = '';
        if (salaInput) salaInput.value = '';
        if (presentesInput) presentesInput.value = '0';
        if (visitantesInput) visitantesInput.value = '0';
        if (assistTotalInput) assistTotalInput.value = '0';
        if (matriculadosInput) matriculadosInput.value = '';

        const btnSave = modalRealizarChamada.querySelector('#btnSaveEdit');
        if (btnSave) {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="bx bx-send" style="font-size: 1.1em;"></i> Enviar Chamada';
        }
    }

    // Trocar texto do botão para "Enviar Chamada"
    var btnSaveInit = modalRealizarChamada.querySelector('#btnSaveEdit');
    if (btnSaveInit) {
        btnSaveInit.innerHTML = '<i class="bx bx-send" style="font-size: 1.1em;"></i> Enviar Chamada';
    }

    // Se não há select de sala (sem classes faltantes ou não é dia de chamada), parar aqui
    if (!selectSala) return;

    // Ao selecionar classe, carregar pessoas via AJAX
    $(selectSala).on('change', function () {
        const salaId = this.value;
        if (!salaId) return;

        loadingEl.style.display = 'flex';
        contentEl.style.display = 'none';
        errorsEl.style.display = 'none';

        const url = buscarPessoasUrl.value + '/' + salaId;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                salaInput.value = salaId;
                spanNomeClasse.textContent = $('#modal-select-sala option:selected').text();
                tbodyPessoas.innerHTML = '';
                pessoasInput.value = JSON.stringify(data.pessoas);

                matriculadosInput.value = data.matriculados;
                presentesInput.value = data.presentes;
                assistTotalInput.value = data.assist_total;

                $.each(data.pessoas, function (index, item) {
                    var row = document.createElement('tr');

                    // Highlight professor
                    if (item.funcao_id === 2) {
                        row.classList.add('is-professor');
                    }

                    // Nome
                    var tdNome = document.createElement('td');
                    tdNome.innerHTML = '<strong>' + escapeHtml(item.pessoa_nome) + '</strong>';
                    row.appendChild(tdNome);

                    // Função
                    var tdFuncao = document.createElement('td');
                    var funcaoBadgeClass = item.funcao_id === 2 ? 'funcao-badge professor' : 'funcao-badge';
                    tdFuncao.innerHTML = '<span class="' + funcaoBadgeClass + '">' + escapeHtml(item.funcao_nome) + '</span>';
                    row.appendChild(tdFuncao);

                    // Presença
                    var tdPresenca = document.createElement('td');
                    tdPresenca.style.textAlign = 'center';

                    if (item.presenca) {
                        // Já tem presença marcada
                        if (item.dados_presenca && item.dados_presenca.sala_id == salaId) {
                            tdPresenca.innerHTML = '<span class="presenca-badge presente"><i class="bx bx-check"></i> Presente</span>';
                        } else {
                            tdPresenca.innerHTML = '<span class="presenca-badge presente-outra"><i class="bx bx-group"></i> Outra classe</span>';
                        }
                    } else {
                        var toggleId = 'modal-presenca-' + item.pessoa_id;
                        tdPresenca.innerHTML =
                            '<div class="presenca-toggle">' +
                            '  <input type="checkbox" id="' + toggleId + '" class="modal-presenca-checkbox" data-pessoa-id="' + item.pessoa_id + '">' +
                            '  <label for="' + toggleId + '" class="presenca-toggle-label"></label>' +
                            '</div>';
                    }

                    row.appendChild(tdPresenca);
                    tbodyPessoas.appendChild(row);
                });

                loadingEl.style.display = 'none';
                contentEl.style.display = 'block';
            },
            error: function () {
                loadingEl.style.display = 'none';
                errorsEl.innerHTML = '<p>Erro ao carregar os dados da classe. Tente novamente.</p>';
                errorsEl.style.display = 'block';
            }
        });
    });

    // Toggle de presença
    $(document).on('change', '.modal-presenca-checkbox', function () {
        var pessoaId = parseInt($(this).data('pessoa-id'));
        var isChecked = $(this).is(':checked');
        var pessoas = JSON.parse(pessoasInput.value);

        pessoas.forEach(function (pessoa) {
            if (parseInt(pessoa.pessoa_id) === pessoaId) {
                pessoa.presenca = isChecked ? 1 : 0;
            }
        });

        pessoasInput.value = JSON.stringify(pessoas);

        // Recalcular presentes
        var totalPresentes = parseInt(presentesInput.value);
        if (isChecked) {
            totalPresentes++;
        } else {
            totalPresentes--;
        }
        presentesInput.value = totalPresentes;
        assistTotalInput.value = totalPresentes + parseInt(visitantesInput.value || 0);
    });

    // Visitantes input
    $(document).on('input', '#modal-visitantes', function () {
        var visitantes = parseInt($(this).val()) || 0;
        var presentes = parseInt(presentesInput.value) || 0;
        assistTotalInput.value = presentes + visitantes;
    });

    // Submit da chamada
    window.submitRealizarChamada = function () {
        var form = document.getElementById('form-realizar-chamada');
        if (!form) return;

        var sala = salaInput.value;
        if (!sala) {
            errorsEl.innerHTML = '<p>Selecione uma classe antes de enviar.</p>';
            errorsEl.style.display = 'block';
            return;
        }

        var btnSave = modalRealizarChamada.querySelector('#btnSaveEdit');
        if (btnSave) {
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Enviando...';
        }

        form.submit();
    };

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }
});




