<x-app-layout :breadcrumbs="[
    [
        'name' => __('Dashboard'),
        'icon' => 'fa-solid fa-house',
        'route' => route('dashboard'),
    ],
    [
        'name' => __('Reports'),
        'icon' => 'fa-solid fa-paste',
        'route' => route('reports.index'),
    ],
    [
        'name' => $report->category . ' (' . $report->created_at->format('d/m/Y h:i A') . ')',
        'icon' => 'fa-solid fa-circle-info',
    ],
]">

    <div class="px-6 mx-auto h-auto">
        <div class="w-full bg-white dark:bg-gray-800 p-6 rounded-lg shadow-2xl mx-auto mb-4">
            <div class="flex justify-between items-center mb-6">
                <div
                    class="flex items-center gap-4 bg-white dark:bg-gray-800 px-4 py-2 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700">
                    <i class="fa-solid fa-file-alt text-gray-800 dark:text-gray-100 text-2xl"></i>
                    <span class="text-xl font-semibold text-gray-900 dark:text-white leading-tight">
                        {{ $report->category }}
                    </span>
                    @if ($report->type === 'Momentary')
                        <span
                            class="text-xs font-medium text-white bg-red-500 dark:bg-red-600 px-3 py-1 rounded-lg shadow-2xl">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                            {{ $report->type }}
                        </span>
                    @elseif ($report->type === 'Hourly')
                        <span
                            class="text-xs font-medium text-white bg-green-500 dark:bg-green-600 px-3 py-1 rounded-lg shadow-2xl">
                            <i class="fa-solid fa-clock mr-1"></i>
                            {{ $report->type }}
                        </span>
                    @else
                        <span
                            class="text-xs font-medium text-white bg-blue-500 dark:bg-blue-600 px-3 py-1 rounded-lg shadow-2xl">
                            <i class="fa-solid fa-forward mr-1"></i>
                            {{ $report->type }}
                        </span>
                    @endif
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            {{ __('Folio') }} #{{ $report->id }}
                        </span>
                        @if (
                            $report->reported_by == auth()->id() &&
                                $report->id == auth()->user()->lastReport?->id &&
                                $report->type === 'Momentary')
                            <a href="{{ route('reports.edit', ['report' => $report->id]) }}"
                                class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-lg bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-300 hover:bg-purple-100 dark:hover:bg-purple-800 group">
                                <i
                                    class="fa-solid fa-pencil-alt text-xs transition-transform group-hover:scale-110"></i>
                                <span class="hidden sm:inline">
                                    {{ __('Edit this report') }}
                                </span>
                            </a>
                        @endif
                    </div>
                </div>
                <a href="{{ route('reports.index') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-2xl">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span class="text-sm font-medium">
                        {{ __('Go back') }}
                    </span>
                </a>
            </div>
            <div
                class="p-5 rounded-xl shadow-md transition-transform hover:scale-[1.02]
                    bg-gradient-to-br from-pink-500 via-orange-400 to-red-500
                    dark:from-blue-900 dark:via-indigo-800 dark:to-purple-900
                    text-white dark:text-gray-100 ring-1 ring-white/20 dark:ring-gray-700 hover:shadow-[0_8px_25px_rgba(0,0,0,0.3)]">
                <div class="flex justify-between items-center gap-6">
                    <div class="flex items-center gap-4 flex-1">
                        <img src="{{ $report->reportedBy->profile_photo_url }}" alt="{{ $report->reportedBy->name }}"
                            class="w-12 h-12 rounded-full shadow-2xl">
                        <div>
                            <h4 class="text-sm font-semibold text-white dark:text-gray-300">
                                {{ __('Reported by') }}
                            </h4>
                            <p class="text-base font-bold text-white dark:text-gray-100">
                                {{ $report->reportedBy->name }}
                            </p>
                            <p class="text-xs text-gray-200 dark:text-gray-300 opacity-80">
                                {{ $report->created_at->format('d/m/Y h:i A') }}
                                @if ($report->updated_at && $report->updated_at != $report->created_at)
                                    - {{ $report->updated_at->format('d/m/Y h:i A') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div
                            class="flex items-center gap-3 bg-white/20 dark:bg-gray-800 px-4 py-3 rounded-lg shadow-md">
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
                                <h4 class="text-sm text-white dark:text-gray-300">
                                    {{ __('Status') }}
                                </h4>
                                <p class="text-sm font-bold uppercase">
                                    {{ $report->status }}
                                </p>
                            </div>
                        </div>
                        @if ($report->type === 'Momentary')
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
                                        {{ $report->reviewed_by }}
                                        @if ($report->duration)
                                            - {{ __('Resolved in') }}
                                            @php
                                                $createdAt = \Carbon\Carbon::parse($report->created_at);
                                                $resolvedAt = \Carbon\Carbon::parse($report->updated_at);
                                                $totalSeconds = intval($createdAt->diffInSeconds($resolvedAt));

                                                if ($totalSeconds < 60) {
                                                    echo $totalSeconds . ' seconds';
                                                } elseif ($totalSeconds < 3600) {
                                                    echo floor($totalSeconds / 60) . ' minutes';
                                                } else {
                                                    $hours = floor($totalSeconds / 3600);
                                                    $minutes = floor(($totalSeconds % 3600) / 60);
                                                    echo $hours .
                                                        ' hours ' .
                                                        ($minutes > 0 ? $minutes . ' minutes' : '');
                                                }
                                            @endphp
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
                        class="text-lg font-semibold text-gray-800 dark:text-white cursor-pointer px-4 py-3 flex items-center justify-between w-full bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                                class="fa-solid text-gray-800 dark:text-white"></i>
                            <span
                                class="bg-gray-50 dark:bg-gray-700 border dark:border-white rounded-full px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-layer-group"></i> {{ $report->category }}
                            </span>
                        </div>
                        <span class="bg-primary-100 text-primary-800 text-sm font-medium py-1 px-3 rounded-full">
                            {{ $report->reportDetails->count() }}
                            {{ $report->reportDetails->count() === 1 ? __('channel') : __('channels') }}
                        </span>
                    </button>
                    <div x-show="open" x-collapse
                        class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-6 bg-white dark:bg-gray-800">
                        @foreach ($report->reportDetails as $detail)
                            <div
                                class="relative flex flex-col px-5 py-3 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-xl shadow-2xl space-y-4">
                                <div class="absolute -top-3 -right-3 z-20">
                                    @if ($detail->description)
                                        <button data-popover-target="popover-{{ $detail->id }}" type="button"
                                            class="flex items-center justify-center w-6 h-6 rounded-full text-sm text-gray-500 dark:text-gray-300">
                                            <i class="fa-solid fa-circle-info text-base"></i>
                                        </button>
                                    @endif
                                </div>
                                <div data-popover id="popover-{{ $detail->id }}" role="tooltip"
                                    class="absolute z-30 invisible inline-block w-64 text-sm text-gray-600 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-800"
                                    style="top: -10px; right: -270px;">
                                    <div
                                        class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700 text-center">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">
                                            {{ __('Description') }}
                                        </h3>
                                    </div>
                                    <div class="px-3 py-2 text-center">
                                        <p>{{ $detail->description }}</p>
                                    </div>
                                </div>
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
                    </div>
                </div>
            @endif
            @php
                $fixedCategories = [];
                if ($report->type === 'Hourly') {
                    $fixedCategories = ['CDN TELMEX', 'CDN CEF+', 'STINGRAY'];
                } elseif ($report->type === 'Functions') {
                    $fixedCategories = ['RESTART', 'CUTV', 'EPG', 'PC'];
                }
                $existingCategories = $report->reportDetails->pluck('subcategory')->unique()->toArray();
            @endphp
            @if ($report->type === 'Hourly' || $report->type === 'Functions')
                @foreach ($fixedCategories as $fixedCategory)
                    <div x-data="{ open: false }"
                        class="mt-6 border border-gray-300 dark:border-gray-700 rounded-lg shadow-2xl">
                        <h3 @click="open = !open"
                            class="text-lg font-semibold text-gray-800 dark:text-white cursor-pointer px-4 py-3 flex items-center justify-between border-b dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                                    class="fa-solid text-gray-800 dark:text-white"></i>
                                <span
                                    class="bg-gray-50 dark:bg-gray-700 border dark:border-white rounded-full px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-layer-group"></i> {{ $fixedCategory }}
                                </span>
                            </div>
                            <span class="bg-primary-100 text-primary-800 text-sm font-medium py-1 px-3 rounded-full">
                                @if (in_array($fixedCategory, $existingCategories) &&
                                        $report->reportDetails->where('subcategory', $fixedCategory)->count() > 0)
                                    {{ $report->reportDetails->where('subcategory', $fixedCategory)->count() }}
                                    {{ $report->reportDetails->where('subcategory', $fixedCategory)->count() === 1 ? __('channel') : __('channels') }}
                                @else
                                    <span class="text-gray-500 dark:text-gray-400 italic">
                                        {{ __('All clear in this category') }}
                                    </span>
                                @endif
                            </span>
                        </h3>
                        <div x-show="open" x-collapse
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6 p-6 bg-white dark:bg-gray-800">
                            @if (in_array($fixedCategory, $existingCategories))
                                @foreach ($report->reportDetails->where('subcategory', $fixedCategory) as $detail)
                                    <div
                                        class="relative flex flex-col px-5 py-3 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-xl shadow-2xl space-y-4">
                                        <div class="absolute -top-3 -right-3 z-20 h-6 w-6">
                                            @if ($detail->description)
                                                <button data-popover-target="popover-{{ $detail->id }}"
                                                    type="button"
                                                    class="flex items-center justify-center w-full h-full rounded-full text-sm text-gray-500 dark:text-gray-300">
                                                    <i class="fa-solid fa-circle-info text-base"></i>
                                                </button>
                                            @endif
                                        </div>
                                        <div data-popover id="popover-{{ $detail->id }}" role="tooltip"
                                            class="absolute z-30 invisible inline-block w-64 text-sm text-gray-600 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-800"
                                            style="top: -10px; right: -270px;">
                                            <div
                                                class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700 text-center">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                                    {{ __('Description') }}</h3>
                                            </div>
                                            <div class="px-3 py-2 text-center">
                                                <p>{{ $detail->description }}</p>
                                            </div>
                                        </div>
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
                                                <span
                                                    class="text-[10px] mt-1 text-gray-500 dark:text-gray-300">DASH</span>
                                            </div>
                                            <div class="flex flex-col items-center tooltip"
                                                title="{{ $detail->protocol === 'HLS' || $detail->protocol === 'DASH/HLS' ? __('Not working on Set Up Box (HLS)') : __('Working on Set Up Box (HLS)') }}">
                                                <i
                                                    class="fa-solid fa-tv {{ $detail->protocol === 'HLS' || $detail->protocol === 'DASH/HLS' ? 'text-red-500' : 'text-green-500' }} text-xl"></i>
                                                <span
                                                    class="text-[10px] mt-1 text-gray-500 dark:text-gray-300">HLS</span>
                                            </div>
                                        </div>
                                        @if ($fixedCategory === 'CUTV' && $detail->reportContentLosses->isNotEmpty())
                                            <div x-data="{ showLosses: false }" class="mt-5 w-full text-center">
                                                <div class="flex justify-center">
                                                    <button @click="showLosses = !showLosses"
                                                        class="text-[11px] text-primary-600 dark:text-primary-400 font-medium flex justify-center items-center gap-1 hover:underline">
                                                        <i class="fa-solid fa-clock text-xs"></i>
                                                        {{ __('View content loss intervals') }}
                                                        <i :class="showLosses ? 'fa-chevron-up' : 'fa-chevron-down'"
                                                            class="fa-solid ml-1 text-xs transition-transform duration-200"></i>
                                                    </button>
                                                </div>
                                                <div x-show="showLosses" x-collapse
                                                    class="mt-3 space-y-2 text-[11px] text-gray-600 dark:text-gray-300">
                                                    @foreach ($detail->reportContentLosses as $loss)
                                                        @php
                                                            $start = \Carbon\Carbon::parse($loss->start_time);
                                                            $end = \Carbon\Carbon::parse($loss->end_time);
                                                        @endphp
                                                        <div
                                                            class="px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                                                            @if ($start->format('Y-m-d') === $end->format('Y-m-d'))
                                                                <div>{{ $start->translatedFormat('d M Y') }} |
                                                                    {{ $start->format('H:i') }} →
                                                                    {{ $end->format('H:i') }}</div>
                                                            @else
                                                                <div>{{ $start->translatedFormat('d M Y H:i') }} →
                                                                    {{ $end->translatedFormat('d M Y H:i') }}</div>
                                                            @endif
                                                        </div>
                                                    @endforeach
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
                                    <span class="block text-base font-semibold text-gray-900 dark:text-white">
                                        {{ __('No issues reported in this category') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
