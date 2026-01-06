<div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div
                class="flex items-center bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded px-3 py-1 shadow-sm">
                <i class="fa-solid fa-calendar-days text-gray-400 mr-2"></i>
                <label for="select-year" class="text-sm text-gray-700 dark:text-gray-200 mr-2">{{ __('Year')
                        }}</label>
                <select id="select-year" wire:model="selectedYear" wire:change="$set('selectedYear', $event.target.value)" class="border-0 bg-transparent text-sm focus:outline-none">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            @if(isset($devices) && $devices->count())
                <div
                    class="flex items-center bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded px-3 py-1 shadow-sm">
                    <i class="fa-solid fa-hard-drive text-gray-400 mr-2"></i>
                    <label for="select-device" class="text-sm text-gray-700 dark:text-gray-200 mr-2">{{ __('Device')
                            }}</label>
                    <select id="select-device" class="border-0 bg-transparent text-sm focus:outline-none">
                        <option value="">{{ __('All devices') }}</option>
                        @foreach($devices as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-4 h-[336px] relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-lg font-semibold">{{ __('Downloads - Yearly view') }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ __('Shows monthly downloads for the selected device and
                            year') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div id="chart-loading" class="text-sm text-gray-500 hidden">{{ __('Loading...') }}</div>
                    <button id="btn-refresh"
                        class="text-sm text-gray-600 bg-gray-50 hover:bg-gray-100 px-2 py-1 rounded shadow-sm">
                        <i class="fa-solid fa-arrow-rotate-right"></i>
                    </button>
                </div>
            </div>
            <div class="w-full h-full">
                <canvas id="monthlyDownloadsChart" class="w-full h-full block mb-16"></canvas>
            </div>
        </div>

        <div class="flex flex-col space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex-1 flex flex-col">
                <h3 class="text-sm text-gray-500 flex items-center gap-2"><i
                        class="fa-solid fa-chart-pie text-gray-400"></i>{{ __('Distribution') }}</h3>
                <div class="w-full flex-1 mt-2 flex items-center justify-center">
                    <canvas id="pieDownloadsChart" class="w-40 h-40"></canvas>
                </div>
                <div class="mt-3 text-xs text-gray-500 text-center">{{ __('Lorem ipsum dolor sit amet.') }}</div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 grid grid-cols-3 gap-3 text-center">
                <div>
                    <h4 class="text-xs text-gray-500">{{ __('Total (year)') }}</h4>
                    <div class="text-xl font-bold mt-1" id="kpi-total">0</div>
                </div>
                <div>
                    <h4 class="text-xs text-gray-500">{{ __('Average / month') }}</h4>
                    <div class="text-xl font-bold mt-1" id="kpi-average">0</div>
                </div>
                <div>
                    <h4 class="text-xs text-gray-500">{{ __('Top month') }}</h4>
                    <div class="text-xl font-bold mt-1" id="kpi-top">—</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @php
        $__initialDownloadsData = $monthlyData ?? array_fill(0, 12, 0);
        $__initialDownloadsKpis = $kpis ?? ['total' => 0, 'average' => 0, 'top' => ['month' => '—', 'value' => 0]];
    @endphp

    <script type="application/json" id="initialDownloadsData">{!! json_encode($__initialDownloadsData) !!}</script>
    <script type="application/json" id="initialDownloadsKpis">{!! json_encode($__initialDownloadsKpis) !!}</script>

    <script>
        (function () {
            function initMonthlyDownloads() {
                const canvas = document.getElementById('monthlyDownloadsChart');
                if (!canvas) {
                    return setTimeout(initMonthlyDownloads, 50);
                }
                let ctx;
                try {
                    ctx = canvas.getContext('2d');
                } catch (e) {
                    console.error('Chart init error', e);
                    return;
                }

                function getLastMonths(count) {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const result = [];
                const now = new Date();
                for (let i = count - 1; i >= 0; i--) {
                    const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
                    result.push(months[d.getMonth()]);
                }
                return result;
            }

            const labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            const data = {
                labels: labels,
                datasets: [{
                    label: '{{ __('Downloads') }}',
                    data: Array(12).fill(0),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            };

                window.downloadsChart = new Chart(ctx, config);
                const pieCtx = document.getElementById('pieDownloadsChart')?.getContext('2d');
            let pieChart = null;
            if (pieCtx) {
                const pieData = {
                    labels: [
                        'HLS',
                        'DASH'
                    ],
                    datasets: [{
                        label: 'My First Dataset',
                        data: [300, 50],
                        backgroundColor: [
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)',
                        ],
                        hoverOffset: 4
                    }]
                };

                pieChart = new Chart(pieCtx, {
                    type: 'doughnut',
                    data: pieData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }

            function ensureDownloadsChart() {
                const canvas = document.getElementById('monthlyDownloadsChart');
                if (!canvas) return null;

                if (window.downloadsChart && window.downloadsChart.canvas === canvas) {
                    return window.downloadsChart;
                }

                const seedData = (window.__lastDownloadsPayload && Array.isArray(window.__lastDownloadsPayload.data))
                    ? window.__lastDownloadsPayload.data.slice(0, 12)
                    : (window.downloadsChart && window.downloadsChart.data && window.downloadsChart.data.datasets[0]
                        ? window.downloadsChart.data.datasets[0].data.slice(0, 12)
                        : Array(12).fill(0));

                if (window.downloadsChart) {
                    try { window.downloadsChart.destroy(); } catch (e) { console.debug(e); }
                    window.downloadsChart = null;
                }

                const ctx2 = canvas.getContext('2d');
                const cfg = JSON.parse(JSON.stringify(config));
                cfg.data.datasets[0].data = seedData.concat();
                window.downloadsChart = new Chart(ctx2, cfg);
                return window.downloadsChart;
            }

            window.updateMonthlyDownloads = function (payload) {
                console.debug('updateMonthlyDownloads payload:', payload);
                const chartInstance = ensureDownloadsChart();
                if (!chartInstance) return;
                if (payload) {
                    document.getElementById('chart-loading')?.classList.add('hidden');
                    function normalize(value) {
                        if (!value) return value;
                        if (Array.isArray(value) && value.length === 2 && Array.isArray(value[0]) && value[1] && value[1].s === 'arr') {
                            return value[0];
                        }
                        if (Array.isArray(value) && value.length === 2 && typeof value[0] === 'object' && value[1] && value[1].s === 'arr') {
                            return value[0];
                        }
                        return value;
                    }

                    const rawSeries = Array.isArray(payload.series) ? payload.series : (Array.isArray(payload.data) ? payload.data : []);
                    const series = normalize(rawSeries);
                    if (Array.isArray(series) && series.length) {
                        const s = series.slice(0, 12).map(n => (typeof n === 'number' ? n : (parseInt(n) || 0)));
                        while (s.length < 12) s.push(0);
                        console.debug('setting chart data to', s);
                        chartInstance.data.datasets[0].data = s;
                        chartInstance.update();
                    }

                    const rawKpis = normalize(payload.kpis ?? payload);
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
                        pieChart.update();
                    }
                }
            }

            if (window.Livewire) {
                Livewire.on('downloads-updated', (payload) => {
                    console.debug('Livewire.on downloads-updated received', payload);
                    window.__lastDownloadsPayload = payload || {};
                    try { window.updateMonthlyDownloads(window.__lastDownloadsPayload); } catch (e) { console.debug(e); }
                });

                if (Livewire.hook) {
                    Livewire.hook('message.processed', (message, component) => {
                        if (window.__lastDownloadsPayload) {
                            setTimeout(() => {
                                console.debug('Livewire.message.processed applying lastDownloadsPayload');
                                try { window.updateMonthlyDownloads(window.__lastDownloadsPayload); } catch (e) { console.debug(e); }
                            }, 20);
                        }
                    });
                }
            }

            window.addEventListener('downloads-updated', (e) => {
                console.debug('browser event downloads-updated received', e.detail);
                const payload = e?.detail ?? {};
                window.__lastDownloadsPayload = payload;
                try { window.updateMonthlyDownloads(payload); } catch (e) { console.debug(e); }
            });

            try {
                const initialDataEl = document.getElementById('initialDownloadsData');
                const initialKpisEl = document.getElementById('initialDownloadsKpis');
                const initialData = initialDataEl ? JSON.parse(initialDataEl.textContent) : Array(12).fill(0);
                const initialKpis = initialKpisEl ? JSON.parse(initialKpisEl.textContent) : { total: 0, average: 0, top: { month: '—', value: 0 } };
                console.debug('initialPayload', { data: initialData, kpis: initialKpis });
                window.updateMonthlyDownloads({ data: initialData, kpis: initialKpis });
            } catch (e) { console.error(e); }
            }

            initMonthlyDownloads();
        })();
    </script>
@endpush
