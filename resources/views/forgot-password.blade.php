<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ cacheBust('css/forgot.css') }}">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/img/logo_ebd.png" />
    <title>Esqueci a senha — EBDControl</title>
</head>
<body>

<div class="login-bg">
    <div class="login-bg-glow login-bg-glow--1"></div>
    <div class="login-bg-glow login-bg-glow--2"></div>
    <div class="login-bg-grid"></div>
</div>

<main class="forgot-main">

    <div class="forgot-card">

        {{-- ÍCONE --}}
        <div class="forgot-icon-wrap">
            <i class='bx bx-lock-open-alt'></i>
        </div>

        {{-- HEADER --}}
        <h1 class="forgot-title">Esqueceu a senha?</h1>
        <p class="forgot-desc">
            Sem problemas! Entre em contato com a secretaria da EBD por um dos canais abaixo e ela irá te ajudar a recuperar o acesso.
        </p>

        {{-- CONTATOS --}}
        <div class="forgot-contacts">

            <a href="https://api.whatsapp.com/send?phone=55SEU_NUMERO" target="_blank" class="forgot-contact forgot-contact--whatsapp">
                <div class="forgot-contact-icon">
                    <i class='bx bxl-whatsapp'></i>
                </div>
                <div class="forgot-contact-body">
                    <span class="forgot-contact-name">WhatsApp</span>
                    <span class="forgot-contact-hint">Resposta mais rápida</span>
                </div>
                <i class='bx bx-chevron-right forgot-contact-arrow'></i>
            </a>

            <a href="https://instagram.com/SEU_INSTAGRAM" target="_blank" class="forgot-contact forgot-contact--instagram">
                <div class="forgot-contact-icon">
                    <i class='bx bxl-instagram'></i>
                </div>
                <div class="forgot-contact-body">
                    <span class="forgot-contact-name">Instagram</span>
                    <span class="forgot-contact-hint">Mande uma mensagem direta</span>
                </div>
                <i class='bx bx-chevron-right forgot-contact-arrow'></i>
            </a>

            <a href="mailto:SEU_EMAIL" class="forgot-contact forgot-contact--email">
                <div class="forgot-contact-icon">
                    <i class='bx bx-envelope'></i>
                </div>
                <div class="forgot-contact-body">
                    <span class="forgot-contact-name">E-mail</span>
                    <span class="forgot-contact-hint">secretaria@exemplo.com</span>
                </div>
                <i class='bx bx-chevron-right forgot-contact-arrow'></i>
            </a>

        </div>

        {{-- VOLTAR --}}
        <a href="/" class="forgot-back">
            <i class='bx bx-arrow-back'></i>
            Voltar para o login
        </a>

    </div>
</main>

</body>
</html>
