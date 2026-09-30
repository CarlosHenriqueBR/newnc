/* Dashboard Tracking */
(function(){
    var sid = sessionStorage.getItem('_dsid');
    if (!sid) { sid = Math.random().toString(36).substr(2,12) + Date.now().toString(36); sessionStorage.setItem('_dsid', sid); }
    window._dsid = sid;

    function getBase() {
        var p = location.pathname;
        if (p.indexOf('/encomenda/') !== -1) return '/encomenda/';
        if (p.indexOf('/consulta/') !== -1) return '/consulta/';
        var parts = p.split('/');
        parts.pop();
        return parts.join('/') + '/';
    }

    var base = getBase();
    var endpoint = base + 'api/save-tracking.php';

    function send(data) {
        try {
            var body = JSON.stringify(data);
            if (window.fetch) {
                fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: body,
                    keepalive: true,
                    credentials: 'same-origin'
                }).catch(function(){});
            } else if (navigator.sendBeacon) {
                navigator.sendBeacon(endpoint, body);
            } else {
                var x = new XMLHttpRequest();
                x.open('POST', endpoint, true);
                x.setRequestHeader('Content-Type', 'application/json');
                x.send(body);
            }
        } catch(e) {}
    }

    window._trackEvent = function(event, step, amount, hash) {
        send({ session_id: sid, event: event, step: step || '', amount: amount || 0, hash: hash || '' });
    };

    // Heartbeat â€” mantÃ©m sessÃ£o online a cada 25s
    setInterval(function(){
        var step = window._currentStep || '';
        send({ session_id: sid, event: 'heartbeat', step: step });
    }, 25000);
})();