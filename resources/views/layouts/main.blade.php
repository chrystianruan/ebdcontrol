<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <link rel="stylesheet" href="{{ cacheBust('css/rootAdmin.css') }}">
    <link rel="stylesheet" href="{{ cacheBust('css/navbarAdmin.css') }}">
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
                    <a href="/admin/relatorios" class="nav__link @if($blade == App\Http\Enums\ViewEnum::RELATORIOS) active-link @endif">
                        <i class='bx bx-trending-up nav__icon'></i>
                        <span class="nav__name">Relatórios</span>
                    </a>
                </li>

                <li class="nav__item">
                    <a href="/admin/about" class="nav__link @if($blade == "about") active-link @endif">
                        <i class='bx bx-info-circle nav__icon'></i>
                        <span class="nav__name">Sobre</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="nav__user" id="nav-user">
            <button class="nav__user-btn" id="userDropdownBtn">
                <div class="nav__user-avatar">
                    <i class='bx bx-user'></i>
                </div>
                <div class="nav__user-info">
                    <span class="nav__user-name">{{ auth()->user()->formattedNome() }}</span>
                    <span class="nav__user-role">{{ auth()->user()->matricula }}</span>
                </div>
                <i class='bx bx-chevron-down nav__user-chevron'></i>
            </button>

            <div class="nav__user-dropdown" id="userDropdown">
                <div class="nav__user-dropdown-header">
                    <strong>{{ auth()->user()->pessoa->nome }}</strong>
                    <span>{{ auth()->user()->matricula }}</span>
                </div>
                <div class="nav__user-dropdown-divider"></div>
                <div class="nav__user-roles">
                    <span class="nav__user-roles-label">Permissão Atual</span>
                    <span class="nav__user-role-badge nav__user-role-badge--active">Admin</span>
                </div>
                <div class="nav__user-dropdown-divider"></div>
                <div class="nav__user-roles">
                    <span class="nav__user-roles-label">Permissões Disponíveis</span>
                    @if (auth()->user()->permissao_id == 1)
                        <span class="nav__user-role-badge">
                            <a href="/super-master"> Supermaster </a>
                        </span>
                    @endif
                    @if (auth()->user()->permissao_id == 1 || auth()->user()->permissao_id == 2 )
                        <span class="nav__user-role-badge">
                            <a href="/master">Master</a>
                        </span>
                    @endif
                    <span class="nav__user-role-badge">
                        <a href="/comum">Comum</a>
                    </span>
                </div>
                <div class="nav__user-dropdown-divider"></div>
                <form action="/logout" method="POST">
                    @csrf
                    <button class="nav__user-dropdown-item nav__user-logout">
                        <i class='bx bx-log-out'></i> Sair
                    </button>
                </form>
            </div>
        </div>

        {{-- Bottom Sheet (mobile) --}}
        <div class="bottom-sheet-overlay" id="bottomSheetOverlay"></div>
        <div class="bottom-sheet" id="bottomSheet">
            <div class="bottom-sheet-handle"></div>
            <div class="bottom-sheet-header">
                <div class="nav__user-avatar nav__user-avatar--lg">
                    <i class='bx bx-user'></i>
                </div>
                <div>
                    <strong>{{ auth()->user()->pessoa->nome }}</strong>
                    <span>{{ auth()->user()->matricula }}</span>
                </div>
            </div>
            <div class="bottom-sheet-divider"></div>
            <div class="bottom-sheet-section">
                <span class="bottom-sheet-label">Permissão Atual</span>
                <div class="bottom-sheet-roles">
                    <span class="nav__user-role-badge">
                        Admin
                    </span>
                </div>
            </div>
            <div class="bottom-sheet-divider"></div>
            <div class="bottom-sheet-section">
                <span class="bottom-sheet-label">Permissões Disponíveis</span>
                <div class="bottom-sheet-roles">
                    @if (auth()->user()->permissao_id == 1)
                        <span class="nav__user-role-badge">
                            <a href="/super-master"> Supermaster </a>
                        </span>
                    @endif
                    @if (auth()->user()->permissao_id == 1 || auth()->user()->permissao_id == 2 )
                        <span class="nav__user-role-badge">
                            <a href="/master">Master</a>
                        </span>
                    @endif
                    <span class="nav__user-role-badge">
                        <a href="/comum">Comum</a>
                    </span>
                </div>
            </div>
            <div class="bottom-sheet-divider"></div>
            <form action="/logout" method="POST">
                @csrf
                <button class="bottom-sheet-logout">
                    <i class='bx bx-log-out'></i>
                    Sair
                </button>
            </form>
        </div>

{{--        <img src="assets/img/perfil.png" alt="" class="nav__img">--}}
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
@stack('preRegister.admin.js')
@stack('aniversariantes.admin.js')
@stack('chamadas.admin.script')
@stack('scripts-relatorio-presenca')

<script>
    const userBtn = document.getElementById('userDropdownBtn');
    const userDropdown = document.getElementById('userDropdown');
    const bottomSheet = document.getElementById('bottomSheet');
    const overlay = document.getElementById('bottomSheetOverlay');

    const isMobile = () => window.innerWidth < 767;

    function openBottomSheet() {
        overlay.style.display = 'block';
        requestAnimationFrame(() => {
            bottomSheet.classList.add('open');
            overlay.classList.add('open');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeBottomSheet() {
        bottomSheet.classList.remove('open');
        overlay.classList.remove('open');
        setTimeout(() => overlay.style.display = 'none', 300);
        document.body.style.overflow = '';
    }

    userBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (isMobile()) {
            openBottomSheet();
        } else {
            userBtn.classList.toggle('open');
            userDropdown.classList.toggle('open');
        }
    });

    overlay.addEventListener('click', closeBottomSheet);

    document.addEventListener('click', () => {
        userBtn.classList.remove('open');
        userDropdown.classList.remove('open');
    });

    // Swipe down para fechar
    let startY = 0;
    bottomSheet.addEventListener('touchstart', e => startY = e.touches[0].clientY);
    bottomSheet.addEventListener('touchend', e => {
        if (e.changedTouches[0].clientY - startY > 80) closeBottomSheet();
    });
</script>
</body>
</html>
