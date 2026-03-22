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


function savePessoa() {
    const modal = document.getElementById('modalRegister');
    const btnSave = modal.querySelector('#btnSaveEdit');
    const form = document.getElementById('form-store');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    btnSave.disabled = true;
    btnSave.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Salvando...';

    $.ajax({
        url: $('#url-verify').val(),
        type: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            nome: $('#nome').val(),
            congregacao: $('#congregacao').val(),
        }
    }).done(function(){
        submitFormPessoa(form, btnSave);
    }).fail(function(jqXHR, textStatus, msg){
        if(jqXHR.status === 403) {
            var check = confirm("[VALIDAÇÃO] Provavelmente está pessoa já está cadastrada no sistema. Tem certeza que deseja cadastrar mesmo assim?")
            if (check) {
                submitFormPessoa(form, btnSave);
                return;
            }
        } else {
            alert('Erro ao validar cadastro. Tente novamente.');
        }
        btnSave.disabled = false;
        btnSave.innerHTML = 'Salvar';
    });
}

function submitFormPessoa(form, btnSave) {
    const formData = new FormData(form);

    $.ajax({
        url: form.action,
        type: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function() {
            alert('Pessoa cadastrada com sucesso!');
            form.reset();
            closeModalRegister();
            location.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                var errors = xhr.responseJSON.errors;
                var errorMessages = [];
                for (var field in errors) {
                    errors[field].forEach(function(message) {
                        errorMessages.push(message);
                    });
                }
                alert('Erros de validação:\n\n' + errorMessages.join('\n'));
            } else {
                console.log(xhr.responseJSON)
                alert('Erro ao cadastrar pessoa. Tente novamente.');
            }
        },
        complete: function() {
            btnSave.disabled = false;
            btnSave.innerHTML = 'Salvar';
        }
    });
}

