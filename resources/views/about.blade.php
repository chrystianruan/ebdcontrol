
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sobre - EBDControl</title>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/css/about.css">
</head>
<body>
    <div class="container">
        <div style="margin: 12% 0">
        <div class="main">
            Desenvolvido pela equipe da secretaria da EBD - Templo Sede/ADPAR
        </div>


        @if(auth()->user()->permissao_id == 2)
            <a href="/master"><button>Voltar</button></a>
        @endif

        @if(auth()->user()->permissao_id == 3)
            <a href="/admin"><button>Voltar</button></a>
        @endif

        @if(auth()->user()->permissao_id != 2 && auth()->user()->permissao_id != 3)
            <a href="/classe"><button>Voltar</button></a>
        @endif
        </div>
    </div>
</body>
</html>
