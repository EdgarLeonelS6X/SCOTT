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
                <a href="{{ route('reports.index') }}"
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
                            <th wire:click="toggleOrder" class="py-3 px-4 text-left flex items-center cursor-pointer">
                                <i class="fa-solid fa-calendar mr-1"></i>
                                {{ __('Reported') }}
                                <i class="fa-solid {{ $order === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }} ml-1"></i>
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
                                        class="inline-flex items-center px-2 py-1 text-sm font-medium text-yellow-800 bg-yellow-200 dark:bg-yellow-800 dark:text-yellow-200 rounded-full">
                                        <i class="fa-solid fa-magnifying-glass mr-1.5"></i>
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
            <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-xl w-11/12 md:w-3/4 max-w-4xl">
                <div class="flex justify-between items-center mb-8">
                    <div
                        class="flex items-center gap-4 bg-white dark:bg-gray-800 px-4 py-2 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700">
                        <i class="fa-solid fa-file-alt text-gray-800 dark:text-gray-100 text-2xl"></i>
                        <span class="text-xl font-semibold text-gray-900 dark:text-white leading-tight">
                            {{ $selectedReport->category }}
                        </span>
                        <span
                            class="text-xs font-medium text-white bg-red-500 dark:bg-red-600 px-3 py-1 rounded-lg shadow-2xl">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                            {{ $report->type }}
                        </span>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ __('Folio') }} #{{ $report->id }}
                        </span>
                    </div>
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
                                        {{ $selectedReport->reviewed_by }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <div class="flex items-center">
                        <i class="fa-solid fa-layer-group text-xl text-gray-800 dark:text-white mr-1.5"></i>
                        <span class="text-lg font-semibold text-gray-800 dark:text-white mr-1.5">
                            {{ __('This report contains') }}
                        </span>
                        <span
                            class="bg-primary-100 text-primary-800 text-sm font-medium py-1 px-3 rounded-full ml-1.5">
                            {{ isset($selectedReport) && $selectedReport ? $selectedReport->reportDetails->count() : 0 }}
                            {{ isset($selectedReport) && $selectedReport->reportDetails->count() === 1 ? __('channel') : __('channels') }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-2 mt-4
                        @if (isset($selectedReport) && $selectedReport->reportDetails->count() > 4) max-h-56 overflow-auto @else overflow-hidden @endif"
                        style="scrollbar-width: none">
                        @if (isset($selectedReport) && $selectedReport)
                            @foreach ($selectedReport->reportDetails as $detail)
                                <div @click.prevent="openMiniPlayer('{{ $detail->channel->url }}')"
                                    class="relative flex flex-col px-5 py-3 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-xl space-y-4 cursor-pointer">
                                    <div x-data="{ show: false }" class="absolute -top-3 -right-3 z-20 h-6 w-6">
                                        @if ($detail->description)
                                            <button @mouseenter="show = true" @mouseleave="show = false"
                                                type="button"
                                                class="flex items-center justify-center w-full h-full rounded-full text-sm text-gray-500 dark:text-gray-300">
                                                <i class="fa-solid fa-circle-info text-base"></i>
                                            </button>
                                            <div x-show="show" x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 translate-y-1"
                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100 translate-y-0"
                                                x-transition:leave-end="opacity-0 translate-y-1"
                                                @mouseenter="show = true" @mouseleave="show = false"
                                                class="absolute z-30 w-64 mt-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg shadow-lg dark:text-gray-300 dark:border-gray-600 dark:bg-gray-800"
                                                style="right: -120px;">
                                                <div
                                                    class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700 text-center">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                                        {{ __('Description') }}
                                                    </h3>
                                                </div>
                                                <div class="px-3 py-2 text-center">
                                                    <p class="text-gray-700 dark:text-gray-300">
                                                        {{ $detail->description }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex items-center gap-2 w-full">
                                            <div class="w-1/3 flex-shrink-0">
                                                <img src="{{ $detail->channel->image }}"
                                                    alt="{{ $detail->channel->name }}"
                                                    class="w-10 h-10 object-contain object-center shadow-sm rounded-lg">
                                            </div>
                                            <div class="w-2/3 flex flex-col justify-center text-end">
                                                <p
                                                    class="text-base font-semibold text-gray-900 dark:text-white leading-tight">
                                                    {{ $detail->channel->number }} {{ $detail->channel->name }}
                                                </p>
                                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    {{ $detail->stage->name }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="flex justify-around items-center gap-3 px-5 py-3 mt-4 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg w-full">
                                        <div class="flex flex-col items-center tooltip"
                                            title="{{ $detail->media === 'VIDEO' || $detail->media === 'AUDIO/VIDEO' ? __('The channel does not have video') : __('The channel has video') }}">
                                            <i
                                                class="fa-solid {{ $detail->media === 'VIDEO' || $detail->media === 'AUDIO/VIDEO' ? 'fa-video-slash text-red-500' : 'fa-video text-green-500' }} text-xl"></i>
                                            <span
                                                class="text-[10px] mt-1 text-gray-500 dark:text-gray-300">VIDEO</span>
                                        </div>
                                        <div class="flex flex-col items-center tooltip"
                                            title="{{ $detail->media === 'AUDIO' || $detail->media === 'AUDIO/VIDEO' ? __('The channel does not have audio') : __('The channel has audio') }}">
                                            <i
                                                class="fa-solid {{ $detail->media === 'AUDIO' || $detail->media === 'AUDIO/VIDEO' ? 'fa-volume-xmark text-red-500' : 'fa-volume-up text-green-500' }} text-xl"></i>
                                            <span
                                                class="text-[10px] mt-1 text-gray-500 dark:text-gray-300">AUDIO</span>
                                        </div>
                                        <div class="flex flex-col items-center tooltip"
                                            title="{{ $detail->protocol === 'DASH' || $detail->protocol === 'DASH/HLS' ? __('Not working on Web Client (DASH)') : __('Working on Web Client (DASH)') }}">
                                            <i
                                                class="fa-solid fa-computer {{ $detail->protocol === 'DASH' || $detail->protocol === 'DASH/HLS' ? 'text-red-500' : 'text-green-500' }} text-xl"></i>
                                            <span class="text-[10px] mt-1 text-gray-500 dark:text-gray-300">DASH</span>
                                        </div>
                                        <div class="flex flex-col items-center tooltip"
                                            title="{{ $detail->protocol === 'HLS' || $detail->protocol === 'DASH/HLS' ? __('Not working on Set Up Box (HLS)') : __('Working on Set Up Box (HLS)') }}">
                                            <i
                                                class="fa-solid fa-tv {{ $detail->protocol === 'HLS' || $detail->protocol === 'DASH/HLS' ? 'text-red-500' : 'text-green-500' }} text-xl"></i>
                                            <span class="text-[10px] mt-1 text-gray-500 dark:text-gray-300">HLS</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="flex justify-end mt-4 space-x-4">
                    <button wire:click.prevent="markAsSolved()"
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

<script>
    function openMiniPlayer(url) {
        const youtubeRegex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]{11})/;
        const match = url.match(youtubeRegex);

        if (match) {
            const videoId = match[1];
            url = `https://www.youtube.com/embed/${videoId}`;
        }

        let playerContainer = document.getElementById('miniPlayerContainer');
        if (!playerContainer) {
            playerContainer = document.createElement('div');
            playerContainer.id = 'miniPlayerContainer';
            playerContainer.classList =
                'fixed bottom-4 right-4 w-80 bg-white shadow-lg rounded-lg overflow-hidden z-50';
            document.body.appendChild(playerContainer);

            const controlBar = document.createElement('div');
            controlBar.classList =
                'w-full flex justify-between items-center bg-primary-600 dark:bg-primary-700 text-white p-2 shadow-2xl';
            controlBar.style.height = '40px';
            controlBar.innerHTML = `
                <span>{{ __('Playing channel') }}</span>
                <button onclick="closeMiniPlayer()" class="text-gray-300 hover:text-white">
                    <i class="fa-solid fa-times"></i>
                </button>
            `;
            playerContainer.appendChild(controlBar);

            const iframe = document.createElement('iframe');
            iframe.classList = 'w-full';
            iframe.style.height =
                'calc(100% - 40px)';
            iframe.frameBorder = 0;
            iframe.allowFullscreen = true;
            playerContainer.appendChild(iframe);
        }

        playerContainer.querySelector('iframe').src = url;
        playerContainer.style.display = 'block';
    }

    function closeMiniPlayer() {
        const playerContainer = document.getElementById('miniPlayerContainer');
        if (playerContainer) {
            playerContainer.style.display = 'none';
            playerContainer.querySelector('iframe').src = '';
        }
    }
</script>
