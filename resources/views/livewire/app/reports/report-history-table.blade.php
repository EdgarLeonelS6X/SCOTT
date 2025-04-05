<div class="bg-white dark:bg-gray-800 relative shadow-2xl sm:rounded-lg overflow-hidden mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 bg-white dark:bg-gray-800">
        <div class="w-full md:w-1/3">
            <form class="relative">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                </div>
                <input type="text" id="simple-search" wire:model.live="search"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="{{ __('Search') }}" autofocus>
            </form>
        </div>
        <div class="w-full md:w-auto flex flex-col md:flex-row items-stretch md:items-center justify-end gap-3">
            <style>
                .flatpickr-month {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 0 10px;
                }

                .flatpickr-current-month .cur-month {
                    margin-right: auto;
                    font-weight: 600;
                    font-size: 14px;
                }

                .flatpickr-current-month .numInputWrapper {
                    margin-left: auto;
                }

                .flatpickr-current-month input.cur-year {
                    width: 60px;
                }
            </style>
            <div x-data="{ dateRange: @entangle('startDate').defer + ' to ' + @entangle('endDate').defer }" x-init="flatpickr($refs.input, {
                mode: 'range',
                dateFormat: 'd-m-Y',
                defaultDate: dateRange.split(' to '),
                maxDate: 'today',
                onChange: function(selectedDates, dateStr) {
                    let [start, end] = dateStr.split(' - ');
                    $wire.set('startDate', start);
                    $wire.set('endDate', end);
                }
            })" class="flex flex-col gap-1 shadow-2xl">
                <x-input x-ref="input" type="text" placeholder="{{ __('Select a range') }}"
                    class="w-[225px] px-3 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
            </div>
            <div class="relative">
                <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown"
                    class="flex items-center gap-2 py-2 px-4 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-md hover:bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-filter"></i>
                    {{ __('Filter') }}
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div id="filterDropdown"
                    class="z-10 hidden absolute right-0 mt-2 w-72 p-4 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-700 dark:border-gray-600">
                    <label class="flex items-center space-x-2">
                        <input id="resolved-filter" type="checkbox" wire:click="toggleStatusFilter('Resolved')"
                            class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm text-gray-800 dark:text-gray-200">
                            {{ __('Show only resolved reports') }}
                        </span>
                    </label>
                    <label class="flex items-center space-x-2 mt-3">
                        <input id="revision-filter" type="checkbox" wire:click="toggleStatusFilter('Revision')"
                            class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm text-gray-800 dark:text-gray-200">
                            {{ __('Show only revision reports') }}
                        </span>
                    </label>
                </div>
            </div>
            <button wire:click="exportToExcel"
                class="flex items-center gap-2 py-2 px-4 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                <i class="fa-solid fa-arrow-up-from-bracket"></i>
                {{ __('Export to Excel') }}
            </button>
            <button wire:click="resetFilters"
                class="flex items-center gap-2 py-2 px-4 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                <i class="fa-solid fa-rotate-left"></i>
                {{ __('Reset table') }}
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs dark:text-white uppercase dark:bg-gray-600 shadow-2xl">
                <tr>
                    <th class="py-3 px-4 text-left">
                        <i class="fa-solid fa-flag mr-1"></i>
                        {{ __('Folio') }}
                    </th>
                    <th class="py-3 px-4 text-left w-36 min-w-[300px] max-w-[300px]">
                        <i class="fa-solid fa-folder mr-1"></i>
                        {{ __('Report') }}
                    </th>
                    <th class="py-3 px-4 text-left w-36 min-w-[150px] max-w-[150px] cursor-pointer"
                        wire:click="toggleTypeFilter">
                        <i class="fa-solid fa-list mr-1"></i>
                        <span class="text-gray-500 dark:text-white">
                            @if ($typeFilter)
                                {{ $typeFilter }}
                            @else
                                {{ __('Type') }}
                            @endif
                            <i class="ml-1 fa-solid fa-sort"></i>
                        </span>
                    </th>
                    <th class="py-3 px-4 text-left cursor-pointer" wire:click="toggleStatusFilter">
                        <i class="fa-solid fa-circle-check mr-1"></i>
                        <span class="text-gray-500 dark:text-white">
                            @if ($statusFilter)
                                {{ $statusFilter }}
                            @else
                                {{ __('Status') }}
                            @endif
                            <i class="ml-1 fa-solid fa-sort"></i>
                        </span>
                    </th>
                    <th class="py-3 px-4 text-left cursor-pointer" wire:click="setOrder('created_at')">
                        <i class="fa-solid fa-calendar mr-1"></i>
                        {{ __('Datetime') }}
                        <button
                            class="ml-1 text-gray-500 dark:text-white focus:outline-none transform transition-all hover:scale-110">
                            @if ($orderField === 'created_at')
                                <i
                                    class="fa-solid {{ $orderDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                            @else
                                <i class="fa-solid fa-sort"></i>
                            @endif
                        </button>
                    </th>
                    <th class="py-3 px-4 text-left min-w-[325px] max-w-[325px] cursor-pointer"
                        wire:click="toggleUserFilter">
                        <i class="fa-solid fa-user mr-1"></i>
                        {{ __('Reported By') }}
                        <span class="text-gray-500 dark:text-white">
                            @if ($selectedUser)
                                {{ $selectedUser }}
                            @else
                                {{ __('All Users') }}
                            @endif
                            <i class="ml-1 fa-solid fa-sort"></i>
                        </span>
                    </th>
                    <th scope="col" class="py-3 px-4">
                        <span class="sr-only">
                            <i class="fa-solid fa-sliders-h mr-1"></i>
                            {{ __('Options') }}
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($reports->isEmpty())
                    <tr>
                        <td colspan="7" class="bg-white text-center py-6 pb-3">
                            <div class="text-gray-500 dark:text-gray-300">
                                <i class="fa-solid fa-circle-info mr-1"></i>
                                {{ __('No reports found with the current filters.') }}
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach ($reports as $report)
                        <tr onclick="window.location='{{ route('reports.show', $report->id) }}'"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600 text-black dark:text-white cursor-pointer">
                            <td class="px-4 py-3 text-sm font-bold">
                                {{ $report->id }}
                            </td>
                            <td class="px-4 py-3 text-sm font-bold">
                                {{ $report->category }}
                            </td>
                            <td class="py-3 px-4">
                                @if ($report->type === 'Momentary')
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-full text-red-800 bg-red-200 dark:bg-red-800 dark:text-red-200">
                                        <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>
                                        {{ __($report->type) }}
                                    </span>
                                @elseif ($report->type === 'Hourly')
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-full text-green-800 bg-green-200 dark:bg-green-800 dark:text-green-200">
                                        <i class="fa-solid fa-clock mr-1.5"></i>
                                        {{ __($report->type) }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-full text-blue-800 bg-blue-200 dark:bg-blue-800 dark:text-blue-200">
                                        <i class="fa-solid fa-forward mr-1.5"></i>
                                        {{ __($report->type) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if ($report->type === 'Momentary')
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-full
                                {{ $report->status === 'Resolved' ? 'text-green-800 bg-green-200 dark:bg-green-800 dark:text-green-200' : 'text-yellow-800 bg-yellow-200 dark:bg-yellow-800 dark:text-yellow-200' }}">
                                        @if ($report->status === 'Resolved')
                                            <i class="fa-solid fa-circle-check mr-1.5"></i>
                                        @elseif ($report->status === 'Revision')
                                            <i class="fa-solid fa-magnifying-glass mr-1.5"></i>
                                        @endif
                                        {{ __($report->status) }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-full text-gray-800 bg-gray-200 dark:bg-gray-700 dark:text-gray-200">
                                        <i class="fa-solid fa-folder mr-1.5"></i>
                                        {{ __($report->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span
                                    class="inline-flex items-center px-2 py-1 text-sm font-medium text-blue-800 bg-blue-200 dark:bg-blue-800 dark:text-blue-200 rounded-full">
                                    <i class="fa-solid fa-clock mr-1.5"></i>
                                    {{ $report->created_at->format('d/m/Y h:i A') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 flex items-center">
                                <img src="{{ $report->reportedBy->profile_photo_url }}"
                                    alt="{{ $report->reportedBy->name }}"
                                    class="w-8 h-8 rounded-full mr-2 shadow-2xl">
                                {{ $report->reportedBy->name }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <i class="fa-solid fa-chevron-right text-gray-600 dark:text-gray-300"></i>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="m-4">
            {{ $reports->links() }}
        </div>
    </div>
</div>
