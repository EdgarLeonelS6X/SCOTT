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
            <div class="w-full h-full" wire:ignore>
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
