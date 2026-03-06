
(function() {
    'use strict';

    var $classe = $('#classe');
    var $initialDate = $('#initial_date');
    var $finalDate = $('#final_date');
    var $containerTable = $('#container-table');
    var $loader = $('#loader');
    var $tbodyData = $('#tbody-data');
    var $hiddenTbody = $('#hidden-tbody-data');
    var $btnGerar = $('#gerar-relatorio');
    var $btnBaixar = $('#baixar-relatorio');
    var $feedbackArea = $('#presenca-feedback');
    var $emptyState = $('#presenca-empty-state');

    function showFeedback(message, type) {
        var iconMap = {
            error: 'bx-error-circle',
            warning: 'bx-info-circle',
            success: 'bx-check-circle',
            empty: 'bx-search-alt'
        };

        var html = '<div class="presenca-feedback-msg ' + type + '">' +
            '<i class="bx ' + (iconMap[type] || 'bx-info-circle') + '"></i>' +
            '<span>' + message + '</span>' +
            '</div>';

        $feedbackArea.html(html).show();
    }

    function hideFeedback() {
        $feedbackArea.hide().empty();
    }

    function setButtonLoading($btn, loading) {
        if (loading) {
            $btn.prop('disabled', true);
            $btn.data('original-html', $btn.html());
            $btn.html('<i class="bx bx-loader-alt bx-spin"></i> Aguarde...');
        } else {
            $btn.prop('disabled', false);
            var original = $btn.data('original-html');
            if (original) $btn.html(original);
        }
    }

    function clearHighlights() {
        $classe.css('border', '');
        $initialDate.css('border', '');
        $finalDate.css('border', '');
    }

    function highlightField($field) {
        $field.css('border', '2px solid #dc2626');
    }

    function validateFields() {
        clearHighlights();
        hideFeedback();
        var errors = [];

        if (!$classe.val()) {
            highlightField($classe);
            errors.push('Classe');
        }
        if (!$initialDate.val()) {
            highlightField($initialDate);
            errors.push('Data início');
        }
        if (!$finalDate.val()) {
            highlightField($finalDate);
            errors.push('Data fim');
        }

        if (errors.length > 0) {
            showFeedback('Preencha os campos obrigatórios: ' + errors.join(', ') + '.', 'warning');
            return false;
        }

        var initial = new Date($initialDate.val());
        var final_ = new Date($finalDate.val());

        if (initial >= final_) {
            highlightField($initialDate);
            highlightField($finalDate);
            showFeedback('A data inicial deve ser anterior à data final.', 'error');
            return false;
        }

        return true;
    }

    function generateDataToRelatorio(baixar) {
        if (!validateFields()) return;

        var $activeBtn = baixar ? $btnBaixar : $btnGerar;
        setButtonLoading($activeBtn, true);
        $containerTable.hide();
        $emptyState.hide();
        $loader.css('display', 'flex');
        hideFeedback();

        try {
            $.ajax({
                url: $('#url-get-chamadas').val(),
                type: 'POST',
                data: {
                    initialDate: $initialDate.val(),
                    finalDate: $finalDate.val(),
                    classeId: $classe.val(),
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                timeout: 30000
            })
            .done(function(data) {
                try {
                    if (!data || data === '[]' || data === 'null') {
                        $containerTable.hide();
                        showFeedback('Nenhum registro encontrado para o período selecionado.', 'empty');
                    } else {
                        if (baixar) {
                            baixarPDF(data);
                        } else {
                            formatData(data);
                        }
                    }
                } catch (e) {
                    showFeedback('Erro ao processar os dados recebidos. Tente novamente.', 'error');
                    console.error('Erro ao processar dados:', e);
                }
            })
            .fail(function(jqXHR, textStatus) {
                if (textStatus === 'timeout') {
                    showFeedback('A requisição demorou demais. Verifique sua conexão e tente novamente.', 'error');
                } else if (jqXHR.status === 403) {
                    showFeedback('Você não tem permissão para acessar esta classe.', 'error');
                } else if (jqXHR.status === 0) {
                    showFeedback('Sem conexão com o servidor. Verifique sua internet.', 'error');
                } else {
                    showFeedback('Ocorreu um erro ao gerar o relatório. (Código: ' + jqXHR.status + ')', 'error');
                }
            })
            .always(function() {
                $loader.css('display', 'none');
                setButtonLoading($activeBtn, false);
            });
        } catch (e) {
            $loader.css('display', 'none');
            setButtonLoading($activeBtn, false);
            showFeedback('Erro inesperado ao iniciar a requisição. Tente novamente.', 'error');
            console.error('Erro na requisição:', e);
        }
    }

    function formatData(brutalData) {
        var objectData = JSON.parse(brutalData);
        if (!objectData || objectData.length === 0) {
            showFeedback('Nenhum registro encontrado para o período selecionado.', 'empty');
            return;
        }

        var rows = '';
        $tbodyData.empty();

        $.each(objectData, function(i, data) {
            rows += '<tr>';
            rows += '<td>' + escapeHtml(data.pessoa_nome) + '</td>';
            rows += '<td>' + escapeHtml(data.funcao_nome) + '</td>';
            rows += '<td>' + escapeHtml(data.data_nascimento) + '</td>';
            rows += '<td>' + data.presencas + '</td>';
            rows += '</tr>';
        });

        $tbodyData.append(rows);
        $containerTable.css('display', 'block');
        hideFeedback();
    }

    function baixarPDF(brutalData) {
        try {
            var objectData = JSON.parse(brutalData);
            if (!objectData || objectData.length === 0) {
                showFeedback('Nenhum dado disponível para gerar o PDF.', 'empty');
                return;
            }

            var rows = '';
            $hiddenTbody.empty();

            $.each(objectData, function(i, data) {
                rows += '<tr>';
                rows += '<td>' + escapeHtml(data.pessoa_nome) + '</td>';
                rows += '<td>' + escapeHtml(data.funcao_nome) + '</td>';
                rows += '<td>' + escapeHtml(data.data_nascimento) + '</td>';
                rows += '<td>' + data.presencas + '</td>';
                rows += '</tr>';
            });

            $hiddenTbody.append(rows);

            var doc = new jsPDF('p', 'mm', 'a4');
            var periodo = $initialDate.val().split('-').reverse().join('/') +
                ' a ' +
                $finalDate.val().split('-').reverse().join('/');
            var classe = $classe.find('option:selected').text().trim();

            doc.setFontSize(14);
            doc.text('Relatório de Presença - ' + classe, 10, 15);
            doc.setFontSize(10);
            doc.text('Período: ' + periodo, 10, 22);
            doc.text('Total de alunos: ' + objectData.length, 10, 28);

            doc.autoTable({
                html: '#hidden-table',
                startY: 34,
                styles: { fontSize: 9 },
                headStyles: { fillColor: [123, 78, 165] }
            });

            doc.save('relatorio-presenca-' + classe.replace(/\s+/g, '-').toLowerCase() + '.pdf');
            showFeedback('PDF gerado com sucesso!', 'success');
        } catch (e) {
            showFeedback('Erro ao gerar o PDF. Tente novamente.', 'error');
            console.error('Erro ao gerar PDF:', e);
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    // === Event listeners ===
    $btnGerar.on('click', function() {
        generateDataToRelatorio(false);
    });

    $btnBaixar.on('click', function() {
        generateDataToRelatorio(true);
    });

    // Limpar destaques ao interagir com os campos
    $classe.add($initialDate).add($finalDate).on('change focus', function() {
        $(this).css('border', '');
    });

})();


