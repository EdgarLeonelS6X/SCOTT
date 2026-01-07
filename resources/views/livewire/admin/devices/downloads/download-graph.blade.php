<div>
@php
    $area = Auth::user()?->area;
    $selectRingClass = $area === 'OTT'
        ? 'focus-within:ring-2 focus-within:ring-primary-400 dark:focus-within:ring-primary-600'
        : ($area === 'DTH'
            ? 'focus-within:ring-2 focus-within:ring-secondary-400 dark:focus-within:ring-secondary-600'
            : 'focus-within:ring-2 focus-within:ring-primary-400 dark:focus-within:ring-primary-600');
@endphp
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-4 h-[375px] relative overflow-hidden">
            <div class="flex items-center justify-between mb-5 gap-4">
                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-semibold truncate text-gray-900 dark:text-gray-100">
                        <i class="fa-solid fa-download mr-2" aria-hidden="true"></i>
                        {{ __('Downloads - Yearly view') }}
                    </h2>
                    <p class="text-sm mt-1 truncate text-gray-600 dark:text-gray-400">
                        {{ __('Monthly downloads for the selected year.') }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
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

                    <div class="flex items-center space-x-3">
                        @if(isset($devices) && $devices->count())
                        <div class="inline-flex items-center rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-700 px-3 py-1 shadow-sm {{ $selectRingClass }}">
                                <i class="fa-solid fa-hard-drive text-gray-400 mx-2" aria-hidden="true"></i>
                                <select id="select-device" wire:model="selectedDevice" wire:change="$set('selectedDevice', $event.target.value)" class="appearance-none bg-transparent border-0 pl-2 pr-6 text-sm font-semibold text-gray-700 dark:text-gray-100 focus:outline-none cursor-pointer w-[220px] focus:ring-0 focus:border-0 truncate leading-tight" aria-label="{{ __('Select device') }}">
                                    <option style="color:#1f2937;" class="dark:text-gray-100" value="">{{ __('All devices') }}</option>
                                    @foreach($devices as $d)
                                        <option style="color:#1f2937;" class="dark:text-gray-100" value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <label for="select-year" class="sr-only">{{ __('Year') }}</label>
                        <div class="inline-flex items-center rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-700 px-3 py-1 shadow-sm {{ $selectRingClass }}">
                            <i class="fa-solid fa-calendar text-gray-400 mx-2" aria-hidden="true"></i>
                            <select id="select-year" wire:model="selectedYear" wire:change="$set('selectedYear', $event.target.value)" class="appearance-none bg-transparent border-0 pl-2 pr-6 text-sm font-semibold text-gray-700 dark:text-gray-100 focus:outline-none cursor-pointer min-w-[70px] focus:ring-0 focus:border-0" aria-label="{{ __('Select year') }}">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option style="color:#1f2937;" class="dark:text-gray-100" value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full h-full relative" wire:ignore>
                <canvas id="monthlyDownloadsChart" class="w-full h-full block mb-20" role="img" aria-label="{{ __('Monthly downloads chart') }}"></canvas>
            </div>
        </div>

        <div class="flex flex-col space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex-1 flex flex-col">
                <h3 class="text-sm text-gray-500 flex items-center gap-2"><i
                        class="fa-solid fa-chart-pie text-gray-400"></i>{{ __('Distribution') }}</h3>
                <div class="w-full flex-1 mt-2 flex items-center justify-center">
                    <canvas id="pieDownloadsChart" class="w-40 h-40"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 grid grid-cols-3 gap-3 text-center">
                <div class="mt-1">
                    <h4 class="text-xs text-gray-500">{{ __('Annual total') }}</h4>
                    <div class="text-xl font-bold mt-1 text-gray-900 dark:text-gray-100" id="kpi-total">{{ $kpis['total'] ?? 0 }}</div>
                </div>
                <div class="mt-1">
                    <h4 class="text-xs text-gray-500">{{ __('Average per month') }}</h4>
                    <div class="text-xl font-bold mt-1 text-gray-900 dark:text-gray-100" id="kpi-average">{{ $kpis['average'] ?? 0 }}</div>
                </div>
                <div class="mt-1">
                    <h4 class="text-xs text-gray-500">{{ __('Top month') }}</h4>
                    <div class="text-xl font-bold mt-1 text-gray-900 dark:text-gray-100" id="kpi-top">{{ __($kpis['top']['month'] ?? '—') }}</div>
                </div>

                <div class="col-span-3 mt-2">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between gap-3 px-2">
                        <div class="flex-shrink-0 text-xs text-gray-600 dark:text-gray-400">{{ __('Top device this year:') }}</div>

                        @if(!empty($kpis['top_device']))
                            @php $topDeviceImage = $kpis['top_device']['image'] ?? null; @endphp
                            <div class="flex items-center gap-3 min-w-0 w-full sm:w-auto">
                                @if($topDeviceImage)
                                    <img src="{{ $topDeviceImage }}" alt="{{ $kpis['top_device']['name'] ?? '' }}" class="w-5 h-5 object-contain object-center rounded flex-shrink-0" />
                                @else
                                    <div class="w-5 h-5 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-hard-drive text-gray-400"></i>
                                    </div>
                                @endif

                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-semibold text-gray-900 dark:text-gray-100 truncate" title="{{ $kpis['top_device']['name'] ?? '' }}">{{ $kpis['top_device']['name'] ?? '—' }}</div>
                                </div>

                                <div class="ml-3 text-xs text-gray-500 flex-shrink-0">
                                    <i class="fa-solid fa-arrow-down mr-0.5"></i>
                                    {{ $kpis['top_device']['total'] ?? 0 }}
                                </div>
                            </div>
                        @else
                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">—</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@once
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="/js/downloads-graph.js"></script>
    @endpush
@endonce

@php
    $__initialDownloadsData = $monthlyData ?? array_fill(0, 12, 0);
    $__initialDownloadsKpis = $kpis ?? ['total' => 0, 'average' => 0, 'top' => ['month' => '—', 'value' => 0]];
@endphp

<script type="application/json" id="initialDownloadsData">{!! json_encode($__initialDownloadsData) !!}</script>
<script type="application/json" id="initialDownloadsKpis">{!! json_encode($__initialDownloadsKpis) !!}</script>
