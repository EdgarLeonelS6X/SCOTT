<div class="bg-white dark:bg-gray-800 relative shadow-2xl sm:rounded-lg overflow-hidden">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
        <div class="w-full md:w-1/2">
            <form class="flex items-center">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                            viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="simple-search" wire:model.live="search"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="{{ __('Search') }}" autofocus>
                </div>
            </form>
        </div>
        <div
            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown"
                    class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                    type="button">
                    <i class="fa-solid fa-filter mr-1.5"></i>
                    {{ __('Filter') }}
                    <i class="fa-solid fa-chevron-down ml-1.5"></i>
                </button>
                <div id="filterDropdown" class="z-10 hidden w-64 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-center">
                        <input id="resolved-filter" type="checkbox" wire:click="toggleStatusFilter('Resolved')"
                            class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500"
                            {{ $statusFilter === 'Resolved' ? 'checked' : '' }}>
                        <label for="resolved-filter" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            {{ __('Show only resolved reports') }}
                        </label>
                    </div>
                    <div class="flex items-center mt-2">
                        <input id="revision-filter" type="checkbox" wire:click="toggleStatusFilter('Revision')"
                            class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500"
                            {{ $statusFilter === 'Revision' ? 'checked' : '' }}>
                        <label for="revision-filter" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            {{ __('Show only revision reports') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        @if ($reports->isEmpty())
            <div class="text-center m-2 pb-2">
                <span class="text-gray-500 dark:text-gray-300">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    {{ __('No reports found with the current filters.') }}
                </span>
            </div>
        @else
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs dark:text-white uppercase dark:bg-gray-600 shadow-2xl">
                    <tr>
                        <th class="py-3 px-4 text-left">
                            <i class="fa-solid fa-flag mr-1"></i>
                            {{ __('Folio') }}
                        </th>
                        <th class="py-3 px-4 text-left w-36 min-w-[190px] max-w-[190px]">
                            <i class="fa-solid fa-folder mr-1"></i>
                            {{ __('Report') }}
                        </th>
                        <th class="py-3 px-4 text-left w-36 min-w-[150px] max-w-[150px]">
                            <i class="fa-solid fa-list mr-1"></i>
                            {{ __('Type') }}
                        </th>
                        <th class="py-3 px-4 text-left">
                            <i class="fa-solid fa-gear mr-1"></i>
                            {{ __('Status') }}
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
                        <th class="py-3 px-4 text-left cursor-pointer" wire:click="setOrderByReporter()">
                            <i class="fa-solid fa-user mr-1"></i>
                            {{ __('Reported By') }}
                            <button
                                class="ml-1 text-gray-500 dark:text-white focus:outline-none transform transition-all hover:scale-110">
                                @if ($orderField === 'reported_by')
                                    <i
                                        class="fa-solid {{ $orderDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                @else
                                    <i class="fa-solid fa-sort"></i>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="resetFilters">
                            <i class="fa-solid fa-rotate-left mr-1"></i>
                            {{ __('Reset table') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr wire:click="openReportDetails({{ $report->id }})"
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
                                @else
                                @endif
                            </td>
                            <td class="py-3 px-4">
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
                                    alt="{{ $report->reportedBy->name }}" class="w-8 h-8 rounded-full mr-2 shadow-2xl">
                                {{ $report->reportedBy->name }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <i class="fa-solid fa-chevron-right text-gray-600 dark:text-gray-300"></i>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="m-4">
            {{ $reports->links() }}
        </div>
        @if ($showModal && $selectedReport)
            <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-xl w-11/12 md:w-3/4 max-w-4xl">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white flex items-center">
                            <i class="fa-solid fa-tag mr-3"></i>
                            {{ $selectedReport->category }}
                        </h2>
                        <button wire:click="closeReportDetails"
                            class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    <div
                        class="p-5 rounded-xl shadow-md transition-transform hover:scale-[1.02]
                        bg-gradient-to-br from-pink-500 via-orange-400 to-red-500
                        dark:from-blue-900 dark:via-indigo-800 dark:to-purple-900
                        text-white dark:text-gray-100 ring-1 ring-white/20 dark:ring-gray-700 hover:shadow-[0_8px_25px_rgba(0,0,0,0.3)]">
                        <div class="flex justify-between items-center gap-6">
                            <div class="flex items-center gap-4 flex-1">
                                <img src="{{ $selectedReport->reportedBy->profile_photo_url }}"
                                    alt="{{ $selectedReport->reportedBy->name }}"
                                    class="w-12 h-12 rounded-full shadow-2xl">
                                <div>
                                    <h4 class="text-sm font-semibold text-white dark:text-gray-300">
                                        {{ __('Reported by') }}
                                    </h4>
                                    <p class="text-base font-bold text-white dark:text-gray-100">
                                        {{ $selectedReport->reportedBy->name }}
                                    </p>
                                    <p class="text-xs text-gray-200 dark:text-gray-300 opacity-80">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 font-bold bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-full shadow-lg">
                                            {{ __('Folio') }} {{ $selectedReport->id }}
                                        </span>
                                        {{ $selectedReport->created_at->format('d/m/Y h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div
                                    class="flex items-center gap-3 bg-white/20 dark:bg-gray-800 px-4 py-3 rounded-lg shadow-md">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 bg-white text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-full shadow-md">
                                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm text-white dark:text-gray-300">
                                            {{ __('Status') }}
                                        </h4>
                                        <p class="text-sm font-bold uppercase">
                                            {{ $selectedReport->status }}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="flex items-center gap-3 bg-white/20 dark:bg-gray-800 px-4 py-3 rounded-lg shadow-md">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 bg-white text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-full shadow-md">
                                        <i class="fa-solid fa-gear text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm text-white dark:text-gray-300">
                                            {{ __('Under review by') }}
                                        </h4>
                                        <p class="text-sm font-bold text-white dark:text-gray-100">
                                            {{ $selectedReport->attended_by }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <i class="fa-solid fa-layer-group text-xl text-gray-800 dark:text-white mr-1.5"></i>
                        <span class="text-lg font-semibold text-gray-800 dark:text-white mr-1.5">
                            {{ __('This report contains') }}
                        </span>
                        <span class="bg-primary-100 text-primary-800 text-sm font-medium py-1 px-3 rounded-full">
                            {{ $selectedReport->reportDetails->count() }}
                            {{ $selectedReport->reportDetails->count() === 1 ? __('channel') : __('channels') }}
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-2 mt-6 max-h-56 overflow-auto overscroll-x-none"
                            style="scrollbar-width: none">
                            @foreach ($selectedReport->reportDetails as $detail)
                                <div
                                    class="flex flex-col items-center p-6 bg-gray-50 border dark:bg-gray-800 rounded-xl transform transition-transform hover:scale-[1.02] relative">
                                    @if ($detail->description)
                                        <div class="absolute top-2 right-2">
                                            <i class="fa-solid fa-info-circle text-gray-500 dark:text-white text-xl"
                                                title="{{ $detail->description }}"></i>
                                        </div>
                                    @endif
                                    <img src="{{ $detail->channel->image }}" alt="{{ $detail->channel->name }}"
                                        class="w-16 h-16 object-contain rounded-lg mb-4">

                                    <span class="block text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $detail->channel->number }} {{ $detail->channel->name }}
                                    </span>
                                    <span class="block mt-1 text-xs text-gray-500">
                                        {{ $detail->stage->name }}
                                    </span>
                                    <div class="flex space-x-4 mt-4">
                                        @if ($detail->media === 'VIDEO' || $detail->media === 'AUDIO/VIDEO')
                                            <div class="tooltip" title="{{ __('The channel does not have video') }}">
                                                <i class="fa-solid fa-video-slash text-red-500 text-xl"></i>
                                            </div>
                                        @else
                                            <div class="tooltip" title="{{ __('The channel has video') }}">
                                                <i class="fa-solid fa-video text-green-500 text-xl"></i>
                                            </div>
                                        @endif
                                        @if ($detail->media === 'AUDIO' || $detail->media === 'AUDIO/VIDEO')
                                            <div class="tooltip" title="{{ __('The channel does not have audio') }}">
                                                <i class="fa-solid fa-volume-xmark text-red-500 text-xl"></i>
                                            </div>
                                        @else
                                            <div class="tooltip" title="{{ __('The channel has audio') }}">
                                                <i class="fa-solid fa-volume-up text-green-500 text-xl"></i>
                                            </div>
                                        @endif
                                        @if ($detail->protocol === 'DASH' || $detail->protocol === 'DASH/HLS')
                                            <div class="tooltip"
                                                title="{{ __('Not working on Web Client (DASH)') }}">
                                                <i class="fa-solid fa-computer text-red-500 text-xl"></i>
                                            </div>
                                        @else
                                            <div class="tooltip" title="{{ __('Working on Web Client (DASH)') }}">
                                                <i class="fa-solid fa-computer text-green-500 text-xl"></i>
                                            </div>
                                        @endif
                                        @if ($detail->protocol === 'HLS' || $detail->protocol === 'DASH/HLS')
                                            <div class="tooltip" title="{{ __('Not working on Set Up Box (HLS)') }}">
                                                <i class="fa-solid fa-tv text-red-500 text-xl"></i>
                                            </div>
                                        @else
                                            <div class="tooltip" title="{{ __('Working on Set Up Box (HLS)') }}">
                                                <i class="fa-solid fa-tv text-green-500 text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-end mt-6 space-x-4">
                        <button wire:click="closeReportDetails"
                            class="flex items-center gap-2 py-2 px-4 text-base font-bold text-gray-700 bg-white rounded-lg border border-gray-400 hover:border-primary-600 hover:text-primary-600 focus:ring-4 focus:ring-primary-200 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:hover:text-primary-400 dark:hover:bg-gray-700">
                            <i class="fa-solid fa-xmark"></i>
                            {{ __('Close') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
