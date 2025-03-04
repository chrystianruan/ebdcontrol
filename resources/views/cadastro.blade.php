<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastro Geral - {{ $congregacao->congregacao_nome | $congregacao->setor_nome}}</title>
    <link rel="icon" type="imagem/png" href="/img/logo_ebd.png" />
    <link rel="stylesheet" href="/css/cadastroClasse.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
</head>
<body>
@if(session('msg'))
    <p class="msg" id="msg">{{session('msg')}}</p>
@endif
@if(session('msg2'))
    <p class="msg2" id="msg2">{{session('msg2')}}</p>
@endif
@include('templates.cadastro-template')
<script
    src="https://code.jquery.com/jquery-3.6.0.js"
    integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous">
</script>
@stack('scripts-cadastro')
<script>
    @if(session('msg') || session('msg2'))
    function hideMsg() {
        let msg = document.getElementById("msg");
        msg.style = "display:none";
    }

    function hideMsg2() {
        let msg2 = document.getElementById("msg2");
        msg2.style = "display:none";
    }

    setTimeout(hideMsg, 4000);
    setTimeout(hideMsg2, 3000);
    @endif
</script>
</body>
</html>


