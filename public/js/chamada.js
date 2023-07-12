let pessoasValue = document.getElementById("pessoas");
let presencas = document.querySelectorAll(".presencas");
let presente = document.getElementById("presentes");
let visitantes = document.getElementById("visitantes");
let assist_total = document.getElementById("assist_total");
let pessoas = JSON.parse(pessoasValue.value);

for (let presenca of presencas) {
    presenca.addEventListener("change", function() {
            if(presenca.value == 1) {
                pessoas.forEach((function(pessoa) {
                    if (presenca.id.replace('presenca-', '') == pessoa.id) {
                        pessoa.presenca = 1;
                    }
                }));
                presenca.style.cssText = "background-color: green;" + "color: white;";
                presente.value = ++presente.value;
                assist_total.value = ++assist_total.value;
            } else {
                pessoas.forEach((function(pessoa) {
                    if (presenca.id.replace('presenca-', '') == pessoa.id) {
                        pessoa.presenca = 2;
                    }
                }));
                presenca.style.cssText = "background-color: red;" + "color: white;";
                presente.value = --presente.value;
                assist_total.value = --assist_total.value;
            }
            pessoasValue.value = JSON.stringify(pessoas);
    });
}

function confirmSend() {

}

visitantes.addEventListener("keyup", function() {

    soma = parseInt(visitantes.value) + parseInt(presente.value);
    assist_total.value = soma;

});





