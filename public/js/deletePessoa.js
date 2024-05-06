$('.btn-del-pessoa').click(function (){
   var check = confirm('Tem certeza que deseja apagar a pessoa? Essa operação não pode ser desfeita.')
    if (check) {
        let pessoaId = this.id.replace("btn-", "");
        console.log(pessoaId)
        $("#form-"+pessoaId).submit();
    }
});
