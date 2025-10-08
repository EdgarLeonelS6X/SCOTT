<div class="w-full mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden flex flex-col h-[428px]">
        <div
            class="flex items-center justify-between px-4 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-terminal text-primary-600"></i>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                    Logs
                </h2>
            </div>
            <div class="flex items-center gap-1 relative" wire:ignore>
                <span class="text-xs text-gray-400 dark:text-gray-500 hidden md:block">
                    {{ __('Real-time registration') }}
                </span>
                <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
                    <button type="button"
                        class="ml-1 flex items-center justify-center rounded-full focus:outline-none bg-transparent p-1 hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors duration-150">
                        <i class="fa-regular fa-circle-question text-lg text-primary-600 dark:text-primary-400"></i>
                    </button>
                    <div x-show="open" x-cloak
                        class="absolute z-10 inline-block px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-xs w-64 right-0 translate-x-0 mt-2 dark:bg-gray-700">
                        <div class="mb-1 font-semibold text-primary-200">{{ __('Log type glossary') }}</div>
                        <div class="flex items-center gap-2 mb-1">
                            <span
                                class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-red-100 text-red-700 border border-opacity-30 border-current text-[10px]">
                                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                            </span>
                            <span class="text-xs text-gray-300">=</span>
                            <span>{{ __('Critical') }}</span>
                        </div>
                        <div class="flex items-center gap-2 mb-1">
                            <span
                                class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-yellow-100 text-yellow-700 border border-opacity-30 border-current text-[10px]">
                                <i class="fa-solid fa-triangle-exclamation text-[11px]"></i>
                            </span>
                            <span class="text-xs text-gray-300">=</span>
                            <span>{{ __('Warning') }}</span>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span
                                class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-gray-100 text-gray-700 border border-opacity-30 border-current text-[10px]">
                                <i class="fa-solid fa-info-circle text-[11px]"></i>
                            </span>
                            <span class="text-xs text-gray-300">=</span>
                            <span>{{ __('Info') }}</span>
                        </div>
                        <div class="mb-1"><span class="font-mono font-bold text-primary-400">CUTV_EU</span>:
                            {{ __('CUTV error on end user') }}
                        </div>
                        <div class="mb-1"><span class="font-mono font-bold text-primary-400">CUTV_OR</span>:
                            {{ __('CUTV error on origin') }}
                        </div>
                        <div class="mb-1"><span class="font-mono font-bold text-primary-400">CHANNEL_DL</span>:
                            {{ __('DTH Downlink error') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="logs-container" wire:poll.2000ms="fetchLogs"
            class="overflow-y-auto font-mono text-[12px] leading-relaxed px-2 py-5 space-y-1 bg-gray-50 dark:bg-gray-800 relative"
            style="scrollbar-width: thin; scrollbar-color: #4b5563 transparent; height: 320px; max-height: 320px; min-height: 0;"
            x-data="{
                atBottom: true,
                showBtn: false,
                newLogs: false,
                lastLogCount: 0,
                scrollToBottom() {
                    const el = $el;
                    el.scrollTo({ top: el.scrollHeight, behavior: 'smooth' });
                },
                isAtBottom() {
                    const el = $el;
                    return Math.abs(el.scrollHeight - el.scrollTop - el.clientHeight) < 2;
                },
                handleScroll() {
                    this.atBottom = this.isAtBottom();
                    this.showBtn = !this.atBottom;
                    if (this.atBottom) this.newLogs = false;
                },
                maybeScrollOnUpdate() {
                    const el = $el;
                    const logCount = el.querySelectorAll('[data-log-row]').length;
                    if (logCount > this.lastLogCount && !this.atBottom) {
                        this.newLogs = true;
                    }
                    this.lastLogCount = logCount;
                    if (this.atBottom) this.scrollToBottom();
                }
            }"
            x-init="scrollToBottom(); atBottom = true; showBtn = false; newLogs = false; lastLogCount = $el.querySelectorAll('[data-log-row]').length; $el.addEventListener('scroll', () => handleScroll())"
            x-effect="maybeScrollOnUpdate()">
            @php
                $tagColors = [
                    'HIGH' => 'text-red-500',
                    'MID' => 'text-yellow-600',
                    'LOW' => 'text-gray-500',
                ];
              @endphp
            @foreach (($logs ?? []) as $i => $log)
                @php
                    $tagColor = $tagColors[strtoupper($log['tag'])] ?? 'text-gray-500';
                    $badgeBg = match (strtoupper($log['tag'])) {
                        'HIGH' => 'bg-red-100 text-red-700',
                        'MID' => 'bg-yellow-100 text-yellow-700',
                        'LOW' => 'bg-gray-100 text-gray-700',
                        default => 'bg-gray-200 text-gray-600',
                    };
                    $rowBg = $i % 2 === 0 ? 'bg-white dark:bg-gray-800/40' : 'bg-gray-100 dark:bg-gray-900/30';
                    $tagIcon = match (strtoupper($log['tag'])) {
                        'HIGH' => 'fa-solid fa-circle-exclamation',
                        'MID' => 'fa-solid fa-triangle-exclamation',
                        'LOW' => 'fa-solid fa-info-circle',
                        default => 'fa-regular fa-circle',
                    };
                @endphp
                <div class="rounded px-2 py-1 mb-1 {{ $rowBg }}" data-log-row>
                    <div class="flex items-center justify-between font-mono text-[11px]">
                        <span class="text-gray-400">{{ $log['date'] }}</span>
                        <span
                            class="px-1.5 py-0.5 rounded {{ $badgeBg }} border border-opacity-30 border-current uppercase font-bold text-[9px] {{ $tagColor }} flex items-center gap-1">
                            <i class="{{ $tagIcon }} text-[11px]"></i>
                            {{ __($log['type']) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mt-0.5 text-[11px]">
                        <img src="{{ $log['channel_image'] }}" alt="img canal"
                            class="w-7 h-7 object-contain object-center transition-all duration-200"
                            style="image-rendering: auto;" loading="lazy">
                        <span class="font-semibold text-gray-700 dark:text-gray-200 truncate max-w-[110px]"
                            title="{{ $log['channel'] }}">{{ __($log['channel']) }}</span>
                    </div>
                    <div class="py-1 text-[10px] text-gray-400 whitespace-normal break-words">
                        {{ __($log['description']) }}
                    </div>
                </div>
            @endforeach
            <button
                x-show="showBtn"
                @click="scrollToBottom(); newLogs = false;"
                :class="{'animate-pulse': newLogs}"
                class="sticky float-right bottom-2 right-2 z-20 bg-primary-600 hover:bg-primary-700 bg-opacity-80 text-white rounded-full shadow-lg p-2 transition-all duration-200 flex items-center justify-center relative"
                style="box-shadow: 0 2px 8px 0 rgba(0,0,0,0.15); margin-left: auto;"
                title="{{ __('Scroll to bottom') }}">
                <i class="fa-solid fa-arrow-down"></i>
                <span x-show="newLogs" class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
            </button>
        </div>
        <div
            class="px-4 py-3.5 text-xs text-gray-500 dark:text-gray-600 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-between">
            <span class="font-mono flex items-center gap-1">
                <i class="fa-solid fa-circle text-primary-500 text-[6px]"></i>
                {{ __('Connected') }}
            </span>
            <span class="text-[10px]">{{ __('auto-scroll enabled') }}</span>
        </div>
    </div>
</div>
