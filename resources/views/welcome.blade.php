<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ cacheBust('css/login.css') }}">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/img/logo_ebd.png" />
    <title>Login — EBDControl</title>
</head>
<body>

<div class="login-bg">
    <div class="login-bg-glow login-bg-glow--1"></div>
    <div class="login-bg-glow login-bg-glow--2"></div>
    <div class="login-bg-grid"></div>
</div>

<main class="login-main">

    <div class="login-card">

        {{-- LOGOS --}}
        <div class="login-logos">
            <img src="/img/logo-nova-adpar.png" alt="ADPAR" height="48">
            <div class="login-logos-divider"></div>
            <img src="/img/logo_ebd_extend.png" alt="EBDControl" height="32">
        </div>

        {{-- HEADER --}}
        <div class="login-header">
            <h1 class="login-title">Bem-vindo</h1>
            <p class="login-subtitle">Entre com suas credenciais para continuar</p>
        </div>

        {{-- ALERTAS --}}
        @if(session('msg'))
            <div class="login-alert login-alert--success">
                <i class='bx bx-check-circle'></i>
                <span>{{ session('msg') }}</span>
            </div>
        @endif

        @if(session('danger'))
            <div class="login-alert login-alert--danger">
                <i class='bx bx-error-circle'></i>
                <span>{{ session('danger') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="login-alert login-alert--danger">
                <i class='bx bx-error-circle'></i>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="/" class="login-form">
            @csrf

            <div class="login-field">
                <label class="login-label">Usuário</label>
                <div class="login-input-wrap">
                    <i class='bx bx-user login-input-icon'></i>
                    <input
                        type="text"
                        name="username"
                        placeholder="Nome de usuário"
                        required
                        value="{{ old('username') }}"
                        class="login-input"
                    >
                </div>
            </div>

            <div class="login-field">
                <label class="login-label">Senha</label>
                <div class="login-input-wrap">
                    <i class='bx bx-lock-alt login-input-icon' id="btn-lock" style="cursor:pointer" title="Mostrar/ocultar senha"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Sua senha"
                        required
                        class="login-input"
                    >
                </div>
                <span class="login-field-hint">Clique no cadeado para ver a senha</span>
            </div>

            <a href="/forgot-password" class="login-forgot">Esqueceu a senha?</a>

            <button type="submit" class="login-btn">
                Entrar
                <i class='bx bx-log-in-circle'></i>
            </button>

        </form>

    </div>
</main>

<script>
    const password = document.getElementById('password');
    const btnLock  = document.getElementById('btn-lock');
    btnLock.addEventListener('click', () => {
        const show = password.type === 'password';
        password.type = show ? 'text' : 'password';
        btnLock.className = show ? 'bx bx-lock-open-alt login-input-icon' : 'bx bx-lock-alt login-input-icon';
        btnLock.style.cursor = 'pointer';
    });
</script>

</body>
</html>
