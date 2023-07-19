let classeSelected = document.getElementById("select-classe");
let dataSelected = document.getElementById("date");
let linkVisualizarPdf = document.getElementById("a-visualizar-pdf")




function changeLinkToGeneratePDF() {
    linkVisualizarPdf.href = "/admin/visualizar/pdf-folha-frequencia/"+classeSelected.value+"/"+dataSelected.value;
}

classeSelected.addEventListener("change", changeLinkToGeneratePDF);
dataSelected.addEventListener("change", changeLinkToGeneratePDF);
