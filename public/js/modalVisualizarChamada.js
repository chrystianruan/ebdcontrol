$(document).ready(function () {
    var modalVisualizarChamada = document.getElementById('modalVisualizarChamada');
    if (!modalVisualizarChamada) return;

    var loadingEl = document.getElementById('view-chamada-loading');
    var contentEl = document.getElementById('view-chamada-content');
    var errorEl = document.getElementById('view-chamada-error');
    var tbodyEl = document.getElementById('view-chamada-tbody');
    var urlBase = document.getElementById('url-visualizar-chamada');

    // Abrir modal
    window.openModalVisualizarChamada = function (chamadaId) {
        modalVisualizarChamada.classList.add('active');
        loadChamadaData(chamadaId);
    };

    // Fechar modal
    window.closeModalVisualizarChamada = function () {
        modalVisualizarChamada.classList.remove('active');
        resetModal();
    };

    function resetModal() {
        contentEl.style.display = 'none';
        loadingEl.style.display = 'none';
        errorEl.style.display = 'none';
        errorEl.innerHTML = '';
        tbodyEl.innerHTML = '';
    }

    function loadChamadaData(chamadaId) {
        loadingEl.style.display = 'flex';
        contentEl.style.display = 'none';
        errorEl.style.display = 'none';

        var congregacaoId = document.getElementById('congregacao-input').value;

        $.ajax({
            url: urlBase.value + '/' + chamadaId,
            type: 'GET',
            dataType: 'json',
            data: {
                congregacao_id: congregacaoId
            },
            success: function (data) {
                var chamada = data.chamada;
                var presencas = data.presencas;

                // Preencher header
                $('#view-chamada-classe').text(chamada.classe);
                $('#view-chamada-data').text(chamada.data);

                // Preencher stats
                $('#view-chamada-matriculados').text(chamada.matriculados);
                $('#view-chamada-presentes').text(chamada.presentes);
                $('#view-chamada-visitantes').text(chamada.visitantes);
                $('#view-chamada-assist-total').text(chamada.assist_total);
                $('#view-chamada-biblias').text(chamada.biblias);
                $('#view-chamada-revistas').text(chamada.revistas);

                // Preencher percentuais
                var fmtPerc = function(val) {
                    return String(Number(val).toFixed(1)).replace('.', ',') + '%';
                };
                $('#view-chamada-perc-presentes').text(fmtPerc(chamada.perc_presentes));
                $('#view-chamada-perc-biblias').text(fmtPerc(chamada.perc_biblias));
                $('#view-chamada-perc-revistas').text(fmtPerc(chamada.perc_revistas));

                // Observações
                if (chamada.observacoes) {
                    $('#view-chamada-obs').text(chamada.observacoes);
                    $('#view-chamada-obs-section').show();
                } else {
                    $('#view-chamada-obs-section').hide();
                }

                // Preencher tabela
                tbodyEl.innerHTML = '';
                $.each(presencas, function (index, p) {
                    var row = document.createElement('tr');

                    // Nome
                    var tdNome = document.createElement('td');
                    tdNome.innerHTML = '<strong>' + escapeHtml(p.nome) + '</strong>';
                    row.appendChild(tdNome);

                    // Aniversário
                    var tdAniv = document.createElement('td');
                    tdAniv.className = 'container-hideable';
                    tdAniv.textContent = p.aniversario;
                    row.appendChild(tdAniv);

                    // Função
                    var tdFuncao = document.createElement('td');
                    var funcaoClass = p.funcao === 'Professor' || p.funcao === 'Auxiliar de Professor' ? 'funcao-badge professor' : 'funcao-badge';
                    tdFuncao.innerHTML = '<span class="' + funcaoClass + '">' + escapeHtml(p.funcao) + '</span>';
                    row.appendChild(tdFuncao);

                    // Presença
                    var tdPresenca = document.createElement('td');
                    tdPresenca.style.textAlign = 'center';
                    if (p.presente == 1) {
                        tdPresenca.innerHTML = '<span class="presenca-badge presente"><i class="bx bx-check"></i></span>';
                    } else {
                        tdPresenca.innerHTML = '<span class="presenca-badge ausente"><i class="bx bx-x"></i></span>';
                    }
                    row.appendChild(tdPresenca);

                    tbodyEl.appendChild(row);
                });

                loadingEl.style.display = 'none';
                contentEl.style.display = 'block';
            },
            error: function () {
                loadingEl.style.display = 'none';
                errorEl.innerHTML = '<p>Erro ao carregar os dados da chamada. Tente novamente.</p>';
                errorEl.style.display = 'block';
            }
        });
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }
});


