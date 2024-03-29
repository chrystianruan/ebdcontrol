<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastro Geral - {{ $congregacao->congregacao_nome | $congregacao->setor_nome}}</title>
    @stack('cadastro-pessoa-css')
    <link rel="icon" type="imagem/png" href="/img/logo_ebd.png" />
</head>
<body>
@if(session('msg'))
    <p class="msg" id="msg">{{session('msg')}}</p>
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


