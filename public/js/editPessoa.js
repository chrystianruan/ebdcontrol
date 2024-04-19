$('#btn-adicionar-classe').click(function() {
    var hash = Math.floor(Date.now() * Math.random()).toString(36);
    var string = `<tr id="tr-${hash}" class="tr-tbody-add-classe"> <td><button class="btn-tr-tbody-delete-classe" type="button" id="${hash}"><i class="bx bx-trash" style="font-size: 1.6em"> </i></button></td><td><select id="select-classe-${hash}" class="select-classe"></select></td><td><select id="select-funcao-${hash}" class="select-funcao"></select></td></tr>`;
    $('#tbody-add-classe').append(string);
    appendOptionsInSelectSala($('#congregacao').val(), "select-classe-"+hash)
    appendOptionsInSelectFuncao("select-funcao-"+hash)

});



function appendOptionsInSelectSala(congregacao, selectClasseId) {
    $.ajax({
        url: $('#url').val()+"/api/salas/congregacao/"+congregacao,
        type: 'GET',
        dataType: 'json',
        success: data => {
            var option;
            option += '<option selected value="" disabled>Selecionar</option>'
            $.each(data, function(i, obj){
                option += `<option value="${obj.id}">${obj.nome}</option>`;
            })
            $('#'+selectClasseId).append(option);
        },
        error: data => {
            alert(data)
        }
    });
}
function appendOptionsInSelectFuncao(selectFuncaoId) {
    $.ajax({
        url: $('#url').val()+"/api/funcaos",
        type: 'GET',
        dataType: 'json',
        success: data => {
            var option;
            option += '<option  selected value="" disabled>Selecionar</option>'
            $.each(data, function(i, obj){
                option += `<option value="${obj.id}">${obj.nome}</option>`;
            })
            $('#'+selectFuncaoId).append(option);
        },
        error: data => {
            alert(data)
        }
    });
}




