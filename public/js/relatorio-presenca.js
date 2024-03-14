
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
                let dataJson = JSON.parse(data);
                let uniqueIds = getUniqueIds(dataJson);
                let dataIndex = getData(uniqueIds, dataJson);
                if (baixar == 1) {
                    baixarPDF(dataIndex);
                } else {
                    formatData(dataIndex);
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

function getUniqueIds(dataJson) {
    let uniqueIds = [];
    for(i = 0; i< dataJson.length; i++){
        if(uniqueIds.indexOf(dataJson[i].id) === -1){
            uniqueIds.push(dataJson[i].id);
        }
    }
    return uniqueIds;
}
function getData(uniqueIds, dataJson) {
    let dataFormated = [];
    for (let i = 0; i < uniqueIds.length; i++) {
        if(dataFormated.indexOf(uniqueIds[i].id) === -1){
            let presencasOfPerson = dataJson.filter((d) => d.id == uniqueIds[i]).map((d) => d.presenca);
            let pessoa = dataJson.find(({ id }) => id === uniqueIds[i]);
            let object = {
                id: pessoa.id,
                nome: pessoa.nome,
                data_nasc: pessoa.data_nasc,
                id_funcao: pessoa.id_funcao,
                presencas: presencasOfPerson,
            }
            dataFormated.push(object);
        }
    }

    return dataFormated;
}
function formatData(data) {
    let jsonData = JSON.stringify(data)
    $.ajax({
        url : $("#url-get-format-data").val(),
        type : 'POST',
        data : {
            _token: $('meta[name="csrf-token"]').attr('content'),
            data: jsonData,
        },
    })
        .done(function(dataFormated){
            console.log(dataFormated);
            $('#tbody-data').empty();
            $('#container-table').css("display", "block");
            $('#loader').css("display", "none");

            // periodo.append(`Período: ${initialDateSelected.value.split('-').reverse().join('/')} a ${finalDateSelected.value.split('-').reverse().join('/')}`)
            var rows;
            $(dataFormated).each(function(i, data) {
                rows += "<tr>"
                rows += "<td>" + data.nome + "</td>"
                rows += "<td>" + data.id_funcao + "</td>"
                rows += "<td>" + data.data_nasc + "</td>"
                rows += "<td>" + data.presencas + "</td>"
                rows += "</tr>"
            })
            $('#tbody-data').append(rows);

        })
        .fail(function(jqXHR, textStatus, msg){
            alert(msg);
        });
}

function baixarPDF(data) {
    let jsonData = JSON.stringify(data)
    $.ajax({
        url: $("#url-get-format-data").val(),
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            data: jsonData,
        },
    })
        .done(function (dataFormated) {
            console.log(dataFormated);
            var rows;
            $('#hidden-tbody-data').empty();
            $(dataFormated).each(function (i, data) {
                rows += "<tr>"
                rows += "<td>" + data.nome + "</td>"
                rows += "<td>" + data.id_funcao + "</td>"
                rows += "<td>" + data.data_nasc + "</td>"
                rows += "<td>" + data.presencas + "</td>"
                rows += "</tr>"
            })
            $('#hidden-tbody-data').append(rows);

            var doc = new jsPDF("p", "mm", "a4");
            doc.text(`Período: ${$('#initial_date').val().split('-').reverse().join('/')} a ${$('#final_date').val().split('-').reverse().join('/')}`, 10, 10)
            doc.autoTable({html: '#hidden-table'})
            doc.save("relatorio-frequencia.pdf");


        })
        .fail(function (jqXHR, textStatus, msg) {
            alert(msg);
        });
}
