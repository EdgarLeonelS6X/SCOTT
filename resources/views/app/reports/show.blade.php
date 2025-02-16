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
                    <span
                        class="text-xs font-medium text-white bg-red-500 dark:bg-red-600 px-3 py-1 rounded-full shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                        {{ $report->type }}
                    </span>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('Folio') }} #{{ $report->id }}
                    </span>
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
                                <i
                                    class="fa-solid {{ $report->status === 'Resolved' ? 'fa-circle-check text-green-600 dark:text-green-400' : 'fa-magnifying-glass text-yellow-600 dark:text-yellow-400' }} text-lg"></i>
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
                                    {{ $report->attended_by }}
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
                                                echo $hours . ' hours ' . ($minutes > 0 ? $minutes . ' minutes' : '');
                                            }
                                        @endphp
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div x-data="{ open: false }"
                class="mt-6 border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden shadow-2xl">
                <h3 @click="open = !open"
                    class="text-lg font-semibold text-gray-800 dark:text-white cursor-pointer flex items-center justify-between px-4 py-3">
                    <div>
                        <i class="fa-solid fa-layer-group text-xl mr-1.5"></i>
                        {{ __('This report contains') }}
                        <span class="bg-primary-100 text-primary-800 text-sm font-medium py-1 px-3 rounded-full">
                            {{ $report->reportDetails->count() }}
                            {{ $report->reportDetails->count() === 1 ? __('channel') : __('channels') }}
                        </span>
                    </div>
                    <i :class="open ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"
                        class="text-gray-500 transition-transform duration-300 ease-in-out"></i>
                </h3>
                <div x-show="open" x-collapse
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 p-4 bg-white dark:bg-gray-800">
                    @foreach ($report->reportDetails as $detail)
                        <div
                            class="flex flex-col items-center p-6 bg-gray-50 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-xl hover:scale-[1.02] relative">
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
                            <span class="block mt-1 text-xs text-gray-500 dark:text-gray-400">
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
        </div>
    </div>
</x-app-layout>
