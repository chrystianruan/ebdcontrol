
function returnMsg(msgPrint) {
    alert(msgPrint);
}

$('#gerar-relatorio').click(function() {
    generateDataToRelatorio(0);
});
$('#baixar-relatorio').click(function() {
    generateDataToRelatorio(1);
});

function generateDataToRelatorio(baixar) {
    if ($('#initial_date').val() && $('#final_date').val() && $('#classe').val()) {
        var initial = new Date($('#initial_date').val());
        var final = new Date($('#final_date').val());
        $('#container-table').css("display", "none");
        $('#loader').css("display", "block");

        if (initial < final) {
            $.ajax({
                url: $("#url-get-chamadas").val(),
                type: 'POST',
                data: {
                    initialDate: $('#initial_date').val(),
                    finalDate: $('#final_date').val(),
                    classeId: $('#classe').val(),
                    _token: $('meta[name="csrf-token"]').attr('content'),
        },
        })
        .done(function(data){
            if (data == "[]") {
                alert("Dados inexistentes para o período escolhido!")
            } else {
                if (baixar == 1) {
                    baixarPDF(data);
                } else {
                    formatData(data);
                }
            }
            })
                .fail(function(jqXHR, textStatus, msg){
                    alert(msg);
                });
        } else {
            returnMsg("A data inicial é maior que a final")
        }
    } else {
        returnMsg("Todos os campos devem ser preenchidos");
    }

}
function formatData(brutalData) {
    let objectData = JSON.parse(brutalData)
    var rows;
    $('#tbody-data').empty();
    $('#container-table').css("display", "block");
    $('#loader').css("display", "none");

    $(objectData).each(function(i, data) {
        rows += "<tr>"
        rows += "<td>" + data.pessoa_nome + "</td>"
        rows += "<td>" + data.funcao_nome + "</td>"
        rows += "<td>" + data.data_nascimento + "</td>"
        rows += "<td>" + data.presencas + "</td>"
        rows += "</tr>"
    })
    $('#tbody-data').append(rows);
}

function baixarPDF(brutalData) {
    let objectData = JSON.parse(brutalData)
            var rows;
            $('#hidden-tbody-data').empty();
            $(objectData).each(function (i, data) {
                rows += "<tr>"
                rows += "<td>" + data.pessoa_nome + "</td>"
                rows += "<td>" + data.funcao_nome + "</td>"
                rows += "<td>" + data.data_nascimento + "</td>"
                rows += "<td>" + data.presencas + "</td>"
                rows += "</tr>"
            })
            $('#hidden-tbody-data').append(rows);

            var doc = new jsPDF("p", "mm", "a4");
            doc.text(`Período: ${$('#initial_date').val().split('-').reverse().join('/')} a ${$('#final_date').val().split('-').reverse().join('/')}`, 10, 10)
            doc.autoTable({html: '#hidden-table'})
            doc.save("relatorio-frequencia.pdf");

}
