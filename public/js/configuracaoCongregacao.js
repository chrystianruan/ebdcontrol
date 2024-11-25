function abrirModal() {
    if (document.getElementById("latitude_congregacao").value !== '' && document.getElementById("longitude_congregacao").value !== '') {
        document.getElementById("map").style.display = "none";
    }
    document.getElementById("modal-obter-localizacao").style.display = "block";
}

function fecharModal() {
    if (document.getElementById("latitude_congregacao").value !== '' && document.getElementById("longitude_congregacao").value !== '') {
        document.getElementById("map").style.display = "block";
    }
    document.getElementById("modal-obter-localizacao").style.display = "none";
}


document.getElementById("span-abrir-modal-local").addEventListener("click", abrirModal);
document.getElementById("dialog-close-obter-localizacao").addEventListener("click", fecharModal);
