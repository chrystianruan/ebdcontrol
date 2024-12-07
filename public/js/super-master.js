

// let btnModal = document.getElementsByClassName("btn-modal");
// let closeModal = document.getElementsByClassName("dialog-close");
// let modalUser = document.getElementById("modal-user");
// let modalCongregacao = document.getElementById("modal-congregacao");
// let closeModalUs

// let modalUser = document.getElementById("modal-user")
let modalCongregacao = document.getElementById("modal-congregacao");
let btnModalUser = document.getElementById("btn-modal-user");
let btnModalCongregacao = document.getElementById("btn-modal-congregacao");
// let closeModalUser = document.getElementById("dialog-close-user");
let closeModalCongregacao = document.getElementById("dialog-close-congregacao");

// function showModalUser() {
//     modalUser.style.display = "block"
// }

function showModalCongregacao() {
    modalCongregacao.style.display = "block"
}

// function hideModalUser() {
//     modalUser.style.display = "none"
// }

function hideModalCongregacao() {
    modalCongregacao.style.display = "none"
}

// btnModalUser.addEventListener("click", showModalUser)
btnModalCongregacao.addEventListener("click", showModalCongregacao)
// closeModalUser.addEventListener("click", hideModalUser)
closeModalCongregacao.addEventListener("click", hideModalCongregacao)

// for (let i = 0; i <= btnModal.length; i++) {
//     btnModal[i].addEventListener("click", function () {
//         if (btnModal[i].id === "btn-modal-user") {
//             modalUser.style.display = "block";
//         } else {
//             modalCongregacao.style.display = "block"
//         }
//         console.log("id do btn open: "+btnModal[i].id)
//     });
// }
//
// for (let i = 0; i <= closeModal.length; i++) {
//     closeModal[i].addEventListener("click", function () {
//         console.log("fechar modal")
//         if (closeModal[i].id === "dialog-close-user") {
//             modalUser.style.display = "none";
//         } else {
//             modalCongregacao.style.display = "none";
//         }
//         console.log("id do btn close: "+closeModal[i].id)
//     });
// }
//
