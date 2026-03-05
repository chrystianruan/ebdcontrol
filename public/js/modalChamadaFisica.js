function openModalChamadaFisica() {
    document.getElementById('modalChamadaFisica').classList.add('active');
}

function closeModalChamadaFisica() {
    document.getElementById('modalChamadaFisica').classList.remove('active');

    // Reset form fields
    document.getElementById('select-classe-fisica').selectedIndex = 0;
    document.getElementById('date-chamada-fisica').value = '';
}

function gerarChamadaFisica() {
    var classe = document.getElementById('select-classe-fisica').value;
    var data = document.getElementById('date-chamada-fisica').value;

    if (!classe || !data) {
        alert('Selecione a classe e a data para gerar a chamada física.');
        return;
    }

    var url = '/admin/visualizar/pdf-folha-frequencia/' + classe + '/' + data;
    window.open(url, '_blank');
    closeModalChamadaFisica();
}

