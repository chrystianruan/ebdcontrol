
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
