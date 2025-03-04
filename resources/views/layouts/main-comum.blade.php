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
        <div> <img src="/img/logo_ebd_extend.png" alt="" width="120"> </div>
        <div class="row">
            <div class="btn-group col">
                <button type="button" class="btn btn-secondary dropdown-toggle" style="width: 100px !important; max-width: 100% !important;" data-bs-toggle="dropdown" aria-expanded="false">
                    Usuário
                </button>
                <ul class="dropdown-menu">
                    @if(auth()->user()->permissao_id == 1)<li><a class="dropdown-item" href="/super-master">SuperMaster</a></li>@endif
                    @if(auth()->user()->permissao_id <= 2)<li><a class="dropdown-item" href="/master">Master</a></li>@endif
                    @if(auth()->user()->permissao_id <= 3)<li><a class="dropdown-item" href="/admin">Admin</a></li>@endif
                    @if(auth()->user()->permissao_id == 4)<li><a class="dropdown-item" href="/classe">Classe</a></li>@endif


                   @if(auth()->user()->permissao_id < 5) <li><hr class="dropdown-divider"></li> @endif
                    <li>
                        <form action="/logout" method="POST">
                            @csrf
                            <button class="dropdown-item btn-logout" style="border: none; font-size: 1em; background: none;cursor:pointer; color: red" type="submit">
                                <i class='bx bx-log-out nav_icon'></i>
                                <span class="nav_name">Sair</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            <div class="header_img col"> <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" alt=""> </div>
        </div>

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
                    <a href="/comum/meus-dados" class="nav_link @if ($view == 'meus-dados') active @endif">
                        <i class='bx bx-user nav_icon'></i>
                        <span class="nav_name">
                            Meus Dados
                        </span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <!--Container Main start-->
    <div class="height-100">
        <div class="col-md-12 mt-3">
            @if(session('msg_success'))
                <div class="alert alert-success" id="msg_success" role="alert">
                    <p>{{ session('msg_success') }}</p>
                </div>
            @endif
            @if(session('msg_error'))
               <div class="alert alert-danger" id="msg_error" role="alert">
                   <p>{{ session('msg_error') }}</p>
               </div>
            @endif
            @if(session('msg_validacao'))
                <div class="alert alert-danger" id="msg_validacao" role="alert">
                    <ul>
                        @foreach(session('msg_validacao') as $msg)
                            <li>{{ $msg }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" id="msg_erros_request" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="/js/navbar-comum.js" ></script>

    <script>
        @if(session('msg_success') || session('msg_error') || session('msg_validacao') || $errors->any())
        function hideMsgSuccess() {
            let msgSuccess = document.getElementById("msg_success");
            msgSuccess.style = "display:none";
        }

        function hideMsgError() {
            let msgError = document.getElementById("msg_error");
            msgError.style = "display:none";
        }
        function hideMsgValidacao() {
            let msgValidacao = document.getElementById("msg_validacao");
            msgValidacao.style = "display:none";
        }

        function hideMsgErrosRequest() {
            let msgErrosRequest = document.getElementById("msg_erros_request");
            msgErrosRequest.style = "display:none";
        }

        setTimeout(hideMsgSuccess, 4000);
        setTimeout(hideMsgError, 6000);
        setTimeout(hideMsgValidacao, 6000);
        setTimeout(hideMsgErrosRequest, 6000);
        @endif
    </script>
    @stack('scripts-meus-dados')
</body>
</html>

