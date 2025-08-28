<div class="bg-white dark:bg-gray-900 p-4 sm:p-6 rounded-2xl shadow-2xl w-full max-w-6xl mx-auto mt-8 md:mt-0 flex flex-col md:min-h-[80vh]">
    <div
        class="flex flex-col sm:flex-row sm:flex-wrap justify-between items-center gap-4 mb-6 overflow-x-auto whitespace-nowrap">
        <div
            class="flex flex-wrap items-center gap-4 bg-white dark:bg-gray-800 px-4 py-2 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full sm:w-auto min-w-0">
            <i class="fa-solid fa-file-alt text-gray-800 dark:text-gray-100 text-2xl"></i>
            <span class="text-xl font-semibold text-gray-900 dark:text-white leading-tight">
                {{ $selectedReport->category }}
            </span>
            <span class="text-xs font-medium text-white bg-red-500 dark:bg-red-600 px-3 py-1 rounded-lg shadow-2xl">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                {{ $selectedReport->type }}
            </span>
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ __('Folio') }} #{{ $selectedReport->id }}
            </span>
        </div>
        <button wire:click="closeReportDetails"
            class="hidden sm:block text-gray-500 pt-1 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>
    <div
        class="p-4 sm:p-5 rounded-xl shadow-md transition-transform hover:scale-[1.02]
                                bg-gradient-to-br from-pink-500 via-orange-400 to-red-500
                                dark:from-blue-900 dark:via-indigo-800 dark:to-purple-900
                                text-white dark:text-gray-100 ring-1 ring-white/20 dark:ring-gray-700 hover:shadow-[0_8px_25px_rgba(0,0,0,0.3)]">
        <div class="flex flex-col md:flex-row md:justify-between items-center gap-6">
            <div class="flex items-center gap-4 flex-1 min-w-0">
                <img src="{{ $selectedReport->reportedBy->profile_photo_url }}"
                    alt="{{ $selectedReport->reportedBy->name }}"
                    class="w-12 h-12 rounded-full shadow-2xl object-cover">
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
            <div class="flex gap-4 flex-wrap justify-end w-full md:w-auto">
                <div
                    class="flex items-center gap-3 bg-white/20 dark:bg-gray-800 px-4 py-3 rounded-lg shadow-md w-full sm:w-auto mb-4 sm:mb-0 ">
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
                    class="flex items-center gap-3 bg-white/20 dark:bg-gray-800 px-4 py-3 rounded-lg shadow-md w-full sm:w-auto">
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
    <div class="mt-6 flex flex-col flex-1 overflow-hidden">
        <div class="flex flex-wrap items-center gap-2">
            <i class="fa-solid fa-layer-group text-xl text-gray-800 dark:text-white mr-3"></i>
            <span class="text-lg font-semibold text-gray-800 dark:text-white mr-1.5">
                {{ __('This report contains') }}
            </span>
            <span class="bg-primary-100 text-primary-800 text-sm font-medium py-1 px-3 rounded-full ml-1.5">
                {{ isset($selectedReport) && $selectedReport ? $selectedReport->reportDetails->count() : 0 }}
                {{ isset($selectedReport) && $selectedReport->reportDetails->count() === 1 ? __('Channel') : __('Channels') }}
            </span>
        </div>
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 sm:gap-6 p-2 mt-4 flex-1 min-h-0 z-0"
        style="scrollbar-width: none; max-height: 24rem; overflow-y: auto;">
            @if (isset($selectedReport) && $selectedReport)
                @foreach ($selectedReport->reportDetails->sortBy(fn($detail) => $detail->channel->number) as $detail)
                    <div
                        class="relative overflow-visible flex flex-col px-3 py-3 sm:px-4 sm:py-4 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-xl space-y-3 text-sm min-w-0 h-auto max-h-[190px]">
                        @if ($detail->description)
                            <div x-data="{ openModal: false }" class="absolute -top-3 -right-3 h-6 w-6"
                                :class="{ 'z-[60]': openModal, 'z-50': !openModal }">
                                <button @click.stop="openModal = true; $event.stopImmediatePropagation()" type="button"
                                    class="flex items-center justify-center w-full h-full rounded-full text-sm text-gray-500 dark:text-gray-300">
                                    <i class="fa-solid fa-circle-info text-base"></i>
                                </button>
                                <div x-show="openModal" x-transition:enter="ease-out duration-200"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 p-4" x-cloak>
                                    <div @click.away="openModal = false; $event.stopPropagation()" x-show="openModal"
                                        x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150"
                                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                        class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl w-full max-w-sm p-6 relative">
                                        <button @click.stop="openModal = false"
                                            class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                            <i class="fa-solid fa-xmark text-lg"></i>
                                        </button>
                                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-4">
                                            {{ __('Description') }}
                                        </h2>
                                        <p class="text-gray-700 dark:text-gray-300 text-sm text-center break-words">
                                            {{ $detail->description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div @click.stop="downloadM3U('{{ $detail->channel->url }}', '{{ $detail->channel->number }}', '{{ $detail->channel->name }}');" class="cursor-pointer">
                            <div class="flex items-start gap-2">
                                <div class="flex items-center gap-2 w-full min-w-0">
                                    <div class="w-10 h-10 flex-shrink-0">
                                        <img src="{{ $detail->channel->image }}" alt="{{ $detail->channel->name }}"
                                            title="{{ $detail->channel->number }} {{ $detail->channel->name }}"
                                            class="w-10 h-10 object-contain object-center">
                                    </div>
                                    <div class="flex-1 flex flex-col justify-center text-end min-w-0">
                                        <p class="text-base font-semibold text-gray-900 dark:text-white leading-tight truncate">
                                            {{ $detail->channel->number }} {{ $detail->channel->name }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                            {{ $detail->stage->name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                             <div
                                    class="flex justify-around items-center gap-3 px-5 py-3 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg mt-5">
                                    <div class="flex flex-col items-center tooltip"
                                        title="{{ $detail->media === 'VIDEO' || $detail->media === 'AUDIO/VIDEO' ? __('The channel does not have video') : __('The channel has video') }}">
                                        <i
                                            class="fa-solid {{ $detail->media === 'VIDEO' || $detail->media === 'AUDIO/VIDEO' ? 'fa-video-slash text-red-500' : 'fa-video text-green-500' }} text-xl"></i>
                                        <span class="text-[10px] mt-1 text-gray-500 dark:text-gray-300">VIDEO</span>
                                    </div>
                                    <div class="flex flex-col items-center tooltip"
                                        title="{{ $detail->media === 'AUDIO' || $detail->media === 'AUDIO/VIDEO' ? __('The channel does not have audio') : __('The channel has audio') }}">
                                        <i
                                            class="fa-solid {{ $detail->media === 'AUDIO' || $detail->media === 'AUDIO/VIDEO' ? 'fa-volume-xmark text-red-500' : 'fa-volume-up text-green-500' }} text-xl"></i>
                                        <span class="text-[10px] mt-1 text-gray-500 dark:text-gray-300">AUDIO</span>
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
                    </div>
                @endforeach
            @endif
        </div>
        <div
            class="flex flex-col sm:flex-row flex-wrap justify-end items-stretch mt-4 gap-3 sm:gap-4 w-full max-w-full">
            <button wire:click.prevent="markAsSolved()"
                class="py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-lg shadow font-bold text-base w-full sm:w-auto max-w-full min-w-0">
                <i class="fa-solid fa-circle-check mr-1"></i>
                {{ __('Mark as solved') }}
            </button>
            <button wire:click="closeReportDetails"
                class="flex justify-center items-center gap-2 py-2 px-4 text-base font-bold text-gray-700 bg-white rounded-lg border border-gray-400 hover:border-primary-600 hover:text-primary-600 focus:ring-4 focus:ring-primary-200 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:hover:text-primary-400 dark:hover:bg-gray-700 w-full sm:w-auto max-w-full min-w-0">
                <i class="fa-solid fa-xmark"></i>
                {{ __('Discard') }}
            </button>
        </div>
    </div>
</div>
