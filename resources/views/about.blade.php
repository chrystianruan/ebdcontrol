
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

        <div class="main">
            Desenvolvido por Chrystian Ruan
            <div class="links">
                <a href="https://www.linkedin.com/in/chrystianruan/"> <i class='bx bxl-linkedin-square'></i> </a>
                <a href="https://github.com/chrystianruan/"> <i class='bx bxl-github'></i> </a>
            </div>
        </div>

        @if(auth()->user()->id_nivel == 1)
            <a href="/master"><button>Voltar</button></a>
        @endif

        @if(auth()->user()->id_nivel == 2)
            <a href="/admin"><button>Voltar</button></a>
        @endif

        @if(auth()->user()->id_nivel != 1 && auth()->user()->id_nivel != 2)
            <a href="/classe"><button>Voltar</button></a>
        @endif

    </div>
</body>
</html>
