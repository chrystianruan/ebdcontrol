
let modalChamada = document.getElementById("modal-liberar-chamada")
let modalCadastro = document.getElementById("modal-liberar-cadastro");
let btnModalLiberarChamada = document.getElementById("btn-modal-liberar-chamada");
let btnModalLiberarCadastro = document.getElementById("btn-modal-liberar-cadastro");
let closeModalCadastro = document.getElementById("dialog-close-cadastro");
let closeModalChamada = document.getElementById("dialog-close-chamada");

function showModalLiberarChamada() {
    modalChamada.style.display = "block";
}

function showModalLiberarCadastro() {
    modalCadastro.style.display = "block";
}

function hideModalLiberarChamada() {
    modalChamada.style.display = "none";
}

function hideModalLiberarCadastro() {
    modalCadastro.style.display = "none";
}

btnModalLiberarChamada.addEventListener("click", showModalLiberarChamada)
btnModalLiberarCadastro.addEventListener("click", showModalLiberarCadastro)
closeModalChamada.addEventListener("click", hideModalLiberarChamada)
closeModalCadastro.addEventListener("click", hideModalLiberarCadastro)

$('#liberar').click(function () {
    $.ajax({
        url: $('#url').val(),
        type: 'POST',
        data: {
            congregacao: $('#congregacao').val(),
            date: $('#date').val(),
            _token: $('meta[name="csrf-token"]').attr('content'),
        }
    })
        .done(function(data){
            hideModalLiberarChamada();
            console.log(data.response);
        })
        .error(function(jqXHR, textStatus, msg){
            alert(msg);
        })
})

$('#liberar-link-cadastro').click(function () {
    $.ajax({
        url: $('#url-cadastro').val(),
        type: 'POST',
        data: {
            congregacao: $('#congregacao-cadastro').val(),
            _token: $('meta[name="csrf-token"]').attr('content'),
        }
    })
        .done(function(data){
            activeOrDesactiveLink();
            hideModalLiberarCadastro();
            returnToUser()

        })
        .error(function(jqXHR, textStatus, msg){
            alert("[403] Erro de permissão: "+msg.response);
        })
})

function activeOrDesactiveLink() {
    if ($('#link-ativo').val() == 1) {
        $('#link-ativo').val(0);
        $('#string-liberar-bloquear').text("Liberar");
        $('#liberar-link-cadastro').css("background-color", "green");
    } else {
        $('#link-ativo').val(1);
        $('#string-liberar-bloquear').text("Bloquear");
        $('#liberar-link-cadastro').css("background-color", "red");
    }
}

function verifyLinkAtivo() {
    if ($('#link-ativo').val() == 1) {
        $('#string-liberar-bloquear').text("Bloquear");
        $('#liberar-link-cadastro').css("background-color", "red");
    } else {
        $('#string-liberar-bloquear').text("Liberar");
        $('#liberar-link-cadastro').css("background-color", "green");
    }
}


$(document).ready(verifyLinkAtivo());


