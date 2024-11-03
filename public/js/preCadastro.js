$('.btn-approve').click(function (){
    var check = confirm('Tem certeza que deseja aprovar? Essa operação não pode ser desfeita.')
    if (check) {
        let objectId = this.id.replace("btn-approve-", "");
        $("#form-approve-"+objectId).submit();
    }
});

$('.btn-remove').click(function (){
    var check = confirm('Tem certeza que deseja remover? Essa operação não pode ser desfeita.')
    if (check) {
        let objectId = this.id.replace("btn-remove-", "");
        $("#form-remove-"+objectId).submit();
    }
});

