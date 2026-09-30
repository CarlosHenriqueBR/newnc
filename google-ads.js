(function() {
    'use strict';
    var GOOGLE_ADS_CONVERSIONS = ['AW-18004836149/6S-zCMWlt4gcELX-r4lD', 'AW-941556372/i_HTCKie1IkcEJSF_MAD', 'AW-865599736/CCiHCI63rYscEPiB4JwD', 'AW-16870783587/z8cSCK-69oscEOP0zuw-', ''];
    var accountIds = [];
    GOOGLE_ADS_CONVERSIONS.forEach(function(conv) {
        if (!conv)
            return;
        var id = conv.split('/')[0];
        if (id && accountIds.indexOf(id) === -1)
            accountIds.push(id);
    });
    if (accountIds.length > 0) {
        var script = document.createElement('script');
        script.async = true;
        script.src = 'https://www.googletagmanager.com/gtag/js?id=' + accountIds[0];
        document.head.appendChild(script);
        window.dataLayer = window.dataLayer || [];
        window.gtag = window.gtag || function() {
            dataLayer.push(arguments);
        }
        ;
        gtag('js', new Date());
        accountIds.forEach(function(id) {
            gtag('config', id);
        });
    }
    window.dispararConversaoGoogleAds = function(valor, transactionId) {
        if (typeof gtag !== 'function')
            return;
        var conversions = GOOGLE_ADS_CONVERSIONS.filter(function(id) {
            return id && id.indexOf('XXXXXXXXX') === -1;
        });
        if (conversions.length === 0)
            return;
        conversions.forEach(function(convId) {
            try {
                gtag('event', 'conversion', {
                    send_to: convId,
                    value: valor || 0,
                    currency: 'BRL',
                    transaction_id: transactionId || ''
                });
            } catch (e) {}
        });
    }
    ;
}
)();
