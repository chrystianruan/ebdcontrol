



let modalCongregacao = document.getElementById("modal-congregacao");
let btnModalCongregacao = document.getElementById("btn-modal-congregacao");
let closeModalCongregacao = document.getElementById("dialog-close-congregacao");

let modalCadastroClasse = document.getElementById("modal-cadastro-classe");
let btnModalCadastroClasse = document.getElementById("btn-modal-cadastro-classe");
let closeModalCadastroClasse = document.getElementById("dialog-close-cadastro-classe");

function showModalCadastroClasse() {
    modalCadastroClasse.style.display = "block"
}

function showModalCongregacao() {
    modalCongregacao.style.display = "block"
}

function hideModalCadastroClasse() {
    modalCadastroClasse.style.display = "none"
}

function hideModalCongregacao() {
    modalCongregacao.style.display = "none"
}

btnModalCongregacao.addEventListener("click", showModalCongregacao)
closeModalCongregacao.addEventListener("click", hideModalCongregacao)

btnModalCadastroClasse.addEventListener("click", showModalCadastroClasse)
closeModalCadastroClasse.addEventListener("click", hideModalCadastroClasse)

$('#select-setor').change(function () {
    let setorId = $('#select-setor').val();
    $.ajax({
        type: 'GET',
        url: $('#route-congregacoes-api').val()+"/"+setorId,
        dataType: 'json',
        success: dados => {
            var option;
            option += `<option selected disabled value="">Selecionar</option>`;
            if (dados.length > 0){
                $.each(dados, function(i, obj){
                    option += `<option value="${obj.id}">${obj.nome}</option>`;
                })
            }
            $('#select-congregacao').html(option).show();
        }
    })
});
