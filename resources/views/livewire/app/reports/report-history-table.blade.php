<div class="bg-white dark:bg-gray-800 relative shadow-2xl sm:rounded-lg overflow-hidden mb-6">
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
            <div class="flex items-center">
                <button wire:click="resetFilters"
                    class="flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    <i class="fa-solid fa-rotate-left mr-1"></i>
                    {{ __('Reset table') }}
                </button>
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
                </tbody>
            </table>
        @endif
        <div class="m-4">
            {{ $reports->links() }}
        </div>
    </div>
</div>
