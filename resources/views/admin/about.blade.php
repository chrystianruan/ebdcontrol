@extends('layouts.main')

@section('content')

    @push('dash.admin.css')
        <link rel="stylesheet" href="{{ cacheBust('css/aboutAdmin.css') }}">
    @endpush

    <div class="sobre-wrapper">

        {{-- HERO --}}
        <div class="sobre-hero">
            <div class="sobre-hero-glow"></div>
            <img src="/img/logo_ebd_extend.png" alt="EBDControl" class="sobre-hero-logo">
            <div class="sobre-hero-version">v1.0.0</div>
            <p class="sobre-hero-tagline">Gestão inteligente para sua Escola Bíblica Dominical</p>
            <div class="sobre-hero-since">
                <i class='bx bx-calendar-heart'></i>
                Desenvolvido com dedicação desde 2023
            </div>
        </div>

        {{-- GRID DE CARDS --}}
        <div class="sobre-grid">

            {{-- HISTÓRIA --}}
            <div class="sobre-card sobre-card--full">
                <div class="sobre-card-icon">
                    <i class='bx bx-book-open'></i>
                </div>
                <h2 class="sobre-card-title">Nossa História</h2>
                <p class="sobre-card-text">
                    O EBDControl nasceu em 2023 da necessidade real de organizar e modernizar a gestão da
                    Escola Bíblica Dominical. O que começou como uma solução simples para controle de chamadas
                    evoluiu para uma plataforma completa, reunindo cadastro de pessoas, controle de classes,
                    relatórios de presença e muito mais — tudo pensado para facilitar o trabalho de líderes
                    e secretários da EBD.
                </p>
                <p class="sobre-card-text">
                    Cada funcionalidade foi desenvolvida ouvindo quem usa no dia a dia, com foco em
                    simplicidade, agilidade e confiabilidade.
                </p>
            </div>

            {{-- ESTATÍSTICAS --}}
            <div class="sobre-card sobre-card--stats">
                <div class="sobre-card-icon">
                    <i class='bx bx-bar-chart-alt-2'></i>
                </div>
                <h2 class="sobre-card-title">O sistema em números</h2>
                <div class="sobre-stats-grid">
                    <div class="sobre-stat-item">
                        <span class="sobre-stat-value" data-count="{{ $stats['pessoas'] }}">0</span>
                        <span class="sobre-stat-label"><i class='bx bx-user'></i> Pessoas</span>
                    </div>
                    <div class="sobre-stat-item">
                        <span class="sobre-stat-value" data-count="{{ $stats['chamadas'] }}">0</span>
                        <span class="sobre-stat-label"><i class='bx bx-list-check'></i> Chamadas</span>
                    </div>
                    <div class="sobre-stat-item">
                        <span class="sobre-stat-value" data-count="{{ $stats['classes'] }}">0</span>
                        <span class="sobre-stat-label"><i class='bx bx-chalkboard'></i> Classes</span>
                    </div>
                    <div class="sobre-stat-item">
                        <span class="sobre-stat-value" data-count="{{ $stats['presencas'] }}">0</span>
                        <span class="sobre-stat-label"><i class='bx bx-shield-alt-2'></i> Presenças</span>
                    </div>
                </div>
            </div>

            {{-- VERSÃO + CHANGELOG --}}
            <div class="sobre-card">
                <div class="sobre-card-icon">
                    <i class='bx bx-code-alt'></i>
                </div>
                <h2 class="sobre-card-title">Versão Atual</h2>
                <div class="sobre-version-badge">v1.0.0</div>
                <ul class="sobre-changelog">
                    <li class="sobre-changelog-item sobre-changelog-item--new">
                        <span class="sobre-changelog-tag new">Novo</span>
                        Cadastro e gestão completa de pessoas
                    </li>
                    <li class="sobre-changelog-item sobre-changelog-item--new">
                        <span class="sobre-changelog-tag new">Novo</span>
                        Controle de chamadas por classe
                    </li>
                    <li class="sobre-changelog-item sobre-changelog-item--new">
                        <span class="sobre-changelog-tag new">Novo</span>
                        Relatórios de presença com gráficos
                    </li>
                    <li class="sobre-changelog-item sobre-changelog-item--new">
                        <span class="sobre-changelog-tag new">Novo</span>
                        Pré-cadastros e aniversariantes
                    </li>
                    <li class="sobre-changelog-item sobre-changelog-item--new">
                        <span class="sobre-changelog-tag new">Novo</span>
                        Geração de chamada física em PDF
                    </li>
                </ul>
            </div>

            {{-- STACK --}}
            <div class="sobre-card">
                <div class="sobre-card-icon">
                    <i class='bx bx-layer'></i>
                </div>
                <h2 class="sobre-card-title">Tecnologias</h2>
                <div class="sobre-stack-list">
                    <div class="sobre-stack-item">
                        <div class="sobre-stack-dot" style="background:#FF2D20"></div>
                        <div>
                            <strong>Laravel</strong>
                            <span>Framework back-end</span>
                        </div>
                    </div>
                    <div class="sobre-stack-item">
                        <div class="sobre-stack-dot" style="background:#4479A1"></div>
                        <div>
                            <strong>MySQL</strong>
                            <span>Banco de dados</span>
                        </div>
                    </div>
                    <div class="sobre-stack-item">
                        <div class="sobre-stack-dot" style="background:#7B4EA5"></div>
                        <div>
                            <strong>Blade</strong>
                            <span>Template engine</span>
                        </div>
                    </div>
                    <div class="sobre-stack-item">
                        <div class="sobre-stack-dot" style="background:#F7DF1E"></div>
                        <div>
                            <strong>JavaScript</strong>
                            <span>Interatividade</span>
                        </div>
                    </div>
                    <div class="sobre-stack-item">
                        <div class="sobre-stack-dot" style="background:#264DE4"></div>
                        <div>
                            <strong>CSS3</strong>
                            <span>Estilização</span>
                        </div>
                    </div>
                    <div class="sobre-stack-item">
                        <div class="sobre-stack-dot" style="background:#0769AD"></div>
                        <div>
                            <strong>jQuery</strong>
                            <span>Requisições AJAX</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONTATO --}}
            <div class="sobre-card sobre-card--contato">
                <div class="sobre-card-icon">
                    <i class='bx bx-chat'></i>
                </div>
                <h2 class="sobre-card-title">Contato & Suporte</h2>
                <p class="sobre-card-text">
                    Encontrou um bug, tem uma sugestão ou quer saber mais sobre o sistema?
                    Fale diretamente com o desenvolvedor.
                </p>
                <div class="sobre-contact-links">
                    <a href="https://api.whatsapp.com/send?phone=55SEU_NUMERO" target="_blank" class="sobre-contact-btn sobre-contact-btn--whatsapp">
                        <i class='bx bxl-whatsapp'></i>
                        <div>
                            <strong>WhatsApp</strong>
                            <span>Suporte e dúvidas</span>
                        </div>
                        <i class='bx bx-chevron-right sobre-contact-arrow'></i>
                    </a>
                    <a href="https://instagram.com/SEU_INSTAGRAM" target="_blank" class="sobre-contact-btn sobre-contact-btn--instagram">
                        <i class='bx bxl-instagram'></i>
                        <div>
                            <strong>Instagram</strong>
                            <span>Acompanhe as novidades</span>
                        </div>
                        <i class='bx bx-chevron-right sobre-contact-arrow'></i>
                    </a>
                </div>
            </div>

            {{-- AGRADECIMENTOS --}}
            <div class="sobre-card sobre-card--agradecimentos">
                <div class="sobre-card-icon">
                    <i class='bx bx-heart'></i>
                </div>
                <h2 class="sobre-card-title">Agradecimentos</h2>
                <p class="sobre-card-text">
                    Este sistema só existe graças às pessoas que acreditaram na ideia e contribuíram
                    com feedback, testes e sugestões desde o início.
                </p>
                <div class="sobre-agradecimentos-list">
                    <div class="sobre-agradecimento-item">
                        <i class='bx bx-church'></i>
                        <span>À nossa congregação, por ser a motivação de tudo</span>
                    </div>
                    <div class="sobre-agradecimento-item">
                        <i class='bx bx-group'></i>
                        <span>A todos que testaram e sugeriram melhorias</span>
                    </div>
                    <div class="sobre-agradecimento-item">
                        <i class='bx bx-coffee-togo'></i>
                        <span>E muito café ☕</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="sobre-footer">
            <p>EBDControl &copy; {{ date('Y') }} &mdash; Feito com <i class='bx bxs-heart' style="color:#e05"></i> para a obra de Deus</p>
        </div>

    </div>

    <script>
        // Contador animado nas estatísticas
        function animateCount(el) {
            const target = parseInt(el.dataset.count);
            const duration = 1500;
            const step = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    el.textContent = target.toLocaleString('pt-BR');
                    clearInterval(timer);
                } else {
                    el.textContent = Math.floor(current).toLocaleString('pt-BR');
                }
            }, 16);
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    document.querySelectorAll('.sobre-stat-value').forEach(animateCount);
                    observer.disconnect();
                }
            });
        }, { threshold: 0.3 });

        const statsCard = document.querySelector('.sobre-card--stats');
        if (statsCard) observer.observe(statsCard);
    </script>

@endsection
