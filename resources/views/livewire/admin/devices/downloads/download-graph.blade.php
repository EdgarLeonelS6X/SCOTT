<div>

@php
    $area = Auth::user()?->area;
    $selectRingClass = $area === 'OTT'
        ? 'focus-within:ring-2 focus-within:ring-primary-400 dark:focus-within:ring-primary-600'
        : ($area === 'DTH'
            ? 'focus-within:ring-2 focus-within:ring-secondary-400 dark:focus-within:ring-secondary-600'
            : 'focus-within:ring-2 focus-within:ring-primary-400 dark:focus-within:ring-primary-600');
@endphp
<style>
    @media (max-width: 640px) {
        #downloads-chart-panel #chart-loading:not(.hidden) {
            position: absolute !important;
            inset: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            z-index: 60 !important;
            background: transparent !important;
        }

        #downloads-chart-panel #chart-loading:not(.hidden) ~ * {
            display: none !important;
        }
    }
</style>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        <div id="downloads-chart-panel" class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4 flex flex-col min-h-[360px] md:min-h-[420px] relative">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-2 md:mb-4 gap-2 md:gap-4">
                <div class="flex-1 items-center min-w-0">
                    <h2 class="text-base md:text-lg font-semibold truncate text-gray-900 dark:text-gray-100">
                        <i class="fa-solid fa-download mr-2" aria-hidden="true"></i>
                        {{ __('Downloads - Yearly view') }}
                    </h2>
                    <p class="text-sm mt-1 truncate text-gray-600 dark:text-gray-400 mb-2 md:mb-0">
                        {{ __('Monthly downloads for the selected year.') }}
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full md:w-auto">
                    @php
                        $spinnerFillClass = $area === 'OTT' ? 'fill-primary-600' : ($area === 'DTH' ? 'fill-secondary-600' : 'fill-blue-600');
                    @endphp
                    <div id="chart-loading" class="text-sm text-gray-500 hidden" aria-hidden="true">
                        <div role="status" class="flex items-center gap-2 mr-0.5">
                            <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 {{ $spinnerFillClass }}" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                            </svg>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
                        @if(isset($devices) && $devices->count())
                            <div class="inline-flex items-center rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-700 px-3 py-1.5 sm:py-1 shadow-sm {{ $selectRingClass }} w-full sm:w-auto">
                                <i class="fa-solid fa-hard-drive text-gray-400 mx-2" aria-hidden="true"></i>
                                <select id="select-device" wire:model="selectedDevice" wire:change="$set('selectedDevice', $event.target.value)" class="appearance-none bg-transparent border-0 pl-2 pr-6 text-sm font-semibold text-gray-700 dark:text-gray-100 focus:outline-none cursor-pointer w-full sm:w-[220px] focus:ring-0 focus:border-0 truncate leading-tight" aria-label="{{ __('Select device') }}">
                                    <option style="color:#1f2937;" class="dark:text-gray-100" value="">{{ __('All devices') }}</option>
                                    @foreach($devices as $d)
                                        <option style="color:#1f2937;" class="dark:text-gray-100" value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <label for="select-year" class="sr-only">{{ __('Year') }}</label>
                        <div class="inline-flex items-center rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-700 px-3 py-1.5 sm:py-1 shadow-sm {{ $selectRingClass }} w-full sm:w-auto mt-2 md:mt-0">
                            <i class="fa-solid fa-calendar text-gray-400 mx-2" aria-hidden="true"></i>
                            <select id="select-year" wire:model="selectedYear" wire:change="$set('selectedYear', $event.target.value)" class="appearance-none bg-transparent border-0 pl-2 pr-6 text-sm font-semibold text-gray-700 dark:text-gray-100 focus:outline-none cursor-pointer w-full sm:min-w-[70px] focus:ring-0 focus:border-0" aria-label="{{ __('Select year') }}">
                                @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                                    <option style="color:#1f2937;" class="dark:text-gray-100" value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full flex-1 relative mt-2 md:mt-0" wire:ignore>
                <canvas id="monthlyDownloadsChart" class="w-full h-full block" role="img" aria-label="{{ __('Monthly downloads chart') }}"></canvas>
            </div>
        </div>

        <div class="flex flex-col space-y-3 md:space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4 flex-1 flex flex-col justify-between">
                <h3 class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie"></i>{{ __('Distribution') }}
                </h3>

                <div class="mt-3 flex items-end justify-center" wire:ignore>
                    <div class="w-[200px] h-[200px] sm:w-[220px] sm:h-[220px]">
                        <canvas id="pieDownloadsChart" class="w-full h-full" aria-label="{{ __('Downloads distribution chart') }}"></canvas>
                    </div>
                </div>

                <div class="col-span-1 sm:col-span-2 md:col-span-3 mt-2">
                    <div class="flex flex-col sm:flex-row items-center sm:items-center sm:justify-between gap-2 px-2">
                        <div class="flex-shrink-0 text-[11px] sm:text-xs text-gray-600 dark:text-gray-400 mr-2">{{ __('Top device this year:') }}</div>

                        @if(!empty($kpis['top_device']))
                            @php $topDeviceImage = $kpis['top_device']['image'] ?? null; @endphp
                            <div class="flex items-center gap-2 min-w-0">
                                @if($topDeviceImage)
                                    <img src="{{ $topDeviceImage }}" alt="{{ $kpis['top_device']['name'] ?? '' }}" class="w-5 h-5 sm:w-5 sm:h-5 object-contain object-center rounded flex-shrink-0" />
                                @else
                                    <div class="w-5 h-5 sm:w-5 sm:h-5 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-hard-drive text-gray-400 text-[11px]"></i>
                                    </div>
                                @endif

                                <div class="min-w-0">
                                    <div class="text-[12px] sm:text-sm font-semibold text-gray-900 dark:text-gray-100 truncate" title="{{ $kpis['top_device']['name'] ?? '' }}">{{ $kpis['top_device']['name'] ?? '—' }}</div>
                                </div>
                            </div>
                        @else
                            <div class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-gray-100">—</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-2.5 sm:p-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 text-center">
                <div class="py-1 sm:py-2">
                    <h4 class="text-[11px] sm:text-xs text-gray-600 dark:text-gray-400">{{ __('Top month') }}</h4>
                    <div class="text-base sm:text-lg font-bold mt-0.5 text-gray-900 dark:text-gray-100" id="kpi-top">{{ __($kpis['top']['month'] ?? '—') }}</div>
                </div>
                <div class="py-1 sm:py-2">
                    <h4 class="text-[11px] sm:text-xs text-gray-600 dark:text-gray-400">{{ __('Average per month') }}</h4>
                    <div class="text-base sm:text-lg font-bold mt-0.5 text-gray-900 dark:text-gray-100" id="kpi-average">{{ $kpis['average'] ?? 0 }}</div>
                </div>
                <div class="py-1 sm:py-2">
                    <h4 class="text-[11px] sm:text-xs text-gray-500">{{ __('Total per year') }}</h4>
                    <div class="text-base sm:text-lg font-bold mt-0.5 text-gray-900 dark:text-gray-100" id="kpi-total">{{ $kpis['total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@once
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="/js/downloads-graph.js"></script>
        <script>
            const downloadsPdfUrl = "{{ route('admin.downloads.history.pdf') }}";

            function exportChartsPdf(e) {
                e && e.preventDefault();
                const monthlyCanvas = document.getElementById('monthlyDownloadsChart');
                const pieCanvas = document.getElementById('pieDownloadsChart');
                if (!monthlyCanvas || !pieCanvas) {
                    alert('Charts not ready');
                    return;
                }

                const monthlyData = monthlyCanvas.toDataURL('image/png');
                const pieData = pieCanvas.toDataURL('image/png');

                const fd = new FormData();
                fd.append('charts[monthly]', monthlyData);
                fd.append('charts[pie]', pieData);
                const yearSelect = document.querySelector('#select-year');
                const deviceSelect = document.querySelector('#select-device');
                if (yearSelect) {
                    fd.append('year', yearSelect.value);
                }
                if (deviceSelect) {
                    fd.append('device_id', deviceSelect.value);
                }

                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const headers = tokenMeta ? { 'X-CSRF-TOKEN': tokenMeta.getAttribute('content') } : {};

                fetch(downloadsPdfUrl, { method: 'POST', body: fd, headers })
                    .then(resp => {
                        if (!resp.ok) throw new Error('PDF generation failed');
                        return resp.blob();
                    })
                    .then(blob => {
                        const url = URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'downloads-charts.pdf';
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        URL.revokeObjectURL(url);
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error generating PDF');
                    });
            }
        </script>
    @endpush
@endonce

@php
    $__initialDownloadsData = $monthlyData ?? array_fill(0, 12, 0);
    $__initialDownloadsKpis = $kpis ?? ['total' => 0, 'average' => 0, 'top' => ['month' => '—', 'value' => 0]];
@endphp

<script type="application/json" id="initialDownloadsData">{!! json_encode($__initialDownloadsData) !!}</script>
<script type="application/json" id="initialDownloadsKpis">{!! json_encode($__initialDownloadsKpis) !!}</script>
