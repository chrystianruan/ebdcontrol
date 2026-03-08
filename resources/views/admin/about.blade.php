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
            <div class="sobre-hero-version">v2.0.0</div>
            <p class="sobre-hero-tagline">Gestão inteligente para sua Escola Bíblica Dominical</p>
            <div class="sobre-hero-since">
                <i class='bx bx-calendar-heart'></i>
                Desenvolvido com dedicação e excelência desde 2022
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
                    O EBDControl nasceu da necessidade de auxiliar o Templo Sede da IEADERN Parnamirim em suas atividades
                    durante a EBD. Visto que, àquela época, eram utilizados meios manuais e planilhas para controle de presenças,
                    cadastros e análises de dados. Desenvolvido em 2022, o sistema passou por diversas fases de testes e melhorias,
                    até que chegou em produção no ano de 2023, trazendo uma solução simples para os problemas existentes na época.
                    Pouco tempo depois, o EBDControl foi disponibilizado para outras congregações, e hoje é utilizado por
                    algumas igrejas do campo de Parnamirim.
                </p>
                <p class="sobre-card-text">
                    Trabalhamos sempre que possível para aprimorar o sistema, visando sempre oferecer um software eficiente,
                    intuitivo e poderoso para a gestão da Escola Bíblica Dominical. Nosso propósito sempre foi e sempre
                    será servir com dedicação e qualidade!
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
                        <span class="sobre-stat-label"><i class='bx bx-user-check'></i> Presenças</span>
                    </div>
                </div>
            </div>

            {{-- VERSÃO + CHANGELOG --}}
            <div class="sobre-card">
                <div class="sobre-card-icon">
                    <i class='bx bx-code-alt'></i>
                </div>
                <h2 class="sobre-card-title">Versão Atual</h2>
                <div class="sobre-version-badge">v2.0.0</div>
                <ul class="sobre-changelog">
                    <li class="sobre-changelog-item sobre-changelog-item--new">
                        <span class="sobre-changelog-tag new">Novo</span>
                        Nova estilização de todo ambiente admin, com design moderno e responsivo.
                    </li>
                    <li class="sobre-changelog-item sobre-changelog-item--new">
                        <span class="sobre-changelog-tag new">Novo</span>
                        Melhoria visual e adição de novas métricas aos relatórios e chamadas.
                    </li>
                    <li class="sobre-changelog-item sobre-changelog-item--new">
                        <span class="sobre-changelog-tag new">Novo</span>
                        Nova estilização da página de login e início.
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
                    Encontrou um bug, tem uma sugestão ou deseja ser voluntário nesse projeto?
                    Fale diretamente com o desenvolvedor ou DENEC.
                </p>
                <div class="sobre-contact-links">
                    <a href="https://api.whatsapp.com/send?phone=5584981203938" target="_blank" class="sobre-contact-btn sobre-contact-btn--whatsapp">
                        <i class='bx bxl-whatsapp'></i>
                        <div>
                            <strong>WhatsApp</strong>
                            <span>Contato direto com o desenvolvedor</span>
                        </div>
                        <i class='bx bx-chevron-right sobre-contact-arrow'></i>
                    </a>
                    <a href="https://instagram.com/denec.parnamirim" target="_blank" class="sobre-contact-btn sobre-contact-btn--instagram">
                        <i class='bx bxl-instagram'></i>
                        <div>
                            <strong>Instagram</strong>
                            <span>Perfil do DENEC</span>
                        </div>
                        <i class='bx bx-chevron-right sobre-contact-arrow'></i>
                    </a>
                    <a href="mailto:suporte@ebdcontrol.com" target="_blank" class="sobre-contact-btn sobre-contact-btn--email">
                        <i class='bx bx-envelope'></i>
                        <div>
                            <strong>Email</strong>
                            <span>suporte@ebdcontrol.com</span>
                        </div>
                        <i class='bx bx-chevron-right sobre-contact-arrow'></i>
                    </a>
                </div>
            </div>

            {{-- AGRADECIMENTOS --}}
            <div class="sobre-card sobre-card--full">
                <div class="sobre-card-icon">
                    <i class='bx bx-heart'></i>
                </div>
                <h2 class="sobre-card-title">Agradecimentos</h2>
                <p class="sobre-card-text">
                    Expressamos nossa sincera gratidão a todos que, direta ou indiretamente,
                    contribuíram para a realização, desenvolvimento e aprimoramento deste sistema. Este projeto é fruto
                    de apoio, colaboração e dedicação de muitas pessoas ao longo do tempo. Portanto, somos imensamente
                    gratos a:
                </p>
                <div class="sobre-agradecimentos-list">
                    <div class="sobre-agradecimento-item">
                        <i class='bx bx-bible'></i>
                        <span>
                            A Deus, em primeiro lugar, pelo Seu infinito amor, misericórdia e cuidado. A Ele somos gratos
                            pela força, criatividade e capacidade concedidas para que possamos servir com dedicação e excelência!
                        </span>
                    </div>
                    <div class="sobre-agradecimento-item">
                        <i class='bx bx-church'></i>
                        <span>
                            A nossa IEADERN Parnamirim, pelo apoio constante, pela confiança e por proporcionar o
                            ambiente e os recursos necessários para que este sistema pudesse ser desenvolvido e utilizado.
                        </span>
                    </div>
                    <div class="sobre-agradecimento-item">
                        <i class='bx bx-group'></i>
                        <span>
                            Aos irmãos em Cristo que colaboraram e apoiaram este projeto ao longo do tempo, seja com
                            ideias, incentivo ou contribuição direta. Por ordem cronológica: Abner Irineu, Ev. Assis Irineu, Pb. Domingos José,
                            Pr. Elinaldo Renovato, Hartur e Pr. Abdênego Xavier.
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <div class="sobre-footer">
            <p>EBDControl &copy; {{ date('Y') }} &mdash; Feito com dedicação e excelência para a obra de Deus</p>
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
