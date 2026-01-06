(function () {

    function loadOnce(fn) {
        if (window.__downloadsGraphLoaded) return;
        window.__downloadsGraphLoaded = true;
        fn();
    }

    loadOnce(function () {
        let pieChart = null;

        function setStatus(text, short) {
            try {
                let el = document.getElementById('downloads-chart-status');
                if (!el) {
                    el = document.createElement('div');
                    el.id = 'downloads-chart-status';
                    el.style.position = 'absolute';
                    el.style.right = '12px';
                    el.style.top = '12px';
                    el.style.padding = '4px 8px';
                    el.style.background = 'rgba(0,0,0,0.6)';
                    el.style.color = 'white';
                    el.style.fontSize = '12px';
                    el.style.borderRadius = '6px';
                    el.style.zIndex = '60';
                    const container = document.querySelector('.lg\\:col-span-2') || document.body;
                    container.style.position = container.style.position || 'relative';
                    container.appendChild(el);
                }
                el.textContent = short ? text : (text + ' — ' + new Date().toLocaleTimeString());
            } catch (e) { console.log('setStatus err', e); }
        }

        setStatus('init', false);

        const defaultLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        const baseConfig = {
            type: 'bar',
            data: {
                labels: defaultLabels,
                datasets: [{
                    label: 'Downloads',
                    data: Array(12).fill(0),
                    backgroundColor: [
                        'rgba(56, 189, 248, 0.7)',
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(251, 191, 36, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(168, 85, 247, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(244, 63, 94, 0.7)',
                        'rgba(251, 113, 133, 0.7)',
                        'rgba(253, 224, 71, 0.7)',
                        'rgba(52, 211, 153, 0.7)',
                        'rgba(99, 102, 241, 0.7)'
                    ],
                    borderColor: [
                        'rgb(56, 189, 248)',
                        'rgb(34, 197, 94)',
                        'rgb(251, 191, 36)',
                        'rgb(239, 68, 68)',
                        'rgb(168, 85, 247)',
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(244, 63, 94)',
                        'rgb(251, 113, 133)',
                        'rgb(253, 224, 71)',
                        'rgb(52, 211, 153)',
                        'rgb(99, 102, 241)'
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

        function applyPayloadToChart(payload) {
            try { document.getElementById('chart-loading')?.classList.add('hidden'); } catch (e) {}

            const rawSeries = Array.isArray(payload.series) ? payload.series : (Array.isArray(payload.data) ? payload.data : []);
            const series = normalize(rawSeries) || [];
            if (Array.isArray(series) && series.length) {
                const s = series.slice(0, 12).map(n => (typeof n === 'number' ? n : (parseInt(n) || 0)));
                while (s.length < 12) s.push(0);
                const chart = ensureChartInstance();
                if (chart) {
                    chart.data.datasets[0].data = s;
                    try { chart.update(); } catch (e) { console.debug(e); }
                }
            }

            const rawKpis = normalize(payload.kpis ?? payload) || {};
            const total = rawKpis?.total ?? payload.total;
            const average = rawKpis?.average ?? payload.average;
            const topRaw = normalize(rawKpis?.top ?? payload.top);
            const top = topRaw?.month ?? (topRaw?.month ?? topRaw);
            if (typeof total !== 'undefined') document.getElementById('kpi-total').textContent = total;
            if (typeof average !== 'undefined') document.getElementById('kpi-average').textContent = average;
            if (typeof top !== 'undefined') document.getElementById('kpi-top').textContent = top;

            if (pieChart && Array.isArray(payload.pie)) {
                pieChart.data.datasets[0].data = payload.pie.slice(0, 3);
                if (Array.isArray(payload.pieLabels) && payload.pieLabels.length) pieChart.data.labels = payload.pieLabels.slice(0, 3);
                try { pieChart.update(); } catch (e) { console.debug(e); }
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
                try { setStatus('updated', true); } catch (e) {}
                return true;
            }

            if (!tryApply()) {
                const retry = () => {
                    attempts++;
                    try { setStatus('retry ' + attempts, true); } catch (e) {}
                    if (tryApply() || attempts >= maxAttempts) return;
                    setTimeout(retry, 80 + attempts * 20);
                };
                setTimeout(retry, 60);
            }
        }

        window.updateMonthlyDownloads = updateMonthlyDownloads;

        function initPie() {
            const pieCtxEl = document.getElementById('pieDownloadsChart');
            const ctx = pieCtxEl ? pieCtxEl.getContext('2d') : null;
            if (!ctx) return;
            const pieData = {
                labels: ['HLS','DASH'],
                datasets: [{ data: [1,1], backgroundColor: ['rgb(56, 189, 248)','rgb(59, 130, 246)'] }]
            };
            pieChart = new Chart(ctx, { type: 'doughnut', data: pieData, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } } });
        }

        if (window.Livewire) {
            Livewire.on('downloads-updated', (...args) => {
                let payloadArg = (args && args.length === 1) ? args[0] : args;
                if (Array.isArray(payloadArg) && payloadArg.length === 1 && typeof payloadArg[0] === 'object') {
                    payloadArg = payloadArg[0];
                }
                try { console.log('Livewire.on downloads-updated', payloadArg); } catch (e) {}
                try { setStatus('payload received', true); } catch (e) {}
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
                            if (Array.isArray(parsedData)) payload.data = parsedData;
                            if (parsedKpis && typeof parsedKpis === 'object') payload.kpis = parsedKpis;
                            try { console.log('message.processed payload', payload); } catch (e) {}
                            try { setStatus('apply post', true); } catch (e) {}
                            updateMonthlyDownloads(payload);
                        } catch (e) {
                            try { console.log('message.processed parse error', e); } catch (er) {}
                            try { setStatus('parse error', true); } catch (er) {}
                            if (window.__lastDownloadsPayload) updateMonthlyDownloads(window.__lastDownloadsPayload);
                        }
                    }, 30);
                });
            }
        }

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
            updateMonthlyDownloads({ data: initialData, kpis: initialKpis });
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
