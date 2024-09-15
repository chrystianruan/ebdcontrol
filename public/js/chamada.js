let presencas = document.querySelectorAll(".presencas");
let presente = document.getElementById("presentes");
let visitantes = document.getElementById("visitantes");
let assist_total = document.getElementById("assist_total");

$(document).on('change', '.presencas', function() {
        let pessoas = JSON.parse($('#pessoas').val())
        var presenca = $(this);
        if(presenca.val() == 1) {
            pessoas.forEach((function(pessoa) {
                if (parseInt(presenca.attr('id').replace('presenca-', '')) == parseInt(pessoa.pessoa_id)) {
                    pessoa.presenca = 1;
                }
            }));
            presenca.css({
                backgroundColor: 'green',
                color: 'white'
            })
            presente.value = ++presente.value;
            assist_total.value = ++assist_total.value;
        } else {
            pessoas.forEach((function(pessoa) {
                if (presenca.attr('id').replace('presenca-', '') == pessoa.pessoa_id) {
                    pessoa.presenca = 0;
                }
            }));
            presenca.css({
                backgroundColor: 'red',
                color: 'white'
            })
            presente.value = --presente.value;
            assist_total.value = --assist_total.value;
        }
        $('#pessoas').val(JSON.stringify(pessoas))
});


$('#visitantes').on('keyup', function() {

    soma = parseInt(visitantes.value) + parseInt(presente.value);
    assist_total.value = parseInt(soma);

});





