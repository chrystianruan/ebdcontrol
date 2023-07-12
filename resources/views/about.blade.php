
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/css/about.css">
</head>
<body>
    <div class="container">

        <div class="main">
            Desenvolvido por Chrystian Ruan
            <div class="links">

            </div>
        </div>


        @if(auth()->user()->id_nivel == 1)
            <h1>Sobre Master</h1>
            <a href="/master"><button>Voltar</button></a>
        @endif

        @if(auth()->user()->id_nivel == 2)
            <h1> Sobre Admin</h1>
            <a href="/admin"><button>Voltar</button></a>
        @endif

        @if(auth()->user()->id_nivel != 1 && auth()->user()->id_nivel != 2)
            <h1>Sobre Classe</h1>
            <a href="/classe"><button>Voltar</button></a>
        @endif

    </div>
</body>
</html>
