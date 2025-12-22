<div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div
                class="flex items-center bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded px-3 py-1 shadow-sm">
                <i class="fa-solid fa-calendar-days text-gray-400 mr-2"></i>
                <label for="select-year" class="text-sm text-gray-700 dark:text-gray-200 mr-2">{{ __('Year')
                        }}</label>
                <select id="select-year" class="border-0 bg-transparent text-sm focus:outline-none">
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

        <div class="flex items-center gap-2">
            <a href="#" id="btn-export"
                class="inline-flex items-center gap-2 text-sm bg-white border border-gray-200 hover:bg-gray-50 px-3 py-2 rounded shadow-sm">
                <i class="fa-solid fa-file-csv text-green-600"></i>
                <span class="text-gray-700">{{ __('Export CSV') }}</span>
            </a>
            <a href="#" id="btn-new"
                class="inline-flex items-center gap-2 text-sm bg-primary-600 text-white hover:bg-primary-700 px-4 py-2 rounded shadow">
                <i class="fa-solid fa-plus"></i>
                <span>{{ __('New monthly report') }}</span>
            </a>
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
                <div class="mt-3 text-xs text-gray-500 text-center">{{ __('Distribution across device categories or
                        regions') }}</div>
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
