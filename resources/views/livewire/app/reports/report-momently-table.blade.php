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
            @if ($reports->isEmpty())
                <div class="mt-4 text-center">
                    <span class="text-gray-500 dark:text-gray-300">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        {{ __('There are no reports available at this time.') }}
                    </span>
                </div>
            @else
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
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600 text-black dark:text-white cursor-pointer"
                                wire:click="openReportDetails({{ $report->id }})">
                                <td class="py-3 px-4 font-bold">
                                    {{ $report->category }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-3">
                                        @foreach ($report->reportDetails->take(3) as $detail)
                                            <div class="relative w-8 h-8 overflow-hidden">
                                                <img class="w-full h-full object-contain object-center"
                                                    src="{{ $detail->channel->image }}"
                                                    alt="{{ $detail->channel->name }}"
                                                    title="{{ $detail->channel->number }} {{ $detail->channel->name }}">
                                            </div>
                                        @endforeach
                                        @if ($report->reportDetails->count() > 3)
                                            <span
                                                class="flex items-center justify-center w-8 h-8 text-xs font text-white bg-primary-700 rounded-full shadow-md">
                                                +{{ $report->reportDetails->count() - 3 }}
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
                                            mr-1.5">
                                        </i>
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
                                    <i class="fa-solid fa-chevron-right text-gray-600 dark:text-gray-300"></i>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="p-4">
            {{ $reports->links() }}
        </div>
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
                                    {{ $selectedReport->created_at->format('d/m/Y h:i A') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="flex items-center gap-3 bg-white/20 dark:bg-gray-800 px-4 py-3 rounded-lg shadow-md">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-white text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-full shadow-md">
                                    <i
                                        class="fa-solid {{ $selectedReport->status === 'Solved'
                                            ? 'fa-check-circle'
                                            : ($selectedReport->status === 'Pending'
                                                ? 'fa-hourglass-half'
                                                : 'fa-exclamation-circle') }} text-lg"></i>
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
                <div class="mt-8">
                    <h4 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                        <i class="fa-solid fa-layer-group mr-2"></i>
                        {{ __('This report contains') }}
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($selectedReport->reportDetails as $detail)
                            <div
                                class="flex flex-col items-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-2xl transform transition-transform hover:scale-105">
                                @if ($detail->description)
                                    <div class="absolute top-2 right-2">
                                        <i class="fa-solid fa-info-circle text-gray-500 text-xl"
                                            title="{{ $detail->description }}"></i>
                                    </div>
                                @endif
                                <img src="{{ $detail->channel->image }}" alt="{{ $detail->channel->name }}"
                                    class="w-16 h-16 object-contain rounded-lg mb-4">
                                <span class="block text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $detail->channel->number }}
                                    {{ $detail->channel->name }}
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
                                        <div class="tooltip" title="{{ __('Not working on Web Client (DASH)') }}">
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
                <div class="flex justify-end mt-8 space-x-4">
                    <button type="submit"
                        class="py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-lg shadow font-bold text-base">
                        <i class="fa-solid fa-circle-check mr-1"></i>
                        {{ __('Mark as solved') }}
                    </button>
                    <button wire:click="closeReportDetails"
                        class="flex items-center gap-2 py-2 px-4 text-base font-bold text-gray-700 bg-white rounded-lg border border-gray-400 hover:border-primary-600 hover:text-primary-600 focus:ring-4 focus:ring-primary-200 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:hover:text-primary-400 dark:hover:bg-gray-700">
                        <i class="fa-solid fa-xmark"></i>
                        {{ __('Discard') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
