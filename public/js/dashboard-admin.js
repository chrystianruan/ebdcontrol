let classeSelected = document.getElementById("select-classe");
let dataSelected = document.getElementById("date");
let linkVisualizarPdf = document.getElementById("a-visualizar-pdf")


classeSelected.addEventListener("change", function () {
    linkVisualizarPdf.href = "/admin/visualizar/pdf-folha-frequencia/"+classeSelected.value+"/"+dataSelected.value;
});

