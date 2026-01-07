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
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-4 h-[336px] relative overflow-hidden">
            <div class="flex items-center justify-between mb-3 gap-4">
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
                    <div id="chart-loading" class="text-sm text-gray-500 hidden" aria-hidden="true">{{ __('Loading...') }}</div>

                    <div class="flex items-center space-x-3">
                        @if(isset($devices) && $devices->count())
                            <div class="inline-flex items-center rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-700 px-3 py-1 shadow-sm">
                                <i class="fa-solid fa-hard-drive text-gray-400 mx-2" aria-hidden="true"></i>
                                <select id="select-device" wire:model="selectedDevice" wire:change="$set('selectedDevice', $event.target.value)" class="appearance-none bg-transparent border-0 pl-2 pr-6 text-sm text-gray-700 dark:text-gray-100 focus:outline-none cursor-pointer min-w-[120px]">
                                    <option value="">{{ __('All devices') }}</option>
                                    @foreach($devices as $d)
                                        <option style="color:#1f2937;" class="dark:text-gray-100" value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <label for="select-year" class="sr-only">{{ __('Year') }}</label>
                        <div class="inline-flex items-center rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-700 px-3 py-1 shadow-sm {{ $selectRingClass }}">
                            <i class="fa-solid fa-calendar-days text-gray-400 mx-2" aria-hidden="true"></i>
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
                <canvas id="monthlyDownloadsChart" class="w-full h-full block mb-16" role="img" aria-label="{{ __('Monthly downloads chart') }}"></canvas>
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
                    <div class="text-xl font-bold mt-1 text-gray-900 dark:text-gray-100" id="kpi-total">{{ $kpis['total'] ?? 0 }}</div>
                </div>
                <div>
                    <h4 class="text-xs text-gray-500">{{ __('Average / month') }}</h4>
                    <div class="text-xl font-bold mt-1 text-gray-900 dark:text-gray-100" id="kpi-average">{{ $kpis['average'] ?? 0 }}</div>
                </div>
                <div>
                    <h4 class="text-xs text-gray-500">{{ __('Top month') }}</h4>
                    <div class="text-xl font-bold mt-1 text-gray-900 dark:text-gray-100" id="kpi-top">{{ __($kpis['top']['month'] ?? '—') }}</div>
                </div>

                {{-- <div class="col-span-3 mt-2 flex items-center justify-center gap-3">
                    <div class="text-xs text-gray-600 dark:text-gray-400">{{ __('Top device this year:') }}</div>
                    @if(!empty($kpis['top_device']))
                        <div class="text-xs font-semibold text-gray-900 dark:text-gray-100 truncate leading-tight max-w-[220px]">{{ $kpis['top_device']['name'] ?? '—' }}</div>
                        <div class="text-xs text-gray-500">({{ $kpis['top_device']['total'] ?? 0 }})</div>
                    @else
                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">—</div>
                    @endif
                </div> --}}
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
