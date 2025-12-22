<x-admin-layout :breadcrumbs="[
        ['name' => __('Dashboard'), 'icon' => 'fa-solid fa-wrench', 'route' => route('admin.dashboard')],
        ['name' => __('Devices'), 'icon' => 'fa-solid fa-hard-drive', 'route' => route('admin.devices.index')],
        ['name' => __('Monthly downloads'), 'icon' => 'fa-solid fa-download'],
    ]">

    <x-slot name="action">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.devices.index') }}"
                class="hidden lg:block w-full sm:w-auto justify-center items-center text-white bg-gray-600 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm px-4 py-2 text-center">
        <i class="fa-solid fa-arrow-left mr-1.5"></i>
                {{ __('Go back') }}
            </a>
            <button type="button" data-modal-target="create-momently-report-modal" data-modal-toggle="create-momently-report-modal"
                class="hidden lg:block w-full sm:w-auto justify-center items-center text-white {{ Auth::user()?->area === 'DTH'
    ? 'bg-secondary-700 hover:bg-secondary-800 focus:ring-4 focus:ring-secondary-300 dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-800'
    : 'bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800' }} font-medium rounded-lg text-sm px-5 py-2 focus:outline-none shadow-xl">
                <i class="fas fa-calendar mr-2"></i>
                {{ __('Monthly downloads report') }}
            </button>
        </div>
    </x-slot>
    <a href="{{ route('admin.devices.index') }}"
        class="mb-4 lg:hidden block w-full sm:w-auto justify-center items-center text-white bg-gray-600 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm px-4 py-2 text-center">
        <i class="fa-solid fa-arrow-left mr-1.5"></i>
        {{ __('Go back') }}
    </a>
    <button type="button" data-modal-target="create-momently-report-modal" data-modal-toggle="create-momently-report-modal"
        class="block lg:hidden w-full sm:w-auto justify-center items-center text-white {{ Auth::user()?->area === 'DTH'
    ? 'bg-secondary-700 hover:bg-secondary-800 focus:ring-4 focus:ring-secondary-300 dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-800'
    : 'bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800' }} font-medium rounded-lg text-sm px-5 py-2 focus:outline-none shadow-xl">
        <i class="fas fa-calendar mr-2"></i>
        {{ __('Monthly downloads report') }}
    </button>

    <div id="create-momently-report-modal" tabindex="-1"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-7xl max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white truncate">
                        <i class="fas fa-calendar mr-2 {{ Auth::user()?->area === 'DTH' ? 'text-secondary-600' : 'text-primary-600' }}"></i>
                        {{ __('Report monthly downloads per device') }}
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="create-momently-report-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    @livewire('admin.devices.downloads.monthly-downloads-report')
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto py-6">

        {{-- @livewire('admin.devices.downloads.download-graph') --}}

        @livewire('admin.devices.downloads.download-history-table')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        (function () {
            const ctx = document.getElementById('monthlyDownloadsChart').getContext('2d');

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

            const labels = getLastMonths(7);
            const data = {
                labels: labels,
                datasets: [{
                    label: '{{ __('Downloads') }}',
                    data: [65, 59, 80, 81, 56, 55, 40],
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

            const chart = new Chart(ctx, config);
            const pieCtx = document.getElementById('pieDownloadsChart')?.getContext('2d');
            let pieChart = null;
            if (pieCtx) {
                const pieData = {
                    labels: [
                        'Red',
                        'Blue',
                        'Yellow'
                    ],
                    datasets: [{
                        label: 'My First Dataset',
                        data: [300, 50, 100],
                        backgroundColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)'
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

            window.updateMonthlyDownloads = function (payload) {
                if (payload) {
                    document.getElementById('chart-loading')?.classList.add('hidden');
                    if (Array.isArray(payload.series)) {
                        chart.data.datasets[0].data = payload.series.slice(0, 12);
                        chart.update();
                    }
                    if (typeof payload.total !== 'undefined') document.getElementById('kpi-total').textContent = payload.total;
                    if (typeof payload.average !== 'undefined') document.getElementById('kpi-average').textContent = payload.average;
                    if (typeof payload.top !== 'undefined') document.getElementById('kpi-top').textContent = payload.top;
                    if (pieChart && Array.isArray(payload.pie)) {
                        pieChart.data.datasets[0].data = payload.pie.slice(0, 3);
                        if (Array.isArray(payload.pieLabels) && payload.pieLabels.length) pieChart.data.labels = payload.pieLabels.slice(0, 3);
                        pieChart.update();
                    }
                }
            }

            document.getElementById('select-year').addEventListener('change', function () {
                document.getElementById('chart-loading')?.classList.remove('hidden');
                setTimeout(() => {
                    const demoSeries = Array.from({ length: labels.length }, () => Math.floor(Math.random() * 120));
                    const total = demoSeries.reduce((a, b) => a + b, 0);
                    const avg = Math.round(total / labels.length);
                    const topIndex = demoSeries.indexOf(Math.max(...demoSeries));
                    const months = labels;
                    window.updateMonthlyDownloads({ series: demoSeries, total: total, average: avg, top: months[topIndex], pie: [300, 50, 100], pieLabels: ['Red', 'Blue', 'Yellow'] });
                }, 600);
            });

            document.getElementById('select-device')?.addEventListener('change', function () {
                document.getElementById('select-year').dispatchEvent(new Event('change'));
            });
        })();
    </script>

</x-admin-layout>
