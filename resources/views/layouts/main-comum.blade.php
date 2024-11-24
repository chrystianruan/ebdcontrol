<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EBDControl</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/css/navbar-comum.css">
</head>
<body id="body-pd">
    <header class="header" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
        <div class="header_img"> <img src="https://i.imgur.com/hczKIze.jpg" alt=""> </div>
    </header>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav_logo">
                    <span class="nav_logo-name">
                        <img src="/img/logo_ebd.png" class="nav_icon_logo" width="30">
                        <span class="nav_name">
                            Menu
                        </span>
                    </span>
                </a>
                <div class="nav_list">
                    <a href="/comum" class="nav_link @if ($view == 'dashboard') active @endif">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name">
                            Dashboard
                        </span>
                    </a>
                    <a href="/comum/marcar-presenca" class="nav_link @if ($view == 'marcar-presenca') active @endif">
                        <i class='bx bx-user-check nav_icon'></i>
                        <span class="nav_name">
                            Marcar Presença
                        </span>
                    </a>
                    <a href="/comum/minhas-presencas" class="nav_link @if ($view == 'minhas-presencas') active @endif">
                        <i class='bx bxs-user-detail nav_icon'></i>
                        <span class="nav_name">
                            Minhas Presenças
                        </span>
                    </a>
                    <a href="#" class="nav_link @if ($view == 'avaliacao') active @endif">
                        <i class='bx bxs-comment-detail nav_icon'></i>
                        <span class="nav_name">
                            Avaliação
                        </span>
                    </a>
                    <a href="#" class="nav_link @if ($view == 'rank') active @endif">
                        <i class='bx bx-bar-chart-alt-2 nav_icon'></i>
                        <span class="nav_name">
                            Rank
                        </span>
                    </a>
                    <a href="/comum/meus-dados" class="nav_link @if ($view == 'meus-dados') active @endif">
                        <i class='bx bx-user nav_icon'></i>
                        <span class="nav_name">
                            Meus Dados
                        </span>
                    </a>
                </div>
            </div>
            <a href="#" class="nav_link">
                <i class='bx bx-log-out nav_icon'></i>
                <span class="nav_name">
                    Sair
                </span>
            </a>
        </nav>
    </div>
    <!--Container Main start-->
    <div class="height-100 bg-light">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="/js/navbar-comum.js" ></script>
</body>
</html>

