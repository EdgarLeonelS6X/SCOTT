<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
        <div class="lg:col-span-2 relative">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden h-[380px] sm:h-[420px]">
                <div class="flex items-center justify-between px-3 sm:px-4 py-2 sm:py-3 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-white/70 to-transparent dark:from-gray-800/70">
                    <div>
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                            <i class="fa-solid fa-download mr-1.5"></i>
                            {{ __('Downloads') }}
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            {{ __('Monthly downloads for device and year.') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        @php
                            $area = Auth::user()?->area;
                            $spinnerFill = $area === 'OTT'
                                ? 'fill-primary-600'
                                : ($area === 'DTH'
                                    ? 'fill-secondary-600'
                                    : 'fill-primary-600');
                        @endphp

                        <div id="chart-loading" class="hidden mr-2" role="status" wire:loading.class.remove="hidden" wire:target="selectedYear,selectedDevice">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-200 dark:text-gray-600 animate-spin {{ $spinnerFill }}" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                            </svg>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </div>

                        @php
                            $area = Auth::user()?->area;
                            $ringClass = $area === 'OTT'
                                ? 'focus-within:ring-2 focus-within:ring-primary-400 dark:focus-within:ring-primary-600'
                                : ($area === 'DTH'
                                    ? 'focus-within:ring-2 focus-within:ring-secondary-400 dark:focus-within:ring-secondary-600'
                                    : 'focus-within:ring-2 focus-within:ring-primary-400 dark:focus-within:ring-primary-600');
                        @endphp

                        @if(isset($devices) && $devices->count())
                            <div class="flex items-center justify-center gap-2 px-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full shadow-sm transition w-[220px] relative {{ $ringClass }}">
                            <i class="fa-solid fa-hard-drive text-gray-400 ml-3"></i>
                                <select id="select-device" wire:model="selectedDevice" class="appearance-none bg-transparent border-0 pl-2 text-sm font-semibold text-gray-700 dark:text-gray-100 focus:outline-none cursor-pointer min-w-[70px] focus:ring-0 focus:border-0 truncate leading-tight">
                                    <option style="color:#1f2937;" class="dark:text-gray-100" value="">{{ __('All devices') }}</option>
                                    @foreach($devices as $d)
                                        <option style="color:#1f2937;" class="dark:text-gray-100" value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="flex items-center justify-center gap-2 px-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full shadow-sm transition min-w-[120px] relative {{ $ringClass }}">
                            <i class="fa-solid fa-calendar-days text-gray-400 ml-3"></i>
                            <select id="select-year" wire:model="selectedYear" class="appearance-none bg-transparent border-0 pl-2 text-sm font-semibold text-gray-700 dark:text-gray-100 focus:outline-none cursor-pointer min-w-[70px] focus:ring-0 focus:border-0">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option style="color:#1f2937;" class="dark:text-gray-100" value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="relative" x-data="{ show: false }">
                            <button id="btn-refresh" aria-label="{{ __('Refresh') }}" @mouseenter="show = true" @mouseleave="show = false" @focus="show = true" @blur="show = false"
                                class="inline-flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-full shadow-sm">
                                <i class="fa-solid fa-arrow-rotate-right text-gray-500 dark:text-gray-400"></i>
                            </button>

                            <div x-show="show"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute z-50 left-1/2 transform -translate-x-1/2 mt-2 w-max px-2 py-1 rounded-md text-xs text-white bg-gray-800 dark:bg-gray-100 dark:text-gray-900">
                                {{ __('Refresh') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 relative">
                    <div class="w-full h-full rounded-lg overflow-hidden">
                        <div wire:ignore>
                            <canvas id="monthlyDownloadsChart" class="w-full h-[310px] block"></canvas>
                        </div>
                    </div>

                    <script>
                        (function(){
                            function loadChartJs(cb){
                                if(window.Chart) return cb();
                                var s = document.createElement('script');
                                s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
                                s.onload = cb;
                                document.head.appendChild(s);
                            }

                            loadChartJs(function(){
                                const el = document.getElementById('monthlyDownloadsChart');
                                if(!el) return;
                                const ctx = el.getContext('2d');
                                const monthLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                                let chart = null;

                                function createOrUpdate(data){
                                    if(chart){
                                        chart.data.datasets[0].data = data;
                                        chart.update();
                                        return;
                                    }

                                    chart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: monthLabels,
                                            datasets: [{
                                                label: 'Downloads',
                                                data: data,
                                                backgroundColor: '#60A5FA',
                                                borderRadius: 6,
                                            }]
                                        },
                                        options: {
                                            maintainAspectRatio: false,
                                            scales: { y: { beginAtZero: true } },
                                            plugins: { legend: { display: false } }
                                        }
                                    });
                                }

                                try{
                                    const initial = @json($monthlyData ?? array_fill(0,12,0));
                                    createOrUpdate(initial);
                                }catch(e){
                                    createOrUpdate(Array(12).fill(0));
                                }

                                window.addEventListener('downloads-updated', function(e){
                                    if(e && e.detail && Array.isArray(e.detail.data)){
                                        createOrUpdate(e.detail.data);
                                    }
                                });

                                if(window.livewire){
                                    window.livewire.on('downloads-updated', data => {
                                        if(Array.isArray(data)) createOrUpdate(data);
                                    });
                                }
                            });
                        })();
                    </script>
                </div>
            </div>
        </div>

        <div class="flex flex-col space-y-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 flex-1 flex flex-col">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm text-gray-500 flex items-center gap-2"><i class="fa-solid fa-chart-pie text-gray-400"></i>{{ __('Distribution') }}</h3>
                    <button id="btn-export" class="text-xs text-gray-600 bg-gray-50 hover:bg-gray-100 px-2 py-1 rounded">{{ __('Export') }}</button>
                </div>
                <div class="w-full flex-1 mt-2 flex items-center justify-center">
                    <canvas id="pieDownloadsChart" class="w-32 h-32"></canvas>
                </div>
                <div class="mt-2 text-xs text-gray-500 text-center">{{ __('Distribution across device categories or regions') }}</div>

                <div class="mt-3 grid grid-cols-1 gap-2 text-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                            <span class="text-sm text-gray-700 dark:text-gray-200">{{ __('Set-top boxes') }}</span>
                        </div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">42%</div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                            <span class="text-sm text-gray-700 dark:text-gray-200">{{ __('Mobile') }}</span>
                        </div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">33%</div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 grid grid-cols-3 gap-2 text-center">
                <div>
                    <h4 class="text-xs text-gray-500">{{ __('Total (year)') }}</h4>
                    <div class="text-2xl font-extrabold mt-1 text-primary-600 dark:text-primary-400" id="kpi-total">0</div>
                </div>
                <div>
                    <h4 class="text-xs text-gray-500">{{ __('Average / month') }}</h4>
                    <div class="text-2xl font-extrabold mt-1 text-amber-600" id="kpi-average">0</div>
                </div>
                <div>
                    <h4 class="text-xs text-gray-500">{{ __('Top month') }}</h4>
                    <div class="text-2xl font-extrabold mt-1 text-emerald-600" id="kpi-top">—</div>
                </div>
            </div>
        </div>
    </div>
</div>
