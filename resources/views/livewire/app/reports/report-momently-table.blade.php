<div class="w-full md:w-2/3 pl-6" wire:key="reports-table">
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
                            placeholder="{{ __('Search') }}" required="" autofocus>
                    </div>
                </form>
            </div>
            <div class="w-full md:w-auto flex items-center justify-end space-x-3">
                <a href=""
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    <i class="fa-solid fa-folder mr-1"></i>
                    {{ __('Report history') }}
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs dark:text-white uppercase dark:bg-gray-600 shadow-2xl">
                    <tr>
                        <th class="py-3 px-4 text-left">
                            <i class="fa-solid fa-folder mr-1"></i>
                            {{ __('Report') }}
                        </th>
                        <th class="py-3 px-4 text-left">
                            <i class="fa-solid fa-layer-group mr-1"></i>
                            {{ __('Channels') }}
                        </th>
                        <th class="py-3 px-4 text-left">
                            <i class="fa-solid fa-circle-check mr-1"></i>
                            {{ __('Status') }}
                        </th>
                        <th class="py-3 px-4 text-left flex items-center">
                            <i class="fa-solid fa-calendar mr-1"></i>
                            {{ __('Reported') }}
                            <button wire:click="toggleOrder"
                                class="ml-2 text-gray-500 dark:text-white focus:outline-none transform transition-all hover:scale-110">
                                <i class="fa-solid {{ $order === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                            </button>
                        </th>
                        <th scope="col" class="px-4 py-3">
                            <span class="sr-only">
                                <i class="fa-solid fa-sliders-h mr-1"></i>
                                {{ __('Options') }}
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600 text-black dark:text-white cursor-pointer">
                            <td class="py-3 px-4 font-bold">
                                {{ $report->category }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3">
                                    @foreach ($report->reportDetails->take(3) as $detail)
                                        <div class="relative w-8 h-8 overflow-hidden">
                                            <img class="w-full h-full object-contain object-center"
                                                src="{{ $detail->channel->image }}" alt="{{ $detail->channel->name }}"
                                                title="{{ $detail->channel->number }} {{ $detail->channel->name }}">
                                        </div>
                                    @endforeach
                                    @if ($report->channels_count > 3)
                                        <span
                                            class="flex items-center justify-center w-8 h-8 text-xs font text-white bg-primary-700 rounded-full shadow-md">
                                            +{{ $report->channels_count - 3 }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span
                                    class="inline-flex items-center px-2 py-1 text-sm font-medium 
                                    {{ $report->status === 'Pending'
                                        ? 'text-yellow-800 bg-yellow-200 dark:bg-yellow-800 dark:text-yellow-200'
                                        : ($report->status === 'Resolved'
                                            ? 'text-green-800 bg-green-200 dark:bg-green-800 dark:text-green-200'
                                            : 'text-red-800 bg-red-200 dark:bg-red-800 dark:text-red-200') }} 
                                    rounded-full">
                                    <i
                                        class="fa-solid {{ $report->status === 'Pending'
                                            ? 'fa-hourglass-half'
                                            : ($report->status === 'Resolved'
                                                ? 'fa-check-circle'
                                                : 'fa-exclamation-triangle') }} 
                                       mr-1.5"></i>
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span
                                    class="inline-flex items-center px-2 py-1 text-sm font-medium text-blue-800 bg-blue-200 rounded-full dark:bg-blue-800 dark:text-blue-200">
                                    <i class="fa-solid fa-clock mr-1.5"></i>
                                    {{ $report->formatted_date }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button wire:click="openReportDetails({{ $report->id }})"
                                    class="bg-primary-700 hover:bg-primary-800 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 text-white px-3 py-2 rounded-lg shadow-md">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $reports->links() }}
        </div>
    </div>
</div>
