<x-app-layout :breadcrumbs="[
    [
        'name' => __('Home'),
        'icon' => 'fa-solid fa-house',
        'route' => route('dashboard'),
    ],
    [
        'name' => __('Reports'),
        'icon' => 'fa-solid fa-paste',
        'route' => route('reports.index'),
    ],
    [
        'name' => __('Report'),
        'icon' => 'fa-solid fa-circle-info',
    ],
]">

    <div class="px-6 mx-auto h-auto">
        <div class="w-full bg-white dark:bg-gray-800 p-6 rounded-lg shadow-2xl mx-auto mb-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div
                    class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-white dark:bg-gray-800 px-4 py-3 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full sm:w-auto">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-file-alt text-gray-800 dark:text-gray-100 text-2xl"></i>
                        <span
                            class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white leading-tight truncate max-w-[150px] sm:max-w-2xl overflow-hidden whitespace-nowrap">
                            {{ $report->category }}
                        </span>
                    </div>
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between sm:justify-end gap-2 w-full sm:w-auto">
                        <div class="flex flex-wrap justify-between sm:justify-start items-center gap-2">
                            @if ($report->type === 'Momentary')
                                <span
                                    class="text-xs font-medium text-white bg-red-500 dark:bg-red-600 px-3 py-1 rounded-lg shadow-2xl">
                                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ __($report->type) }}
                                </span>
                            @elseif ($report->type === 'Hourly')
                                <span
                                    class="text-xs font-medium text-white bg-green-500 dark:bg-green-600 px-3 py-1 rounded-lg shadow-2xl">
                                    <i class="fa-solid fa-clock mr-1"></i> {{ __($report->type) }}
                                </span>
                            @else
                                <span
                                    class="text-xs font-medium text-white bg-blue-500 dark:bg-blue-600 px-3 py-1 rounded-lg shadow-2xl">
                                    <i class="fa-solid fa-forward mr-1"></i> {{ __($report->type) }}
                                </span>
                            @endif
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                {{ __('Folio') }} #{{ $report->id }}
                            </span>
                            @if ($report->type === 'Momentary' && $report->status === 'Revision')
                                <a href="{{ route('reports.edit', ['report' => $report->id]) }}"
                                    class="hidden md:block items-center gap-1 px-2 py-1 text-xs font-medium rounded-lg bg-primary-50 dark:bg-primary-900 text-primary-600 dark:text-primary-300 hover:bg-primary-100 dark:hover:bg-primary-800">
                                    <i class="fa-solid fa-pencil-alt text-xs"></i>
                                    <span>{{ __('Edit this report') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @if ($report->type === 'Momentary' && $report->status === 'Revision')
                    <a href="{{ route('reports.edit', ['report' => $report->id]) }}"
                        class="md:hidden sm:block inline-flex items-center gap-2 px-4 py-2 bg-primary-100 dark:bg-primary-700 text-primary-700 dark:text-primary-300 rounded-lg border border-primary-300 dark:border-primary-600 hover:bg-primary-200 dark:hover:bg-primary-600 shadow-2xl w-full sm:w-auto justify-center sm:justify-start">
                        <i class="fa-solid fa-pencil-alt text-xs"></i>
                        <span class="text-sm font-medium">{{ __('Edit this report') }}</span>
                    </a>
                @endif
                <a href="{{ route('reports.index') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-2xl w-full sm:w-auto justify-center sm:justify-start">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span class="text-sm font-medium">{{ __('Go back') }}</span>
                </a>
            </div>
            <div
                class="p-5 rounded-xl shadow-md transition-transform hover:scale-[1.02]
                bg-gradient-to-br from-pink-500 via-orange-400 to-red-500
                dark:from-blue-900 dark:via-indigo-800 dark:to-purple-900
                text-white dark:text-gray-100 ring-1 ring-white/20 dark:ring-gray-700 hover:shadow-[0_8px_25px_rgba(0,0,0,0.3)]">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex items-center gap-4 w-auto">
                        <img src="{{ $report->reportedBy->profile_photo_url }}" alt="{{ $report->reportedBy->name }}"
                            class="w-12 h-12 rounded-full shadow-2xl">
                        <div>
                            <h4 class="text-sm font-semibold">{{ __('Reported by') }}</h4>
                            <p class="text-base font-bold">{{ $report->reportedBy->name }}</p>
                            <p class="text-xs opacity-80">
                                {{ $report->created_at->format('d/m/Y h:i A') }}
                                @if ($report->updated_at && $report->updated_at->gt($report->created_at))
                                    - {{ $report->updated_at->format('d/m/Y h:i A') }}
                                    <br>
                                    <small>
                                        ({{ __('Last updated') }}: {{ $report->updated_at->diffForHumans() }})
                                    </small>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                        <div
                            class="flex items-center gap-3 bg-white/20 dark:bg-gray-800 px-4 py-3 rounded-lg shadow-md w-full sm:w-auto">
                            <div
                                class="flex items-center justify-center w-10 h-10 bg-white text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-full shadow-md">
                                @if ($report->type === 'Momentary')
                                    <i
                                        class="fa-solid {{ $report->status === 'Resolved' ? 'fa-circle-check text-green-600 dark:text-green-400' : 'fa-magnifying-glass text-yellow-600 dark:text-yellow-400' }} text-lg"></i>
                                @elseif ($report->type === 'Hourly')
                                    <i class="fa-solid fa-folder text-gray-600 dark:text-gray-400 text-lg"></i>
                                @else
                                    <i class="fa-solid fa-forward text-gray-600 dark:text-gray-400 text-lg"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm">{{ __('Status') }}</h4>
                                <p class="text-sm font-bold uppercase">{{ __($report->status) }}</p>
                            </div>
                        </div>
                        @if ($report->type === 'Momentary')
                            <div
                                class="flex items-center gap-3 bg-white/20 dark:bg-gray-800 px-4 py-3 rounded-lg shadow-md w-full">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-white text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-full shadow-md flex-shrink-0">
                                    <i class="fa-solid fa-gear text-lg"></i>
                                </div>
                                <div class="w-full">
                                    <h4 class="text-sm whitespace-normal break-words">{{ __('Under review by') }}</h4>
                                    <p class="text-sm font-bold whitespace-normal break-words">
                                        {{ $report->reviewed_by }}
                                        @if ($report->duration)
                                            <span class="block sm:inline text-xs dark:text-gray-400">
                                                {{ __('Resolved') }}
                                                {{ $report->updated_at->diffForHumans($report->created_at) }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @if ($report->type === 'Momentary')
                <div x-data="{ open: false }"
                    class="mt-6 border border-gray-300 dark:border-gray-700 rounded-xl overflow-hidden shadow-lg">
                    <button @click="open = !open"
                        class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white cursor-pointer px-4 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between w-full bg-white dark:bg-gray-800 border-b dark:border-gray-700 space-y-2 sm:space-y-0">
                        <div
                            class="flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-2 w-full sm:w-auto text-center sm:text-left">
                            <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                                class="fa-solid text-gray-800 dark:text-white"></i>

                            <span
                                class="bg-gray-50 dark:bg-gray-700 border dark:border-white rounded-full px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2 max-w-full sm:max-w-xs w-full sm:w-auto overflow-hidden justify-center sm:justify-start text-center sm:text-left">
                                <i class="fa-solid fa-layer-group"></i>
                                <span class="truncate block w-full" title="{{ $report->category }}">
                                    {{ $report->category }}
                                </span>
                            </span>
                        </div>
                        <span
                            class="bg-primary-100 text-primary-800 text-sm font-medium py-1 px-3 rounded-full text-center sm:text-left w-full sm:w-auto">
                            {{ $report->reportDetails->count() }}
                            {{ $report->reportDetails->count() === 1 ? __('Channel') : __('Channels') }}
                        </span>
                    </button>

                    <div x-show="open" x-collapse
                        class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-6 bg-white dark:bg-gray-800">
                        @foreach ($report->reportDetails->sortBy(fn($detail) => $detail->channel->number) as $detail)
                            <div
                                class="relative overflow-visible flex flex-col px-5 py-3 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-xl space-y-4">
                                @if ($detail->description)
                                    <div x-data="{ openModal: false }" class="absolute -top-3 -right-3 h-6 w-6"
                                        :class="{ 'z-[60]': openModal, 'z-50': !openModal }">
                                        <button @click.stop="openModal = true; $event.stopImmediatePropagation()"
                                            type="button"
                                            class="flex items-center justify-center w-full h-full rounded-full text-sm text-gray-500 dark:text-gray-300">
                                            <i class="fa-solid fa-circle-info text-base"></i>
                                        </button>
                                        <div x-show="openModal" x-transition:enter="ease-out duration-200"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            x-transition:leave="ease-in duration-150"
                                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 p-4"
                                            x-cloak>
                                            <div @click.away="openModal = false; $event.stopPropagation()"
                                                x-show="openModal" x-transition:enter="ease-out duration-200"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="ease-in duration-150"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-95"
                                                class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl w-full max-w-sm p-6 relative">
                                                <button @click.stop="openModal = false"
                                                    class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                                    <i class="fa-solid fa-xmark text-lg"></i>
                                                </button>
                                                <h2
                                                    class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-4">
                                                    {{ __('Description') }}
                                                </h2>
                                                <p
                                                    class="text-gray-700 dark:text-gray-300 text-sm text-center break-words">
                                                    {{ $detail->description }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex items-center gap-2 w-full">
                                    <div class="w-1/3 flex-shrink-0">
                                        <img src="{{ $detail->channel->image }}" alt="{{ $detail->channel->name }}"
                                            title="{{ $detail->channel->number }} {{ $detail->channel->name }}"
                                            class="w-10 h-10 object-contain object-center shadow-sm">
                                    </div>
                                    <div class="w-2/3 flex flex-col justify-center text-end">
                                        <p
                                            class="text-base font-semibold text-gray-900 dark:text-white leading-tight truncate">
                                            {{ $detail->channel->number }} {{ $detail->channel->name }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ $detail->stage->name }}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-around items-center gap-3 px-5 py-3 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
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
                                        title="{{ $detail->protocol === 'DASH' || $detail->protocol === 'HLS/DASH' ? __('Not working on Web Client (DASH)') : __('Working on Web Client (DASH)') }}">
                                        <i
                                            class="fa-solid fa-computer {{ $detail->protocol === 'DASH' || $detail->protocol === 'HLS/DASH' ? 'text-red-500' : 'text-green-500' }} text-xl"></i>
                                        <span class="text-[10px] mt-1 text-gray-500 dark:text-gray-300">DASH</span>
                                    </div>
                                    <div class="flex flex-col items-center tooltip"
                                        title="{{ $detail->protocol === 'HLS' || $detail->protocol === 'HLS/DASH' ? __('Not working on Set Up Box (HLS)') : __('Working on Set Up Box (HLS)') }}">
                                        <i
                                            class="fa-solid fa-tv {{ $detail->protocol === 'HLS' || $detail->protocol === 'HLS/DASH' ? 'text-red-500' : 'text-green-500' }} text-xl"></i>
                                        <span class="text-[10px] mt-1 text-gray-500 dark:text-gray-300">HLS</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            @if ($report->type === 'Functions' && $report->category === 'Chromecast')
                <div class="mt-6 border border-gray-300 dark:border-gray-700 rounded-lg shadow-2xl p-6 bg-white dark:bg-gray-800">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fa-brands fa-chromecast"></i>
                        Chromecast
                    </h3>
                    @if (empty($report->chromecast?->description))
                        <div class="flex items-center gap-2 p-4 bg-green-50 border border-green-300 dark:border-green-700 dark:bg-gray-700 rounded-lg shadow">
                            <i class="fa-solid fa-circle-check text-green-500 dark:text-green-400 text-2xl"></i>
                            <span class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ __('No issues reported for Chromecast.') }}
                            </span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 p-4 bg-yellow-50 border border-yellow-300 dark:border-yellow-700 dark:bg-gray-700 rounded-lg shadow">
                            <i class="fa-solid fa-circle-info text-yellow-500 dark:text-yellow-400 text-2xl"></i>
                            <span class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ $report->chromecast->description }}
                            </span>
                        </div>
                    @endif
                </div>
            @elseif ($report->type === 'Functions' && $report->category === 'Speed Profiles')
                <x-report-profile-table :report="$report" />
            @else
                @php
                    $fixedCategories = [];
                    if ($report->type === 'Hourly') {
                        $fixedCategories = ['CDN TELMEX', 'CDN CEF+', 'STINGRAY'];
                    } elseif ($report->type === 'Functions') {
                        $fixedCategories = ['RESTART', 'CUTV', 'EPG', 'PC'];
                    }

                    $dynamicCategories = $report->reportDetails->pluck('subcategory')->unique()->toArray();

                    $allCategories = collect($fixedCategories)->merge($dynamicCategories)->unique()->values()->toArray();
                @endphp
                @if ($report->type === 'Hourly' || $report->type === 'Functions')
                    @foreach ($allCategories as $fixedCategory)
                        <div x-data="{ open: false }"
                            class="mt-6 border border-gray-300 dark:border-gray-700 rounded-lg shadow-2xl">
                            <h3 @click="open = !open"
                                class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white cursor-pointer px-4 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between border-b dark:border-gray-700 space-y-2 sm:space-y-0">
                                <div
                                    class="flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-2 w-full sm:w-auto">
                                    <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                                        class="fa-solid text-gray-800 dark:text-white"></i>
                                    <span
                                        class="bg-gray-50 dark:bg-gray-700 border dark:border-white rounded-full px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2 max-w-full sm:max-w-xs w-fit overflow-hidden">
                                        <i class="fa-solid fa-layer-group"></i>
                                        <span class="truncate" title="{{ $fixedCategory }}">
                                            {{ $fixedCategory }}
                                        </span>
                                    </span>
                                </div>
                                <span
                                    class="bg-primary-100 text-primary-800 text-sm font-medium py-1 px-3 rounded-full text-center sm:text-left w-full sm:w-auto">
                                    @if (in_array($fixedCategory, $allCategories) &&
                                            $report->reportDetails->where('subcategory', $fixedCategory)->count() > 0)
                                        {{ $report->reportDetails->where('subcategory', $fixedCategory)->count() }}
                                        {{ $report->reportDetails->where('subcategory', $fixedCategory)->count() === 1 ? __('Channel') : __('Channels') }}
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400 italic">
                                            {{ __('All clear in this category') }}
                                        </span>
                                    @endif
                                </span>
                            </h3>
                            <div x-show="open" x-collapse
                                class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-6 bg-white dark:bg-gray-800">
                                @if ($report->reportDetails->where('subcategory', $fixedCategory)->count() > 0)
                                    @foreach ($report->reportDetails->where('subcategory', $fixedCategory)->sortBy(fn($detail) => $detail->channel->number) as $detail)
                                        <div
                                            class="relative flex flex-col px-5 py-3 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-xl shadow-2xl space-y-4">
                                            @if ($detail->description)
                                                <div x-data="{ openModal: false }" class="absolute -top-3 -right-3 h-6 w-6"
                                                    :class="{ 'z-[60]': openModal, 'z-50': !openModal }">
                                                    <button
                                                        @click.stop="openModal = true; $event.stopImmediatePropagation()"
                                                        type="button"
                                                        class="flex items-center justify-center w-full h-full rounded-full text-sm text-gray-500 dark:text-gray-300">
                                                        <i class="fa-solid fa-circle-info text-base"></i>
                                                    </button>
                                                    <div x-show="openModal" x-transition:enter="ease-out duration-200"
                                                        x-transition:enter-start="opacity-0"
                                                        x-transition:enter-end="opacity-100"
                                                        x-transition:leave="ease-in duration-150"
                                                        x-transition:leave-start="opacity-100"
                                                        x-transition:leave-end="opacity-0"
                                                        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 p-4"
                                                        x-cloak>
                                                        <div @click.away="openModal = false; $event.stopPropagation()"
                                                            x-show="openModal" x-transition:enter="ease-out duration-200"
                                                            x-transition:enter-start="opacity-0 scale-95"
                                                            x-transition:enter-end="opacity-100 scale-100"
                                                            x-transition:leave="ease-in duration-150"
                                                            x-transition:leave-start="opacity-100 scale-100"
                                                            x-transition:leave-end="opacity-0 scale-95"
                                                            class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl w-full max-w-sm p-6 relative">
                                                            <button @click.stop="openModal = false"
                                                                class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                                                <i class="fa-solid fa-xmark text-lg"></i>
                                                            </button>
                                                            <h2
                                                                class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-4">
                                                                {{ __('Description') }}
                                                            </h2>
                                                            <p
                                                                class="text-gray-700 dark:text-gray-300 text-sm text-center break-words">
                                                                {{ $detail->description }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="flex items-start">
                                                <div class="flex items-center gap-2 w-full">
                                                    <div class="w-1/3 flex-shrink-0">
                                                        <img src="{{ $detail->channel->image }}"
                                                            alt="{{ $detail->channel->name }}"
                                                            class="w-10 h-10 object-contain object-center shadow-sm">
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
                                    class="flex justify-around items-center gap-3 px-5 py-3 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
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
                                        title="{{ $detail->protocol === 'DASH' || $detail->protocol === 'HLS/DASH' ? __('Not working on Web Client (DASH)') : __('Working on Web Client (DASH)') }}">
                                        <i
                                            class="fa-solid fa-computer {{ $detail->protocol === 'DASH' || $detail->protocol === 'HLS/DASH' ? 'text-red-500' : 'text-green-500' }} text-xl"></i>
                                        <span class="text-[10px] mt-1 text-gray-500 dark:text-gray-300">DASH</span>
                                    </div>
                                    <div class="flex flex-col items-center tooltip"
                                        title="{{ $detail->protocol === 'HLS' || $detail->protocol === 'HLS/DASH' ? __('Not working on Set Up Box (HLS)') : __('Working on Set Up Box (HLS)') }}">
                                        <i
                                            class="fa-solid fa-tv {{ $detail->protocol === 'HLS' || $detail->protocol === 'HLS/DASH' ? 'text-red-500' : 'text-green-500' }} text-xl"></i>
                                        <span class="text-[10px] mt-1 text-gray-500 dark:text-gray-300">HLS</span>
                                    </div>
                                </div>
                                            @if ($fixedCategory === 'CUTV' && $detail->reportContentLosses->isNotEmpty())
                                                <div x-data="{ showLosses: false }" class="mt-5 w-full text-center">
                                                    <div class="flex justify-center">
                                                        <button @click="showLosses = !showLosses"
                                                            class="text-[10px] lg:text-[11px] text-primary-600 dark:text-primary-400 font-medium flex justify-center items-center gap-1 hover:underline">
                                                            <i class="fa-solid fa-clock text-xs"></i>
                                                            {{ __('View content loss intervals') }}
                                                            <i :class="showLosses ? 'fa-chevron-up' : 'fa-chevron-down'"
                                                                class="fa-solid ml-1 text-xs transition-transform duration-200"></i>
                                                        </button>
                                                    </div>

                                                    <div x-show="showLosses" x-collapse class="mt-4 overflow-x-auto">
                                                        <div
                                                            class="inline-block min-w-full align-middle border border-gray-200 dark:border-gray-700 lg:rounded-lg overflow-hidden bg-white dark:bg-gray-800 shadow-sm">
                                                            <table
                                                                class="min-w-full text-[10px] lg:text-[11px] text-gray-700 dark:text-gray-300">
                                                                <thead>
                                                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                                                        <th class="py-2 px-3 text-center">
                                                                            {{ __('Start time') }}</th>
                                                                        <th class="py-2 px-3 text-center">
                                                                            {{ __('End time') }}</th>
                                                                        <th class="py-2 px-3 text-center">
                                                                            {{ __('Duration') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($detail->reportContentLosses as $loss)
                                                                        @php
                                                                            $start = \Carbon\Carbon::parse(
                                                                                $loss->start_time,
                                                                            );
                                                                            $end = \Carbon\Carbon::parse($loss->end_time);
                                                                            $diff = $start->diff($end);
                                                                            $days = $diff->format('%a');
                                                                            $hours = $diff->format('%H');
                                                                            $minutes = $diff->format('%I');

                                                                            $duration =
                                                                                ($days > 0 ? "{$days}d " : '') .
                                                                                "{$hours}h {$minutes}m";
                                                                        @endphp
                                                                        <tr
                                                                            class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                                            <td class="py-2 px-3 text-center">
                                                                                {{ $start->format('d/m/Y H:i') }}</td>
                                                                            <td class="py-2 px-3 text-center">
                                                                                {{ $end->format('d/m/Y H:i') }}</td>
                                                                            <td
                                                                                class="py-2 px-3 text-center font-semibold">
                                                                                {{ $duration }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div
                                        class="col-span-full flex flex-col items-center p-6 bg-green-50 border border-green-300 dark:border-green-700 dark:bg-gray-700 rounded-lg shadow-2xl">
                                        <i
                                            class="fa-solid fa-circle-check text-green-500 dark:text-green-400 text-3xl mb-3"></i>
                                        <span
                                            class="block text-center text-base font-semibold text-gray-900 dark:text-white">
                                            {{ __('No issues reported in this category') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
