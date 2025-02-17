let btn = document.getElementById('btn-alterar')
let form = document.getElementById('form-alterar-senha');
let btnSave = document.getElementById('btn-save');

btn.addEventListener('click', function() {
    if (form.style.display === 'none') {
        form.style.display = 'block'
    } else {
        form.style.display = 'none'
    }
})

btnSave.addEventListener("click", function (event) {
    let senha = document.getElementById("senha");
    let confirmaSenha = document.getElementById("confirma-senha");
    let senhaError = document.getElementById("senha-error");

    if (senha.value !== confirmaSenha.value) {
        event.preventDefault();
        senhaError.style.display = "block";
        confirmaSenha.classList.add("is-invalid");
    } else {
        $('#form-alterar-senha').submit();
    }
});

function showOrHidePassword(senhaID, btnID) {
    let password = document.getElementById(senhaID)
    let btnLock = document.getElementById(btnID)

    btnLock.addEventListener("click", function() {
        if (password.type === "password") {
            password.type = "text";
            btnLock.className = "bx bx-lock-open-alt btn btn-outline-secondary";
        } else {
            password.type = "password";
            btnLock.className = "bx bx-lock-alt btn btn-outline-secondary"
        }
    } )
}

showOrHidePassword("senha", "btn-lock-senha");
showOrHidePassword("confirma-senha", "btn-lock-confirma");
