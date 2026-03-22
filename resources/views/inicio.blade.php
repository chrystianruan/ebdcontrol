<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ cacheBust('css/start.css') }}">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/img/logo_ebd.png" />
    <title>Início — EBDControl</title>
</head>
<body>

<div class="bg-mesh"></div>
<div class="bg-grid"></div>

<main class="start-main">

    {{-- LOGO --}}
    <div class="start-logo" style="animation-delay: 0s">
        <img src="/img/logo_ebd_extend.png" alt="EBDControl" height="36">
    </div>

    {{-- GREETING --}}
    <div class="start-greeting" style="animation-delay: 0.1s">
        <span class="start-greeting-label">Bem-vindo de volta</span>
        <h1 class="start-greeting-name">{{ auth()->user()->pessoa->nome }}</h1>
        <p class="start-greeting-sub">Selecione a área que deseja acessar</p>
    </div>

    {{-- ALERTAS --}}
    @if(auth()->user()->status)
        <div class="start-alert start-alert--danger" style="animation-delay: 0.15s">
            <i class='bx bx-error-circle'></i>
            <span>Seu usuário está desativado e não pode acessar nenhuma área.</span>
        </div>
    @endif

    @if(session('danger'))
        <div class="start-alert start-alert--danger" style="animation-delay: 0.15s">
            <i class='bx bx-error-circle'></i>
            <span>{{ session('danger') }}</span>
        </div>
    @endif

    @if(session('msg_success'))
        <div class="start-alert start-alert--success" style="animation-delay: 0.15s">
            <i class='bx bx-check-circle'></i>
            <span>{{ session('msg_success') }}</span>
        </div>
    @endif

    {{-- CARDS DE ACESSO --}}
    @if(!auth()->user()->status)
        <div class="start-cards">


            @if(auth()->user()->permissao_id == 1)
                <a href="/super-master/" class="start-card" style="animation-delay: 0.3s">
                    <div class="start-card-icon start-card-icon--super">
                        <i class='bx bx-infinite'></i>
                    </div>
                    <div class="start-card-body">
                        <span class="start-card-title">Área SuperMaster</span>
                        <span class="start-card-desc">Acesso total ao sistema</span>
                    </div>
                    <i class='bx bx-chevron-right start-card-arrow'></i>
                </a>
            @endif

            @if(auth()->user()->permissao_id <= 2)
                <a href="/master/" class="start-card" style="animation-delay: 0.25s">
                    <div class="start-card-icon start-card-icon--master">
                        <i class='bx bx-crown'></i>
                    </div>
                    <div class="start-card-body">
                        <span class="start-card-title">Área Master</span>
                        <span class="start-card-desc">Controle avançado e configurações gerais</span>
                    </div>
                    <i class='bx bx-chevron-right start-card-arrow'></i>
                </a>
            @endif

            @if(auth()->user()->permissao_id <= 3)
                <a href="/admin/" class="start-card" style="animation-delay: 0.2s">
                    <div class="start-card-icon start-card-icon--admin">
                        <i class='bx bx-shield-quarter'></i>
                    </div>
                    <div class="start-card-body">
                        <span class="start-card-title">Área Admin</span>
                        <span class="start-card-desc">Gestão de pessoas, chamadas e relatórios</span>
                    </div>
                    <i class='bx bx-chevron-right start-card-arrow'></i>
                </a>
            @endif

            @if(auth()->user()->permissao_id == 4)
                <a href="/classe/" class="start-card" style="animation-delay: 0.2s">
                    <div class="start-card-icon start-card-icon--classe">
                        <i class='bx bx-chalkboard'></i>
                    </div>
                    <div class="start-card-body">
                        <span class="start-card-title">Secretário / Professor</span>
                        <span class="start-card-desc">Gestão de classe e chamadas</span>
                    </div>
                    <i class='bx bx-chevron-right start-card-arrow'></i>
                </a>
            @endif

            @if(auth()->user()->pessoa_id)
                <a href="/comum/" class="start-card" style="animation-delay: 0.35s">
                    <div class="start-card-icon start-card-icon--comum">
                        <i class='bx bx-user-circle'></i>
                    </div>
                    <div class="start-card-body">
                        <span class="start-card-title">Área Comum</span>
                        <span class="start-card-desc">Seus dados e informações pessoais</span>
                    </div>
                    <i class='bx bx-chevron-right start-card-arrow'></i>
                </a>
            @endif

        </div>
    @endif

    {{-- FOOTER --}}
    <div class="start-footer" style="animation-delay: 0.45s">
        <form action="/logout" method="POST">
            @csrf
            <button class="start-logout" type="submit">
                <i class='bx bx-log-out'></i> Sair da conta
            </button>
        </form>
    </div>

</main>

</body>
</html>
