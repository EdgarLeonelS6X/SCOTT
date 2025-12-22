<div class="bg-white dark:bg-gray-800 relative shadow-2xl rounded-lg overflow-hidden mb-6">
    <div class="flex flex-col gap-4 p-4 bg-white dark:bg-gray-800 md:flex-row md:items-center md:justify-between">
        <div class="w-full md:w-1/3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                <i class="fa-solid fa-folder mr-1.5 text-gray-600 dark:text-gray-300"></i>
                {{ __('History of monthly downloads') }}
            </h2>
        </div>
        <div
            class="w-full md:w-auto flex flex-col sm:flex-row sm:flex-wrap items-stretch lg:items-center justify-start md:justify-end gap-3">
            <div x-data="{
                start: @entangle('startDate').defer,
                end: @entangle('endDate').defer,
                flatpickrInstance: null
            }" x-init="
                flatpickrInstance = flatpickr($refs.input, {
                    mode: 'range',
                    dateFormat: 'Y-m-d',
                    defaultDate: (start && end) ? [start, end] : (start ? [start] : []),
                    maxDate: 'today',
                    onChange: function(selectedDates, dateStr) {
                        let [start, end] = dateStr.split(' to ');
                        if (!end) end = start;
                        $wire.set('startDate', start);
                        $wire.set('endDate', end);
                        $refs.input.value = end ? start + ' to ' + end : start;
                    },
                    onReady: function(selectedDates, dateStr, instance) {
                        if (start && !end) {
                            instance.input.value = start;
                        } else if (start && end) {
                            instance.input.value = start + ' to ' + end;
                        }
                    },
                    onClose: function(selectedDates, dateStr, instance) {
                        setTimeout(function() {
                            let [start, end] = instance.input.value.split(' to ');
                            if (!start) return;
                            if (!end) end = start;
                            instance.input.value = end ? start + ' to ' + end : start;
                        }, 10);
                    }
                })
            "
                x-on:clear-datepicker-range.window="
                    flatpickrInstance.clear();
                    $refs.input.value = '';
                "
                class="w-full sm:w-auto">
                <x-input id="downloads-datepicker-range" x-ref="input" type="text" autocomplete="off" placeholder="{{ __('Select a range time') }}"
                    class="min-w-[16rem] w-full px-3 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
            </div>

            <button wire:click="resetFilters"
                class="w-full sm:w-auto flex items-center justify-center gap-2 py-2 px-4 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                <i class="fa-solid fa-rotate-left"></i>
                {{ __('Reset table') }}
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs dark:text-white uppercase dark:bg-gray-600 shadow-2xl">
                <tr>
                    <th class="py-3 px-4 text-left cursor-pointer w-[160px]" wire:click="setOrder('month')">
                        <i class="fa-solid fa-calendar-days mr-1"></i>
                        {{ __('Month') }}
                    </th>
                    <th class="py-3 px-4 text-left cursor-pointer w-[160px]" wire:click="setOrder('year')">
                        <i class="fa-solid fa-calendar mr-1"></i>
                        {{ __('Year') }}
                    </th>
                    <th class="py-3 px-4 text-left cursor-pointer w-[160px]" wire:click="setOrder('count')">
                        <i class="fa-solid fa-download mr-1"></i>
                        {{ __('Total') }}
                    </th>
                    <th scope="col" class="px-4 py-3 w-[80px]">
                        <span class="sr-only">
                            <i class="fa-solid fa-sliders-h mr-1"></i>
                            {{ __('Options') }}
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($aggregates->isEmpty())
                    <tr>
                        <td colspan="4" class="bg-white dark:bg-gray-800 text-center py-6 pb-3">
                            <div class="text-gray-500 dark:text-gray-300">
                                <i class="fa-solid fa-circle-info mr-1"></i>
                                {{ __('No monthly aggregates found with the current filters.') }}
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach ($aggregates as $agg)
                        <tr wire:click="showMonthDetails({{ $agg->year }}, {{ $agg->month }})" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600 text-black dark:text-white cursor-pointer group">
                            <td class="py-3 px-4">
                                @php
                                    $monthName = __(\Carbon\Carbon::createFromFormat('!m', $agg->month)->locale(app()->getLocale())->translatedFormat('F'));
                                    $monthName = mb_strtoupper(mb_substr($monthName, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($monthName, 1, null, 'UTF-8');
                                @endphp
                                {{ $monthName }}
                            </td>
                            <td class="py-3 px-4 text-xs font-bold whitespace-nowrap">
                                {{ $agg->year }}
                            </td>
                            <td class="py-3 px-4">
                                {{ $agg->total_count }}
                            </td>
                            <td class="px-4 py-4 flex items-center justify-end align-middle w-full">
                                <span class="flex items-center h-full justify-center" style="height: 100%; min-height: 24px;">
                                    <i class="fa-solid fa-chevron-right transition-colors text-gray-300 group-hover:text-gray-700 dark:text-gray-500 dark:group-hover:text-gray-400"
                                        style="vertical-align: middle; font-size: 1.1em; line-height: 1;"></i>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="m-4">
        {{ $aggregates->links() }}
    </div>

    @if($showDetailsModal)
        <div class="fixed inset-0 z-50 flex items-start md:items-center justify-center bg-black/50 overflow-y-hidden p-4">
            <div class="w-full max-w-3xl bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-auto max-h-[90vh]">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <div class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fa-solid fa-list mr-1"></i>
                        @php
                            $detailsMonthName = __(\Carbon\Carbon::createFromFormat('!m', $detailsMonth)->locale(app()->getLocale())->translatedFormat('F'));
                            $detailsMonthName = mb_strtoupper(mb_substr($detailsMonthName, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($detailsMonthName, 1, null, 'UTF-8');
                        @endphp
                        {{ $detailsMonthName }} {{ $detailsYear }}
                    </div>
                    <div>
                        <button type="button" wire:click="$set('showDetailsModal', false)" aria-label="{{ __('Close') }}"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    @if(empty($detailsGrouped) || count($detailsGrouped) === 0)
                        <div class="text-gray-500">{{ __('No detailed downloads for this month.') }}</div>
                    @else
                        <div class="max-h-[80vh] overflow-y-auto space-y-3 pr-4">
                            @foreach($detailsGrouped as $ts => $items)
                                <div x-data="{ open: false }" class="border rounded-md overflow-y-hidden">
                                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-gray-700">
                                        <div class="text-sm font-medium text-gray-800 dark:text-white">
                                                @php
                                                    $dt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $ts);
                                                @endphp
                                                <span class="inline-flex items-center justify-center w-6 h-6 mr-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                    <i class="fa-solid fa-calendar-days text-xs"></i>
                                                </span>
                                                {{ $dt->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="text-gray-500">
                                            <i x-bind:class="open ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"></i>
                                        </div>
                                    </button>
                                    <div x-show="open" x-transition class="px-4 py-3 pr-6 bg-white dark:bg-gray-700">
                                        <div class="pr-2">
                                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                                <thead class="text-xs uppercase dark:text-white">
                                                    <tr>
                                                        <th class="py-2 px-3">
                                                            <div class="flex items-center gap-2">
                                                                <i class="fa-solid fa-hard-drive text-gray-600 dark:text-gray-400 mr-1"></i>
                                                                <span>{{ __('Device') }}</span>
                                                            </div>
                                                        </th>
                                                        <th class="py-2 px-3">
                                                            <i class="fa-solid fa-download text-gray-600 dark:text-gray-400 mr-1"></i>
                                                            {{ __('Downloads') }}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($items as $it)
                                                        <tr class="border-t dark:border-gray-700">
                                                            <td class="py-2 px-3 font-semibold">
                                                                <div class="flex items-center gap-3">
                                                                    @if(!empty($it['image']))
                                                                        <img src="{{ $it['image'] }}" alt="{{ $it['device_name'] }}" class="w-6 h-6 rounded-md object-contain object-center" />
                                                                    @else
                                                                        <div class="w-6 h-6 rounded-md bg-gray-100 dark:bg-gray-700 flex items-center justify-center ring-1 ring-gray-200 dark:ring-gray-700">
                                                                            <i class="fa-solid fa-mobile-screen text-gray-500 dark:text-gray-300"></i>
                                                                        </div>
                                                                    @endif
                                                                    <span class="text-gray-900 dark:text-white">{{ $it['device_name'] }}</span>
                                                                </div>
                                                            </td>
                                                            <td class="py-2 px-3 text-gray-700 dark:text-gray-200">{{ $it['count'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
