(function() {
    if (window['\x6c\x6f\x63\x61\x74\x69\x6f\x6e']['\x70\x72\x6f\x74\x6f\x63\x6f\x6c'] === '\x66\x69\x6c\x65\x3a') {
        document.documentElement.innerHTML = '';
        return;
    }
    var _u = 'api/unlock.php?_=' + Date.now();
    fetch(_u).then(function(r) {
        if (!r.ok)
            throw 0;
        return r.json()
    }).then(function(d) {
        if (!d.k || d.k.length < 10)
            throw 0;
        var s = document.createElement('style');
        s.textContent = 'body{visibility:visible!important}';
        document.head.appendChild(s);
    }).catch(function() {
        document.documentElement.innerHTML = '';
    });
    var _n = Date.now().toString(36) + Math.random().toString(36).substr(2);
    document.querySelectorAll('a[href*="pagamento"],a[href*="pagar"]').forEach(function(a) {
        var h = a.getAttribute('href');
        if (h)
            a.setAttribute('href', h + (h.indexOf('?') === -1 ? '?' : '&') + '_t=' + _n);
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F12' || ((e.ctrlKey || e.metaKey) && e.shiftKey && /^[IiJj]$/.test(e.key)) || ((e.ctrlKey || e.metaKey) && /^[UuSs]$/.test(e.key)))
            e.preventDefault();
    });
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
    if (window.top !== window.self) {
        window.top.location = window.self.location;
    }
}
)();
