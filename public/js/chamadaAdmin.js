let divTablePessoas = document.getElementById("div-table-pessoas");
let divDadosInt = document.getElementById("div-dados-inteiros");
let selectSala = document.getElementById("select-sala")

selectSala.addEventListener("change", function () {
    var finalUrl = $('#buscar-pessoas').val() + "/" + selectSala.value;
    $.ajax({
        url: finalUrl,
        type: 'GET',
        dataType: 'json',
        success: data => {
            $('#sala').val(selectSala.value)
            $('#span-nome-classe').text($('#select-sala option:selected').text())
            divTablePessoas.style.display = "block"
            divDadosInt.style.display = "block"
            $('#tbody-table-pessoas').empty();
            $('#pessoas').val(JSON.stringify(data.pessoas))

            $('#matriculados').val(data.matriculados)
            $('#presentes').val(data.presentes)
            $('#assist_total').val(data.assist_total)

            $.each(data.pessoas, function(index, item) {
                if (item.presenca == 0) {
                    var styleTeacher = "";
                    if (item.funcao_id == 2) {
                        styleTeacher = "background-color: rgba(59,52,52,0.73)"
                    }
                    var row = $(`<tr style="${styleTeacher}">`);
                    var valueIdSelect = "presenca-"+item.pessoa_id;
                    row.append($('<td style="font-size: 1em">').text(item.pessoa_nome));
                    row.append($('<td style="font-size: 1em">').text(item.funcao_nome));

                    var select = $(`<select name="presencas[]" id="${valueIdSelect}" class="presencas">`);
                    select.append($('<option selected>').val(0).text('Não'));
                    select.append($('<option>').val(1).text('Sim'));
                    row.append($('<td style="font-size: 1em">').append(select));

                } else {
                    if (item.dados_presenca.sala_id == selectSala.value) {
                        var styleTeacher = "";
                        if (item.funcao_id == 2) {
                            styleTeacher = "background-color: rgba(59,52,52,0.73)"
                        }
                        var row = $(`<tr style="${styleTeacher}">`);
                        var valueIdSelect = "presenca-"+item.pessoa_id;
                        row.append($('<td style="font-size: 1em">').text(item.pessoa_nome));
                        row.append($('<td style="font-size: 1em">').text(item.funcao_nome));

                        var icon = $('<i>').addClass('fa fa-check');
                        row.append($('<td style="font-size: 1em">').append(icon));
                    }
                }


                $('#tbody-table-pessoas').append(row);
            });
        },
        error: data => {
            alert(data)
        }
    })
})

