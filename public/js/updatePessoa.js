let classes = JSON.parse($('#list-salas').val());

$(document).on("change", ".select-classe", function() {
    let idFormated = this.id.replace("select-classe-", "");
    if (checkIdExists(idFormated)) {
        let classe = classes.find(obj => obj.id == idFormated);
        classe.sala_id = parseInt(this.value);
    } else {
        let classe = {
            "id": idFormated,
            "sala_id": parseInt(this.value),
            "funcao_id": null
        }
        classes.push(classe)
    }
    console.log(classes)
})

$(document).on("change", ".select-funcao", function() {
    let idFormated = this.id.replace("select-funcao-", "");
    if (checkIdExists(idFormated)) {
        let classe = classes.find(obj => obj.id == idFormated);
        classe.funcao_id = parseInt(this.value);
    } else {
        let classe = {
            "id": idFormated,
            "sala_id": null,
            "funcao_id": parseInt(this.value)
        }
        classes.push(classe)
    }
    console.log(classes)
})


function checkIdExists(id) {
    return classes.find(obj => obj.id == id);
}

