/**
 * Modal de Visualizar Relatório — carrega dados via AJAX e renderiza no modal.
 */
(function () {
    'use strict';

    var modal = document.getElementById('modalVisualizarRelatorio');
    var modalBody = document.getElementById('relatorio-modal-body');
    var modalTitle = modal ? modal.querySelector('.modal-header h2') : null;

    if (!modal || !modalBody) return;

    function abrirModal() {
        modal.classList.add('active');
    }

    function fecharModal() {
        modal.classList.remove('active');
        if (modalTitle) {
            modalTitle.textContent = 'Visualizar Relatório';
        }
    }

    window.fecharModalRelatorio = fecharModal;

    // Fechar ao clicar fora do modal
    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            fecharModal();
        }
    });

    // Fechar com ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            fecharModal();
        }
    });

    // Escuta clicks nos botões de abrir modal
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-abrir-modal-relatorio');
        if (!btn) return;
        e.preventDefault();

        var date = btn.getAttribute('data-date');
        if (!date) return;

        carregarDadosModal(date);
    });

    function carregarDadosModal(date) {
        modalBody.innerHTML = renderLoader();
        abrirModal();

        var url = '/admin/api/relatorio-modal/' + date;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(function (res) {
                if (!res.ok) throw new Error('Erro na requisição');
                return res.json();
            })
            .then(function (dados) {
                if (dados.vazio || dados.erro) {
                    modalBody.innerHTML = renderErro(dados.mensagem || dados.erro || 'Nenhum dado encontrado.');
                    return;
                }
                if (modalTitle && dados.data) {
                    modalTitle.textContent = 'Relatório — ' + dados.data;
                }
                modalBody.innerHTML = renderConteudo(dados);
            })
            .catch(function () {
                modalBody.innerHTML = renderErro('Erro ao carregar os dados do relatório.');
            });
    }

    function renderLoader() {
        return '<div class="rel-modal-loader"><div class="loader"></div><p>Carregando relatório…</p></div>';
    }

    function renderErro(msg) {
        return '<div class="rel-modal-erro"><i class="bx bx-error-circle"></i><span>' + escHtml(msg) + '</span></div>';
    }

    function renderConteudo(d) {
        var html = '';

        // ===== Resumo Geral =====
        html += '<div class="rel-modal-resumo">';
        html += resumoItem(d.resumo.matriculados, 'Matriculados');
        html += resumoItem(d.resumo.presentes, 'Presentes');
        html += resumoItem('+' + d.resumo.visitantes, 'Visitantes');
        html += resumoItem(d.resumo.assist_total, 'Assist. Total');
        html += resumoItem(fmtPerc(d.resumo.perc_presenca) + '%', 'Presença');
        html += resumoItem(d.resumo.biblias, 'Bíblias');
        html += resumoItem(d.resumo.revistas, 'Revistas');
        html += resumoItem(fmtPerc(d.resumo.perc_biblias) + '%', '% Bíblias');
        html += resumoItem(fmtPerc(d.resumo.perc_revistas) + '%', '% Revistas');
        html += '</div>';

        // ===== Destaques & Piores (lado a lado) =====
        html += '<div class="rel-modal-dp-grid">';

        // Coluna Destaques
        html += '<div class="rel-modal-dp-col">';
        html += '<div class="rel-modal-dp-col-title destaque"><i class="bx bx-trophy"></i> Melhores Índices</div>';
        html += dpItem('presenca', 'bx-user-check', 'Presença', d.destaques.maior_presenca, true);
        html += dpItem('visitantes', 'bx-group', 'Visitantes', d.destaques.maior_visitantes, true, true);
        html += dpItem('biblias', 'bx-book-open', 'Bíblias', d.destaques.maior_biblias, true);
        html += dpItem('revistas', 'bx-news', 'Revistas', d.destaques.maior_revistas, true);
        html += '</div>';

        // Coluna Piores
        html += '<div class="rel-modal-dp-col">';
        html += '<div class="rel-modal-dp-col-title pior"><i class="bx bx-down-arrow-alt"></i> Piores Índices</div>';
        html += dpItem('presenca', 'bx-user-x', 'Presença', d.piores.pior_presenca, false);
        html += dpItem('visitantes', 'bx-group', 'Visitantes', d.piores.pior_visitantes, false, true);
        html += dpItem('biblias', 'bx-book-open', 'Bíblias', d.piores.pior_biblias, false);
        html += dpItem('revistas', 'bx-news', 'Revistas', d.piores.pior_revistas, false);
        html += '</div>';

        html += '</div>';

        // Destaque Geral
        if (d.destaques.destaque_geral) {
            html += '<div class="rel-modal-destaque-geral">';
            html += '<i class="bx bx-trophy"></i>';
            html += '<span>' + escHtml(d.destaques.destaque_geral.sala) + '</span>';
            html += '<em>Classe destaque — ' + d.destaques.destaque_geral.pontos + '/4 categorias</em>';
            html += '</div>';
        }

        // ===== Comparativo =====
        html += renderComparativo(d.comparativo);

        // ===== Tabela de Classes =====
        html += renderTabelaClasses(d.classes, d.resumo, d.classesFaltantes);

        return html;
    }

    function resumoItem(valor, label) {
        return '<div class="rel-modal-resumo-item">' +
            '<span class="rel-modal-resumo-valor">' + valor + '</span>' +
            '<span class="rel-modal-resumo-label">' + label + '</span>' +
            '</div>';
    }

    function dpItem(tipo, icon, label, data, isDestaque, isAbsoluto) {
        if (!data) return '';
        var cls = isDestaque ? 'destaque' : 'pior';
        var valStr = isAbsoluto ? data.valor : fmtPerc(data.valor) + '%';
        return '<div class="rel-modal-dp-item ' + cls + '">' +
            '<div class="rel-modal-dp-icon ' + tipo + ' ' + cls + '"><i class="bx ' + icon + '"></i></div>' +
            '<div class="rel-modal-dp-info">' +
            '<span class="rel-modal-dp-label">' + label + '</span>' +
            '<span class="rel-modal-dp-value">' + escHtml(data.sala) + ' <em>(' + valStr + ')</em></span>' +
            '</div></div>';
    }

    function renderComparativo(comp) {
        var html = '<div class="rel-modal-section">';
        html += '<div class="rel-modal-section-title comparativo-title"><i class="bx bx-git-compare"></i> Comparativo com relatório anterior</div>';

        if (!comp.tem_anterior) {
            html += '<div class="rel-modal-comp-no-prev">' +
                '<i class="bx bx-info-circle"></i>' +
                '<span>Este é o primeiro relatório registrado. Não há dados anteriores para comparação.</span>' +
                '</div>';
            html += '</div>';
            return html;
        }

        html += '<p class="rel-modal-comp-ref">Comparando com o relatório de <strong>' + escHtml(comp.data_anterior) + '</strong></p>';

        var c = comp.comparativo;

        // Grid de números
        html += '<div class="rel-modal-comp-grid">';
        html += compItem('Matriculados', c.matriculados);
        html += compItem('Presentes', c.presentes);
        html += compItem('Visitantes', c.visitantes);
        html += compItem('Assist. Total', c.assist_total);
        html += compItem('Bíblias', c.biblias);
        html += compItem('Revistas', c.revistas);
        html += '</div>';

        // Barras de percentuais
        html += '<div class="rel-modal-comp-bars">';
        html += compBar('Presença', c.perc_presenca);
        html += compBar('Bíblias', c.perc_biblias);
        html += compBar('Revistas', c.perc_revistas);
        html += '</div>';

        html += '</div>';
        return html;
    }

    function compItem(label, data) {
        var badgeCls = data.percentual > 0 ? 'up' : (data.percentual < 0 ? 'down' : 'neutral');
        var badgeIcon = data.percentual > 0 ? 'bx-up-arrow-alt' : (data.percentual < 0 ? 'bx-down-arrow-alt' : 'bx-minus');
        var sinal = data.percentual > 0 ? '+' : '';

        return '<div class="rel-modal-comp-item">' +
            '<span class="rel-modal-comp-item-label">' + label + '</span>' +
            '<div class="rel-modal-comp-item-values">' +
            '<span class="rel-modal-comp-item-current">' + data.atual + '</span>' +
            '<span class="rel-modal-comp-item-prev">ant. ' + data.anterior + '</span>' +
            '</div>' +
            '<span class="rel-modal-comp-badge ' + badgeCls + '">' +
            '<i class="bx ' + badgeIcon + '"></i>' +
            sinal + fmtPerc(data.percentual) + '%' +
            '</span>' +
            '</div>';
    }

    function compBar(label, data) {
        var maxVal = Math.max(data.atual, data.anterior, 1);
        var widthAtual = Math.round((data.atual / maxVal) * 100);
        var widthAnterior = Math.round((data.anterior / maxVal) * 100);

        var badgeCls = data.variacao > 0 ? 'up' : (data.variacao < 0 ? 'down' : 'neutral');
        var sinal = data.variacao > 0 ? '+' : '';

        return '<div class="rel-modal-comp-bar-row">' +
            '<div class="rel-modal-comp-bar-header">' +
            '<span class="rel-modal-comp-bar-label">' + label + '</span>' +
            '<span class="rel-modal-comp-bar-values">' +
            '<span class="current">' + fmtPerc(data.atual) + '%</span>' +
            ' <span style="color:#94a3b8">vs</span> ' +
            fmtPerc(data.anterior) + '% ' +
            '<span class="rel-modal-comp-badge ' + badgeCls + '" style="margin-left:4px">' +
            sinal + fmtPerc(data.variacao) + 'pp' +
            '</span>' +
            '</span>' +
            '</div>' +
            '<div class="rel-modal-comp-bar-track">' +
            '<div class="rel-modal-comp-bar-fill prev" style="width:' + widthAnterior + '%"></div>' +
            '<div class="rel-modal-comp-bar-fill current" style="width:' + widthAtual + '%;position:relative;z-index:1"></div>' +
            '</div>' +
            '</div>';
    }

    function renderTabelaClasses(classes, resumo, faltantes) {
        if ((!classes || classes.length === 0) && (!faltantes || faltantes.length === 0)) return '';

        var html = '<div class="rel-modal-section">';
        html += '<div class="rel-modal-section-title classes-title"><i class="bx bx-table"></i> Detalhamento por classe</div>';
        html += '<div class="rel-modal-table-wrapper">';
        html += '<table class="rel-modal-table">';
        html += '<thead><tr>' +
            '<th>Classe</th>' +
            '<th>Matr.</th>' +
            '<th>Pres.</th>' +
            '<th>Visit.</th>' +
            '<th>Assist.</th>' +
            '<th>Bíblias</th>' +
            '<th>Revistas</th>' +
            '<th>% Pres.</th>' +
            '</tr></thead>';

        html += '<tbody>';
        if (classes && classes.length > 0) {
            for (var i = 0; i < classes.length; i++) {
                var c = classes[i];
                html += '<tr>';
                html += '<td>' + escHtml(c.sala) + '</td>';
                html += '<td>' + c.matriculados + '</td>';
                html += '<td>' + c.presentes + '</td>';
                html += '<td>' + c.visitantes + '</td>';
                html += '<td>' + c.assist_total + '</td>';
                html += '<td>' + c.biblias + '</td>';
                html += '<td>' + c.revistas + '</td>';
                html += '<td>' + fmtPerc(c.perc_presenca) + '%</td>';
                html += '</tr>';
            }
        }
        if (faltantes && faltantes.length > 0) {
            for (var j = 0; j < faltantes.length; j++) {
                html += '<tr style="background:#fef2f2; color:#991b1b; font-style:italic">';
                html += '<td style="font-weight:600">' + escHtml(faltantes[j].nome) + '</td>';
                html += '<td colspan="7" style="text-align:center">Chamada não enviada</td>';
                html += '</tr>';
            }
        }
        html += '</tbody>';

        html += '<tfoot><tr>';
        html += '<td>Total</td>';
        html += '<td>' + resumo.matriculados + '</td>';
        html += '<td>' + resumo.presentes + '</td>';
        html += '<td>' + resumo.visitantes + '</td>';
        html += '<td>' + resumo.assist_total + '</td>';
        html += '<td>' + resumo.biblias + '</td>';
        html += '<td>' + resumo.revistas + '</td>';
        html += '<td>' + fmtPerc(resumo.perc_presenca) + '%</td>';
        html += '</tr></tfoot>';

        html += '</table></div></div>';

        return html;
    }

    // ===== Helpers =====
    function fmtPerc(val) {
        if (val === null || val === undefined) return '0,0';
        return String(Number(val).toFixed(1)).replace('.', ',');
    }

    function escHtml(str) {
        if (!str) return '';
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

})();




