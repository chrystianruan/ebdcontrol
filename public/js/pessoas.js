$(document).ready(function () {
    const nomeInput = document.getElementById('nome');
    if (!nomeInput) return;

    const url = document.getElementById('url')?.value;
    const congregacaoId = document.getElementById('congregacao')?.value;

    if (!url) return;

    $.post(url, { congregacao_id: congregacaoId })
        .done(function (data) {
            const nomes = data.map(item => item.nome);

            new Awesomplete(nomeInput, {
                list: nomes,
                minChars: 1,
                maxItems: 10,
                autoFirst: false
            });
        });
});
