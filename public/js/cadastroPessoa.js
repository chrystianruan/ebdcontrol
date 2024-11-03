$("#interesse").change(function() {
    if (this.value == 1 || this.value == 3) {
        $('#registerp').show();
        $('.inputprof').attr('required','required');
    } else {
        $('#registerp').hide();
        $('.inputprof').removeAttr('required');
    }
});
$("#scales").change(function() {
    if (this.checked) {
        $('#nomeResp').show();
        $('#numeroResp').show();
        $('#numero_pessoa').hide();
        $('#nome_responsavel').attr('required','required');
        $('#telefone_responsavel').attr('required','required');
        $('#field').val("");
    } else {
        $('#nomeResp').hide();
        $('#numeroResp').hide();
        $('#numero_pessoa').show();
        $('#nome_responsavel').removeAttr('required');
        $('#telefone_responsavel').removeAttr('required');
    }
});

$(document).ready(function() {
    $("#field").keyup(function() {
        $("#field").val(this.value.match(/[0-9]*/));
    });
    $("#telefone_responsavel").keyup(function() {
        $("#telefone_responsavel").val(this.value.match(/[0-9]*/));
    });
});


$('#btn-store').click(function () {
    $.ajax({
        url: $('#url-verify').val(),
        type: 'POST',
        data: {
            nome: $('#nome').val(),
            congregacao: $('#congregacao').val(),
            _token: $('meta[name="csrf-token"]').attr('content'),
        }
    }).done(function(data){
        $('#form-store').submit();
    }).fail(function(jqXHR, textStatus, msg){
        if(jqXHR.status === 403) {
            var check = confirm("[VALIDAÇÃO] Provavelmente está pessoa já está cadastrada no sistema. Tem certeza que deseja cadastrar mesmo assim?")
            if (check) {
                $('#form-store').submit();
            }
        } else {
            alert(msg)
        }
    })
})

