<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#004169">
    <title>Verificação de Segurança</title>
    <style>
        :root {
            --azul: #004169;
            --azul-medio: #0071ad;
            --amarelo: #ffd400;
            --amarelo-escuro: #e6bc00;
            --cinza-fundo: #f5f3f0;
            --cinza-borda: #ddd;
            --texto: #1a1a2e;
            --texto-suave: #555577;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Trebuchet MS', 'Segoe UI', Arial, sans-serif;
            background: var(--cinza-fundo);
            color: var(--texto);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .header {
            background: var(--cinza-fundo);
            border-bottom: 3px solid var(--amarelo);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-icon {
            width: 36px;
            height: 36px;
            background: var(--azul);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header-icon svg {
            width: 22px;
            height: 22px;
            fill: #fff;
        }
        .header-nome {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--azul);
            letter-spacing: 0.01em;
        }

        /* MAIN */
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px 24px;
            max-width: 420px;
            width: 100%;
            text-align: center;
        }

        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(0,65,105,0.08);
            color: var(--azul);
            font-size: 0.75rem;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 100px;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-dot {
            width: 7px;
            height: 7px;
            background: var(--amarelo-escuro);
            border-radius: 50%;
        }

        /* Avatar */
        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--azul), var(--azul-medio));
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(0,65,105,0.25);
        }
        .avatar svg {
            width: 44px;
            height: 44px;
            fill: #fff;
        }

        h1 {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--azul);
            line-height: 1.4;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 0.88rem;
            color: var(--texto-suave);
            line-height: 1.6;
            margin-bottom: 24px;
        }

        /* CTA Button */
        .cta-button {
            display: block;
            width: 100%;
            padding: 16px;
            background: var(--azul);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-bottom: 12px;
            letter-spacing: 0.02em;
        }
        .cta-button:hover { background: #005a8e; }
        .cta-button:active { transform: scale(0.98); }

        .info-text {
            font-size: 0.78rem;
            color: #94a3b8;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        .info-text svg {
            width: 13px;
            height: 13px;
            fill: #94a3b8;
        }

        /* Links rodapé do card */
        .links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .links button {
            background: none;
            border: none;
            color: var(--azul-medio);
            font-size: 0.78rem;
            cursor: pointer;
            font-family: inherit;
            padding: 2px 0;
        }
        .links button:hover { text-decoration: underline; }
        .links span { color: #ccc; font-size: 0.75rem; }

        /* MODAL */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .modal-overlay.active { display: flex; }

        .modal {
            background: #fff;
            border-radius: 16px;
            padding: 28px 24px;
            max-width: 440px;
            width: 100%;
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }

        .modal-close {
            position: absolute;
            top: 14px;
            right: 16px;
            background: none;
            border: none;
            font-size: 1.4rem;
            color: #94a3b8;
            cursor: pointer;
            line-height: 1;
        }
        .modal-close:hover { color: var(--azul); }

        .modal-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--azul);
            margin-bottom: 12px;
            padding-right: 24px;
        }
        .modal-text {
            font-size: 0.88rem;
            color: var(--texto-suave);
            line-height: 1.6;
            margin-bottom: 12px;
        }
        .modal-list {
            list-style: none;
            padding: 0;
        }
        .modal-list li {
            font-size: 0.85rem;
            color: var(--texto-suave);
            padding: 6px 0;
            padding-left: 18px;
            position: relative;
        }
        .modal-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--azul);
            font-weight: 700;
        }
        .policy-content h3 {
            font-size: 0.9rem;
            color: var(--azul);
            margin: 14px 0 6px;
        }
        .policy-content p, .policy-content ul {
            font-size: 0.83rem;
            color: var(--texto-suave);
            line-height: 1.6;
        }
        .policy-content ul { padding-left: 16px; list-style: disc; }

        /* STEPPER */
        .stepper {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            gap: 0;
        }
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }
        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            transition: all 0.3s;
        }
        .step-number.active {
            background: var(--azul);
            color: #fff;
            box-shadow: 0 2px 8px rgba(0,65,105,0.3);
        }
        .step-number.done {
            background: #22c55e;
            color: #fff;
        }
        .step-number.pending {
            background: #e5e7eb;
            color: #94a3b8;
        }
        .step-label {
            font-size: 0.7rem;
            color: var(--texto-suave);
            font-weight: 500;
        }
        .step-separator {
            width: 40px;
            height: 2px;
            background: #e5e7eb;
            margin: 0 4px;
            margin-bottom: 18px;
            transition: background 0.3s;
        }
        .step-separator.done { background: #22c55e; }

        /* Modal content steps */
        .modal-content { display: none; }
        .modal-content.active { display: block; }

        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 16px;
        }
        .security-badge::before {
            content: '🔒';
            font-size: 0.75rem;
        }

        /* SLIDER */
        .slider-container { margin: 20px 0; }
        .slider-track {
            position: relative;
            height: 52px;
            background: #f1f5f9;
            border: 2px solid #e2e8f0;
            border-radius: 30px;
            overflow: hidden;
            user-select: none;
        }
        .slider-fill {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            background: linear-gradient(90deg, var(--amarelo), var(--amarelo-escuro));
            border-radius: 30px;
            width: 52px;
            transition: none;
        }
        .slider-text {
            position: absolute;
            width: 100%;
            text-align: center;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--texto-suave);
            pointer-events: none;
            z-index: 2;
            transition: opacity 0.2s;
        }
        .slider-button {
            position: absolute;
            left: 0;
            top: 0;
            width: 52px;
            height: 48px;
            background: var(--azul);
            border-radius: 28px;
            cursor: grab;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            box-shadow: 0 2px 8px rgba(0,65,105,0.3);
        }
        .slider-button::after {
            content: '▶';
            color: #fff;
            font-size: 0.9rem;
        }
        .slider-button:active { cursor: grabbing; }
        .slider-button.done {
            background: #22c55e;
        }
        .slider-button.done::after { content: '✓'; }
        .slider-message {
            margin-top: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #22c55e;
            min-height: 20px;
            text-align: center;
        }

        /* Etapa 3 — sucesso */
        .success-icon {
            width: 64px;
            height: 64px;
            background: #dcfce7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 1.8rem;
        }
        .redirect-info {
            font-size: 0.82rem;
            color: #94a3b8;
            margin-top: 8px;
        }
        .redirect-bar {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            margin: 14px 0 0;
            overflow: hidden;
        }
        .redirect-fill {
            height: 100%;
            background: var(--azul);
            border-radius: 2px;
            width: 0%;
            transition: width 2.5s linear;
        }

        /* FAQ */
        .faq-section {
            max-width: 420px;
            margin: 0 auto;
            padding: 0 16px 32px;
        }
        .faq-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--azul);
            margin-bottom: 12px;
            text-align: center;
        }
        .faq-item {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            margin-bottom: 8px;
            overflow: hidden;
            background: #fff;
        }
        .faq-question {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 16px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--texto);
            user-select: none;
        }
        .faq-question:hover { background: #f8faff; }
        .faq-arrow {
            font-size: 0.65rem;
            color: var(--azul-medio);
            transition: transform 0.2s;
            flex-shrink: 0;
        }
        .faq-item.open .faq-arrow { transform: rotate(90deg); }
        .faq-answer {
            display: none;
            padding: 0 16px 13px;
        }
        .faq-item.open .faq-answer { display: block; }
        .faq-answer-text {
            font-size: 0.82rem;
            color: var(--texto-suave);
            line-height: 1.6;
        }

        /* Footer */
        footer {
            background: var(--azul);
            color: rgba(255,255,255,0.6);
            text-align: center;
            font-size: 0.75rem;
            padding: 16px;
        }

        /* Loading overlay */
        .loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,0.85);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 12px;
        }
        .loading-overlay.active { display: flex; }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e5e7eb;
            border-top-color: var(--azul);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading-text {
            font-size: 0.88rem;
            color: var(--azul);
            font-weight: 600;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<header class="header">
    <div class="header-logo">
        <div class="header-icon">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4l5 2.18V11c0 3.5-2.33 6.79-5 7.93-2.67-1.14-5-4.43-5-7.93V7.18L12 5z"/>
            </svg>
        </div>
        <span class="header-nome">Verificação Segura</span>
    </div>
</header>

<!-- MAIN -->
<main>
    <div class="card">
        <div class="badge">
            <div class="badge-dot"></div>
            Ambiente protegido
        </div>

        <div class="avatar">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
            </svg>
        </div>

        <h1>Verificação necessária para acessar o rastreamento</h1>
        <p class="subtitle">Para sua segurança, confirme que você é humano antes de prosseguir.</p>

        <button class="cta-button" onclick="abrirVerificacao()">
            🔍 Verificar e Rastrear
        </button>

        <p class="info-text">
            <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
            Verificação rápida — sem custos
        </p>

        <div class="links">
            <button onclick="abrirModal('modalPolitica')">Política de Privacidade</button>
            <span>|</span>
            <button onclick="abrirModal('modalTermos')">Termos de Uso</button>
            <span>|</span>
            <button onclick="scrollToFAQ()">FAQ</button>
        </div>
    </div>
</main>

<!-- FAQ -->
<section class="faq-section" id="faq">
    <h2 class="faq-title">Perguntas Frequentes</h2>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFAQ(this)">
            <span class="faq-arrow">▶</span>
            <span>Este site cobra alguma taxa?</span>
        </div>
        <div class="faq-answer">
            <p class="faq-answer-text">A verificação de acesso é gratuita. Taxas aduaneiras, quando existentes, são cobradas pelos órgãos competentes diretamente.</p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFAQ(this)">
            <span class="faq-arrow">▶</span>
            <span>Por que preciso verificar que sou humano?</span>
        </div>
        <div class="faq-answer">
            <p class="faq-answer-text">Para proteger sua privacidade e evitar consultas automáticas indevidas ao seu código de rastreio.</p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFAQ(this)">
            <span class="faq-arrow">▶</span>
            <span>Meus dados ficam salvos?</span>
        </div>
        <div class="faq-answer">
            <p class="faq-answer-text">Não armazenamos dados pessoais nesta etapa. A verificação é processada localmente no seu navegador.</p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFAQ(this)">
            <span class="faq-arrow">▶</span>
            <span>Informações de contato</span>
        </div>
        <div class="faq-answer">
            <p class="faq-answer-text">CNPJ: 42.213.794/0001-11 — Plataforma independente de consulta de rastreamento.</p>
        </div>
    </div>
</section>

<footer>
    <p>© 2026 — Plataforma independente de rastreamento. Sem vínculo com órgãos públicos.</p>
</footer>

<!-- LOADING -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
    <div class="loading-text">Carregando verificação...</div>
</div>

<!-- MODAL VERIFICAÇÃO -->
<div class="modal-overlay" id="modalVerificacao">
    <div class="modal">

        <!-- Stepper -->
        <div class="stepper">
            <div class="step-item">
                <div class="step-number active" id="step1Num">1</div>
                <span class="step-label">Início</span>
            </div>
            <div class="step-separator" id="sep1"></div>
            <div class="step-item">
                <div class="step-number pending" id="step2Num">2</div>
                <span class="step-label">Verificar</span>
            </div>
            <div class="step-separator" id="sep2"></div>
            <div class="step-item">
                <div class="step-number pending" id="step3Num">3</div>
                <span class="step-label">Acessar</span>
            </div>
        </div>

        <!-- Etapa 1 -->
        <div class="modal-content active" id="etapa1">
            <h2 class="modal-title">Confirmação de Segurança</h2>
            <p class="modal-text">Clique abaixo para iniciar a verificação de acesso ao rastreamento.</p>
            <button class="cta-button" onclick="irParaEtapa(2)">Iniciar verificação</button>
            <div class="security-badge">Conexão segura • Dados protegidos</div>
        </div>

        <!-- Etapa 2 — Slider -->
        <div class="modal-content" id="etapa2">
            <h2 class="modal-title">Verificação Humana</h2>
            <p class="modal-text">Arraste o botão até o final para confirmar.</p>

            <div class="slider-container">
                <div class="slider-track" id="sliderTrack">
                    <div class="slider-fill" id="sliderFill"></div>
                    <div class="slider-text" id="sliderText">← Arraste para verificar →</div>
                    <div class="slider-button" id="sliderButton"></div>
                </div>
                <div class="slider-message" id="sliderMessage"></div>
            </div>

            <div class="security-badge">Conexão segura • Dados protegidos</div>
        </div>

        <!-- Etapa 3 — Sucesso + redirect -->
        <div class="modal-content" id="etapa3">
            <div class="success-icon">✅</div>
            <h2 class="modal-title">Verificação concluída!</h2>
            <p class="modal-text">Identidade confirmada. Redirecionando para o rastreamento...</p>
            <div class="redirect-bar">
                <div class="redirect-fill" id="redirectFill"></div>
            </div>
            <p class="redirect-info">Aguarde, você será redirecionado automaticamente.</p>
        </div>

    </div>
</div>

<!-- MODAL POLÍTICA -->
<div class="modal-overlay" id="modalPolitica">
    <div class="modal">
        <button class="modal-close" onclick="fecharModal('modalPolitica')">&times;</button>
        <h2 class="modal-title">Política de Privacidade</h2>
        <div class="policy-content">
            <p>Tratamos dados conforme a LGPD (Lei 13.709/2018).</p>
            <h3>1. Dados coletados</h3>
            <p>Apenas informações técnicas necessárias para o funcionamento (cookies de sessão, logs de acesso).</p>
            <h3>2. Finalidades</h3>
            <ul>
                <li>Prestar as informações solicitadas</li>
                <li>Garantir segurança contra acessos automatizados</li>
                <li>Melhorar a experiência do usuário</li>
            </ul>
            <h3>3. Compartilhamento</h3>
            <p>Não vendemos dados. Compartilhamos apenas com provedores tecnológicos essenciais, sob sigilo.</p>
        </div>
    </div>
</div>

<!-- MODAL TERMOS -->
<div class="modal-overlay" id="modalTermos">
    <div class="modal">
        <button class="modal-close" onclick="fecharModal('modalTermos')">&times;</button>
        <h2 class="modal-title">Termos de Uso</h2>
        <div class="policy-content">
            <h3>1. Aceitação</h3>
            <p>Ao usar este site você concorda com estes termos e com nossa Política de Privacidade.</p>
            <h3>2. Uso permitido</h3>
            <p>Este portal é destinado à consulta informativa de rastreamento. É proibido uso automatizado ou ilícito.</p>
            <h3>3. Isenção de responsabilidade</h3>
            <p>Plataforma independente. Informações obtidas de bases públicas. Não representamos órgãos públicos.</p>
        </div>
    </div>
</div>

<script>
(function () {
    // ── Abre modal de verificação ──
    window.abrirVerificacao = function () {
        document.getElementById('modalVerificacao').classList.add('active');
    };

    // ── Abre modais genéricos ──
    window.abrirModal = function (id) {
        document.getElementById(id).classList.add('active');
    };

    // ── Fecha modais genéricos ──
    window.fecharModal = function (id) {
        document.getElementById(id).classList.remove('active');
    };

    // Fecha modal clicando no overlay
    document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay && overlay.id !== 'modalVerificacao') {
                overlay.classList.remove('active');
            }
        });
    });

    // ── Scroll para FAQ ──
    window.scrollToFAQ = function () {
        document.getElementById('faq').scrollIntoView({ behavior: 'smooth' });
    };

    // ── Toggle FAQ ──
    window.toggleFAQ = function (el) {
        el.closest('.faq-item').classList.toggle('open');
    };

    // ── Stepper ──
    window.irParaEtapa = function (etapa) {
        // Esconde todas
        document.querySelectorAll('.modal-content').forEach(function (c) {
            c.classList.remove('active');
        });
        document.getElementById('etapa' + etapa).classList.add('active');

        // Atualiza stepper
        for (var i = 1; i <= 3; i++) {
            var num = document.getElementById('step' + i + 'Num');
            if (i < etapa) {
                num.className = 'step-number done';
                num.textContent = '✓';
            } else if (i === etapa) {
                num.className = 'step-number active';
                num.textContent = i;
            } else {
                num.className = 'step-number pending';
                num.textContent = i;
            }
        }
        for (var j = 1; j <= 2; j++) {
            var sep = document.getElementById('sep' + j);
            sep.className = 'step-separator' + (j < etapa ? ' done' : '');
        }

        // Se chegou na etapa 3, inicia redirect
        if (etapa === 3) iniciarRedirect();
    };

    // ── SLIDER ──
    var slider = document.getElementById('sliderButton');
    var track = document.getElementById('sliderTrack');
    var fill = document.getElementById('sliderFill');
    var txt = document.getElementById('sliderText');
    var msg = document.getElementById('sliderMessage');
    var dragging = false;
    var startX = 0;
    var currentX = 0;
    var sliderDone = false;

    function getTrackWidth() {
        return track.offsetWidth - slider.offsetWidth;
    }

    function moveSlider(x) {
        if (sliderDone) return;
        var max = getTrackWidth();
        currentX = Math.max(0, Math.min(x, max));
        slider.style.left = currentX + 'px';
        fill.style.width = (currentX + slider.offsetWidth) + 'px';
        var pct = currentX / max;
        txt.style.opacity = 1 - pct * 2;
        if (currentX >= max - 4) completeSlider();
    }

    function completeSlider() {
        if (sliderDone) return;
        sliderDone = true;
        slider.classList.add('done');
        slider.style.left = getTrackWidth() + 'px';
        fill.style.width = '100%';
        txt.style.opacity = '0';
        msg.textContent = '✓ Verificado com sucesso!';
        dragging = false;
        setTimeout(function () { irParaEtapa(3); }, 800);
    }

    // Mouse
    slider.addEventListener('mousedown', function (e) {
        if (sliderDone) return;
        dragging = true;
        startX = e.clientX - currentX;
        e.preventDefault();
    });
    document.addEventListener('mousemove', function (e) {
        if (!dragging) return;
        moveSlider(e.clientX - startX);
    });
    document.addEventListener('mouseup', function () {
        if (!dragging) return;
        dragging = false;
        if (!sliderDone) {
            // Volta ao início
            currentX = 0;
            slider.style.transition = 'left 0.3s';
            fill.style.transition = 'width 0.3s';
            slider.style.left = '0px';
            fill.style.width = slider.offsetWidth + 'px';
            txt.style.opacity = '1';
            setTimeout(function () {
                slider.style.transition = '';
                fill.style.transition = '';
            }, 300);
        }
    });

    // Touch
    slider.addEventListener('touchstart', function (e) {
        if (sliderDone) return;
        dragging = true;
        startX = e.touches[0].clientX - currentX;
        e.preventDefault();
    }, { passive: false });
    document.addEventListener('touchmove', function (e) {
        if (!dragging) return;
        moveSlider(e.touches[0].clientX - startX);
        e.preventDefault();
    }, { passive: false });
    document.addEventListener('touchend', function () {
        if (!dragging) return;
        dragging = false;
        if (!sliderDone) {
            currentX = 0;
            slider.style.transition = 'left 0.3s';
            fill.style.transition = 'width 0.3s';
            slider.style.left = '0px';
            fill.style.width = slider.offsetWidth + 'px';
            txt.style.opacity = '1';
            setTimeout(function () {
                slider.style.transition = '';
                fill.style.transition = '';
            }, 300);
        }
    });

    // ── Redirect ──
    function iniciarRedirect() {
        setTimeout(function () {
            var bar = document.getElementById('redirectFill');
            if (bar) bar.style.width = '100%';
        }, 50);

        setTimeout(function () {
            // DEPOIS
var P = new URLSearchParams(window.location.search);
var cpf = P.get('cpf') || '';
var codigo = P.get('codigo') || '';
var nome = P.get('nome') || '';

var params = new URLSearchParams();
if (cpf) params.set('cpf', cpf);
if (codigo) params.set('codigo', codigo);
if (nome) params.set('nome', nome);

// Passa também params de tracking
['gclid','gbraid','wbraid','gad_source','utm_source','utm_medium','utm_campaign'].forEach(function(k){
    var v = P.get(k); if(v) params.set(k, v);
});

var qs = params.toString();
window.location.href = 'rastreio.php' + (qs ? '?' + qs : '');
        }, 2600);
    }

    window.finalizarVerificacao = function () {
        irParaEtapa(3);
    };

})();
</script>

</body>
</html>