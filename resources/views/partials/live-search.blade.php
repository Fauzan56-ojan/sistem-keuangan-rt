<script>
(function () {
    const input = document.querySelector('[data-live-search]');
    if (!input) return;

    let timer = null;
    let controller = null;

    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(liveSearch, 300);
    });

    function liveSearch() {
        const keyword = input.value.trim();
        const url = new URL(window.location.href);

        if (keyword) {
            url.searchParams.set('search', keyword);
        } else {
            url.searchParams.delete('search');
        }

        // hasil pencarian selalu mulai dari halaman pertama
        url.searchParams.delete('page');

        if (controller) controller.abort();
        controller = new AbortController();

        fetch(url.toString(), {
            signal: controller.signal,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (res) {
            if (res.redirected) {
                window.location.href = res.url;
                return null;
            }

            if (!res.ok) return null;

            return res.text();
        })
        .then(function (html) {
            if (html === null) return;

            const doc = new DOMParser().parseFromString(html, 'text/html');
            const targets = document.querySelectorAll('[data-live-target]');

            if (!targets.length) return;

            let valid = true;

            targets.forEach(function (el) {
                const key = el.getAttribute('data-live-target');
                if (!doc.querySelector('[data-live-target="' + key + '"]')) {
                    valid = false;
                }
            });

            if (!valid) return;

            targets.forEach(function (el) {
                const key = el.getAttribute('data-live-target');
                const next = doc.querySelector('[data-live-target="' + key + '"]');

                el.innerHTML = next.innerHTML;
                el.className = next.className;
            });

            // jaga state pencarian di modal filter tetap sinkron
            document.querySelectorAll('input[type="hidden"][name="search"]').forEach(function (el) {
                el.value = keyword;
            });

            window.history.replaceState({}, '', url.toString());

            if (typeof window.afterLiveSearch === 'function') {
                window.afterLiveSearch();
            }
        })
        .catch(function (err) {
            if (err && err.name !== 'AbortError') {
                console.warn('Live search:', err);
            }
        });
    }
})();
</script>
