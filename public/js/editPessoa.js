$('#btn-adicionar-classe').click(function(e) {
    var hash = Math.floor(Date.now() * Math.random()).toString(36);
    var string = `<tr id="${hash}-tr"> <td><button class="btn-tr-tbody-delete-classe" type="button" id="${hash}"><i class="bx bx-trash" style="font-size: 1.6em"> </i></button></td><td><select></select></td><td><select></select></td></tr>`;
    $('#tbody-add-classe').append(string);
});

$(document).on("click", ".btn-tr-tbody-delete-classe", function() {
    var check = confirm("Tem certeza que deseja excluir essa classe? ");

    if(check) {
        $("#"+this.id+"-tr").remove();
    }

});

