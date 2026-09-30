/**
 * Tracking.js — Persiste gclid e tracking params no localStorage
 * Garante que o gclid sobrevive entre páginas e reloads
 */
(function(){
    var TRACK_KEY = 'correios_tracking';
    var keys = ['gclid','gbraid','wbraid','gad_source','gad_campaignid','utm_source','utm_medium','utm_campaign'];
    var p = new URLSearchParams(window.location.search);

    // Load existing
    var saved = {};
    try { saved = JSON.parse(localStorage.getItem(TRACK_KEY) || '{}'); } catch(e) {}

    // Update with URL params (URL takes priority)
    var changed = false;
    keys.forEach(function(k) {
        var v = p.get(k);
        if (v) { saved[k] = v; changed = true; }
    });

    // Save if any new params found
    if (changed) {
        saved._ts = Date.now();
        localStorage.setItem(TRACK_KEY, JSON.stringify(saved));
    }

    // Generate/restore session ID
    var SID_KEY = 'correios_session_id';
    var sid = localStorage.getItem(SID_KEY);
    if (!sid) {
        sid = Math.random().toString(36).substr(2) + Date.now().toString(36);
        localStorage.setItem(SID_KEY, sid);
    }
    window._dsid = sid;

    // Track event helper
    window._trackEvent = function(event, step) {
        var payload = {
            session_id: sid,
            event: event,
            step: step || '',
            gclid: saved.gclid || ''
        };
        try {
            if (navigator.sendBeacon) {
                navigator.sendBeacon('api/save-tracking.php', JSON.stringify(payload));
            } else {
                fetch('api/save-tracking.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload),
                    keepalive: true
                }).catch(function(){});
            }
        } catch(e) {}
    };
})();
