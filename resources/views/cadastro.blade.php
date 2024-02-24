<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastro Geral - {{ $congregacao->nome }}</title>
    <link rel="stylesheet" href="/css/cadastroClasse.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="icon" type="imagem/png" href="/img/logo_ebd.png" />
</head>
<body>
@include('templates.cadastro-template')
<script
    src="https://code.jquery.com/jquery-3.6.0.js"
    integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="/js/pessoas.js"></script>
<script>
    $("#interesse").change(function() {
        if (this.value == 1 || this.value == 3) {
            $('#registerp').show();
            $('.inputprof').attr('required','required');
        } else {
            $('#registerp').hide();
            $('.inputprof').removeAttr('required');
        }
    });

    $("#scales").change(function() {
        if (this.checked) {
            $('#nomeResp').show();
            $('#numeroResp').show();
            $('#numero_pessoa').hide();
            $('#nome_responsavel').attr('required','required');
            $('#telefone_responsavel').attr('required','required');
            $('#field').val("");
        } else {
            $('#nomeResp').hide();
            $('#numeroResp').hide();
            $('#numero_pessoa').show();
            $('#nome_responsavel').removeAttr('required');
            $('#telefone_responsavel').removeAttr('required');
        }
    });

    $(document).ready(function() {
        $("#field").keyup(function() {
            $("#field").val(this.value.match(/[0-9]*/));
        });
        $("#telefone_responsavel").keyup(function() {
            $("#telefone_responsavel").val(this.value.match(/[0-9]*/));
        });
    });
    @if(session('msg') || session('msg2'))
    function hideMsg() {
        let msg = document.getElementById("msg");
        msg.style = "display:none";
    }

    function hideMsg2() {
        let msg2 = document.getElementById("msg2");
        msg2.style = "display:none";
    }

    setTimeout(hideMsg, 2000);
    setTimeout(hideMsg2, 3000);
    @endif
</script>
</body>
</html>


