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
    $('#list-salas').val(JSON.stringify(classes));
});

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
    $('#list-salas').val(JSON.stringify(classes));
});

$(document).on("click", ".btn-tr-tbody-delete-classe", function() {
    var check = confirm("Tem certeza que deseja excluir essa classe? ");
    if (check) {
        if (classes.length == 1 && this.id == classes[0].id) {
            alert("Deve existir pelo menos um registro de classe")
        } else {
            $("#tr-"+this.id).remove();
            let index = classes.findIndex(obj => obj.id == this.id);
            if (index != -1) {
                classes.splice(index, 1)
                $('#list-salas').val(JSON.stringify(classes));
            }
        }
        console.log(classes)
    }
});


function checkIdExists(id) {
    return classes.find(obj => obj.id == id);
}

