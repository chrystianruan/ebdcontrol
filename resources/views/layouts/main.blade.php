<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <link rel="stylesheet" href="/css/rootAdmin.css">
    <link rel="stylesheet" href="/css/navbarAdmin.css">
    @stack('dash.admin.css')
    @stack('pessoas.admin.css')
    @stack('chamadas.admin.css')
    @stack('modal.admin.css')

    <title>EBDControl</title>
</head>
<body>
<header class="header" id="header">
    <nav class="nav container">
        <div class="div-nav__logo">
            <a href="#" class="nav__logo">
                <img width="140"  src="/img/logo_ebd_extend.png" alt="logo_ebd">
            </a>
        </div>

        <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="/admin" class="nav__link @if($blade == \App\Http\Enums\ViewEnum::HOME->value) active-link @endif">
                        <i class='bx bx-home-alt nav__icon'></i>
                        <span class="nav__name">Home</span>
                    </a>
                </li>

                <li class="nav__item">
                    <a href="/admin/filtro/pessoa" class="nav__link @if($blade == \App\Http\Enums\ViewEnum::PESSOAS->value) active-link @endif">
                        <i class='bx bx-user nav__icon'></i>
                        <span class="nav__name">Pessoas</span>
                    </a>
                </li>

                <li class="nav__item">
                    <a href="/admin/chamadas" class="nav__link @if($blade == App\Http\Enums\ViewEnum::CHAMADAS->value) active-link @endif">
                        <i class='bx bx-list-ul nav__icon'></i>
                        <span class="nav__name">Chamadas</span>
                    </a>
                </li>

                <li class="nav__item">
                    <a href="#skills" class="nav__link @if($blade == "relatorios") active-link @endif">
                        <i class='bx bx-trending-up nav__icon'></i>
                        <span class="nav__name">Relatórios</span>
                    </a>
                </li>


                <li class="nav__item">
                    <a href="#contactme" class="nav__link @if($blade == "configuracoes") active-link @endif">
                        <i class='bx bx-cog nav__icon'></i>
                        <span class="nav__name">Configurações</span>
                    </a>
                </li>
            </ul>
        </div>

        <img src="assets/img/perfil.png" alt="" class="nav__img">
    </nav>
</header>

<main>
    <section class="container section section__height" id="home">
        @yield('content')
    </section>
</main>

<script
    src="https://code.jquery.com/jquery-3.6.0.js"
    integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous">
</script>

@stack('scripts-cadastro')
@stack('pre-cadastro.script')
@stack('pessoas-filtro.admin.script')
</body>
</html>
