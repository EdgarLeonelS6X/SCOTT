(function () {

    function loadOnce(fn) {
        if (window.__downloadsGraphLoaded) return;
        window.__downloadsGraphLoaded = true;
        fn();
    }

    loadOnce(function () {
        let pieChart = null;

        function setStatus(state, show) {
            try {
                const el = document.getElementById('chart-loading');
                if (!el) return;
                if (show) {
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            } catch (e) { console.debug('setStatus error', e); }
        }

        setStatus('init', false);

        try {
            const oldBadge = document.getElementById('downloads-chart-status');
            if (oldBadge && oldBadge.parentNode) oldBadge.parentNode.removeChild(oldBadge);
        } catch (e) { }

        const defaultLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        const baseConfig = {
            type: 'bar',
            data: {
                labels: defaultLabels,
                datasets: [{
                    label: 'Downloads',
                    data: Array(12).fill(0),
                    backgroundColor: [
                        'rgba(6, 182, 212, 0.85)',
                        'rgba(59, 130, 246, 0.85)',
                        'rgba(99, 102, 241, 0.85)',
                        'rgba(139, 92, 246, 0.85)',
                        'rgba(168, 85, 247, 0.85)',
                        'rgba(236, 72, 153, 0.85)',
                        'rgba(239, 68, 68, 0.85)',
                        'rgba(249, 115, 22, 0.85)',
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(34, 197, 94, 0.85)',
                        'rgba(16, 185, 129, 0.85)',
                        'rgba(14, 165, 233, 0.85)'
                    ],
                    borderColor: [
                        'rgb(6, 182, 212)',
                        'rgb(59, 130, 246)',
                        'rgb(99, 102, 241)',
                        'rgb(139, 92, 246)',
                        'rgb(168, 85, 247)',
                        'rgb(236, 72, 153)',
                        'rgb(239, 68, 68)',
                        'rgb(249, 115, 22)',
                        'rgb(245, 158, 11)',
                        'rgb(34, 197, 94)',
                        'rgb(16, 185, 129)',
                        'rgb(14, 165, 233)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        };

        function getCanvas() {
            return document.getElementById('monthlyDownloadsChart');
        }

        function ensureChartInstance() {
            const canvas = getCanvas();
            if (!canvas) return null;

            if (window.downloadsChart && window.downloadsChart.canvas === canvas) return window.downloadsChart;

            const seedData = (window.__lastDownloadsPayload && Array.isArray(window.__lastDownloadsPayload.data))
                ? window.__lastDownloadsPayload.data.slice(0, 12)
                : (window.downloadsChart && window.downloadsChart.data && window.downloadsChart.data.datasets[0]
                    ? window.downloadsChart.data.datasets[0].data.slice(0, 12)
                    : Array(12).fill(0));

            if (window.downloadsChart) {
                try { window.downloadsChart.destroy(); } catch (e) { console.debug(e); }
                window.downloadsChart = null;
            }

            const ctx = canvas.getContext('2d');
            const cfg = JSON.parse(JSON.stringify(baseConfig));
            cfg.data.datasets[0].data = seedData.concat();
            window.downloadsChart = new Chart(ctx, cfg);
            return window.downloadsChart;
        }

        function normalize(value) {
            if (!value) return value;
            if (Array.isArray(value) && value.length === 2 && Array.isArray(value[0]) && value[1] && value[1].s === 'arr') return value[0];
            if (Array.isArray(value) && value.length === 2 && typeof value[0] === 'object' && value[1] && value[1].s === 'arr') return value[0];
            return value;
        }

        function objectToArrayLike(obj) {
            try {
                if (!obj || typeof obj !== 'object') return null;
                const out = [];
                for (let i = 0; i < 12; i++) {
                    if (Object.prototype.hasOwnProperty.call(obj, i)) {
                        out.push(Number(obj[i]) || 0);
                        continue;
                    }
                    const key1 = String(i + 1);
                    if (Object.prototype.hasOwnProperty.call(obj, key1)) {
                        out.push(Number(obj[key1]) || 0);
                        continue;
                    }
                    if (Object.prototype.hasOwnProperty.call(obj, String(i))) {
                        out.push(Number(obj[String(i)]) || 0);
                        continue;
                    }
                    out.push(0);
                }
                return out;
            } catch (e) { return null; }
        }

        function applyPayloadToChart(payload) {

            const rawSeries = (payload && (payload.series !== undefined ? payload.series : payload.data));
            let series = [];
            if (!rawSeries) series = [];
            else if (Array.isArray(rawSeries)) series = normalize(rawSeries) || rawSeries;
            else if (typeof rawSeries === 'object') series = objectToArrayLike(rawSeries) || [];
            else series = [];
            if (Array.isArray(series) && series.length) {
                const s = series.slice(0, 12).map(n => (typeof n === 'number' ? n : (parseInt(n) || 0)));
                while (s.length < 12) s.push(0);
                const chart = ensureChartInstance();
                if (chart) {
                    chart.data.datasets[0].data = s;
                    try { chart.update(); } catch (e) { console.debug(e); }
                    try { setStatus('loaded', false); } catch (e) { }
                }
            }

            if (pieChart && Array.isArray(payload.pie)) {
                pieChart.data.datasets[0].data = payload.pie.slice(0, 2);
                if (Array.isArray(payload.pieLabels) && payload.pieLabels.length) pieChart.data.labels = payload.pieLabels.slice(0, 2);
                try { pieChart.update(); } catch (e) { console.debug(e); }
                try { setStatus('loaded', false); } catch (e) { }
            }
        }

        function updateMonthlyDownloads(payload) {
            window.__lastDownloadsPayload = payload || {};
            let attempts = 0;
            const maxAttempts = 12;

            function tryApply() {
                const canvas = getCanvas();
                if (!canvas) return false;
                try { applyPayloadToChart(payload); } catch (e) { console.debug(e); }
                return true;
            }

            if (!tryApply()) {
                const retry = () => {
                    attempts++;
                    if (tryApply() || attempts >= maxAttempts) return;
                    setTimeout(retry, 80 + attempts * 20);
                };
                setTimeout(retry, 60);
            }
        }

        window.updateMonthlyDownloads = updateMonthlyDownloads;

        function initPie() {
            const pieCanvas = document.getElementById('pieDownloadsChart');
            if (!pieCanvas) return;
            if (pieChart && pieChart.canvas === pieCanvas) return;
            const ctx = pieCanvas.getContext('2d');
            const pieData = {
                labels: ['HLS', 'DASH'],
                datasets: [{
                    data: [1, 1], backgroundColor: [
                        '#06B6D4',
                        '#8B5CF6'
                    ]
                }]
            };
            try {
                if (pieChart) {
                    try { pieChart.destroy(); } catch (e) { console.debug('pie destroy', e); }
                    pieChart = null;
                }
            } catch (e) { }
            pieChart = new Chart(ctx, { type: 'doughnut', data: pieData, options: { responsive: true, maintainAspectRatio: true, aspectRatio: 1, plugins: { legend: { position: 'bottom' } } } });
        }

        function attachLivewireHandlers() {
            if (window.__downloadsGraphLWAttached) return;
            if (!window.Livewire) return;
            window.__downloadsGraphLWAttached = true;

            Livewire.on('downloads-updated', (...args) => {
                let payloadArg = (args && args.length === 1) ? args[0] : args;
                if (Array.isArray(payloadArg) && payloadArg.length === 1 && typeof payloadArg[0] === 'object') {
                    payloadArg = payloadArg[0];
                }
                try { console.log('Livewire.on downloads-updated', payloadArg); } catch (e) { }
                try { setStatus('payload received', false); } catch (e) { }
                updateMonthlyDownloads(payloadArg || {});
            });

            if (Livewire.hook) {
                Livewire.hook('message.processed', () => {
                    setTimeout(() => {
                        try {
                            const dataEl = document.getElementById('initialDownloadsData');
                            const kpisEl = document.getElementById('initialDownloadsKpis');
                            const parsedData = dataEl ? JSON.parse(dataEl.textContent) : null;
                            const parsedKpis = kpisEl ? JSON.parse(kpisEl.textContent) : null;
                            const payload = Object.assign({}, window.__lastDownloadsPayload || {});
                            if ((!payload.data || !Array.isArray(payload.data) || payload.data.length === 0) && Array.isArray(parsedData)) payload.data = parsedData;
                            if ((!payload.kpis || typeof payload.kpis !== 'object') && parsedKpis && typeof parsedKpis === 'object') payload.kpis = parsedKpis;
                            try { console.log('message.processed payload', payload); } catch (e) { }
                            try { setStatus('apply post', false); } catch (e) { }
                            updateMonthlyDownloads(payload);
                        } catch (e) {
                            try { console.log('message.processed parse error', e); } catch (er) { }
                            try { setStatus('parse error', false); } catch (er) { }
                            if (window.__lastDownloadsPayload) updateMonthlyDownloads(window.__lastDownloadsPayload);
                        }
                    }, 30);
                });
            }
        }

        try {
            if (window.Livewire) attachLivewireHandlers();
            else {
                let lwAttempts = 0;
                const lwMax = 30;
                const lwInterval = setInterval(() => {
                    lwAttempts++;
                    if (window.Livewire) {
                        clearInterval(lwInterval);
                        attachLivewireHandlers();
                        return;
                    }
                    if (lwAttempts >= lwMax) clearInterval(lwInterval);
                }, 200);
            }
        } catch (e) { console.debug('livewire attach poll error', e); }

        function observeInitialDataChanges() {
            try {
                const dataEl = document.getElementById('initialDownloadsData');
                const kpisEl = document.getElementById('initialDownloadsKpis');
                if (!dataEl && !kpisEl) return;

                const applyFromElements = () => {
                    try {
                        const parsedData = dataEl ? JSON.parse(dataEl.textContent) : null;
                        const parsedKpis = kpisEl ? JSON.parse(kpisEl.textContent) : null;
                        const payload = Object.assign({}, window.__lastDownloadsPayload || {});
                        if ((!payload.data || !Array.isArray(payload.data) || payload.data.length === 0) && Array.isArray(parsedData)) payload.data = parsedData;
                        if ((!payload.kpis || typeof payload.kpis !== 'object') && parsedKpis && typeof parsedKpis === 'object') payload.kpis = parsedKpis;
                        console.debug('observer apply payload', payload);
                        updateMonthlyDownloads(payload);
                    } catch (e) { console.debug('observer parse error', e); }
                };

                const obs = new MutationObserver((mutations) => {
                    for (const m of mutations) {
                        if (m.type === 'characterData' || m.type === 'childList') {
                            applyFromElements();
                            return;
                        }
                    }
                });

                if (dataEl) obs.observe(dataEl, { characterData: true, childList: true, subtree: true });
                if (kpisEl) obs.observe(kpisEl, { characterData: true, childList: true, subtree: true });

                applyFromElements();
            } catch (e) { console.debug('observeInitialDataChanges error', e); }
        }

        try { observeInitialDataChanges(); } catch (e) { console.debug(e); }

        try {
            document.addEventListener('change', function (ev) {
                try {
                    const id = ev?.target?.id;
                    if (id === 'select-device' || id === 'select-year') {
                        try { setStatus('loading', true); } catch (e) { }
                    }
                } catch (e) { }
            }, { passive: true });
        } catch (e) { console.debug('attach selector listener error', e); }

        window.addEventListener('downloads-updated', (e) => {
            try {
                const det = e?.detail ?? {};
                const payloadArg = (Array.isArray(det) && det.length === 1) ? det[0] : det;
                console.log('browser event downloads-updated', payloadArg);
                updateMonthlyDownloads(payloadArg || {});
            } catch (e) { console.debug(e); }
        });

        try {
            const initialDataEl = document.getElementById('initialDownloadsData');
            const initialKpisEl = document.getElementById('initialDownloadsKpis');
            const initialData = initialDataEl ? JSON.parse(initialDataEl.textContent) : Array(12).fill(0);
            const initialKpis = initialKpisEl ? JSON.parse(initialKpisEl.textContent) : { total: 0, average: 0, top: { month: '—', value: 0 } };
            try { setStatus('loaded', false); } catch (e) { }
            updateMonthlyDownloads({ data: initialData, kpis: initialKpis });
            try { setStatus('loaded', false); } catch (e) { }
        } catch (e) { console.log('initial payload parse error', e); }

        function initLoop() {
            const canvas = getCanvas();
            if (!canvas) return setTimeout(initLoop, 50);
            ensureChartInstance();
            initPie();
        }

        initLoop();
    });
})();
