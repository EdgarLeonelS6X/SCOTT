<div class="w-full mx-auto">
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden flex flex-col h-[428px]">
    <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-terminal text-primary-600"></i>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
        Logs
        </h2>
      </div>
      <div class="flex items-center gap-3 relative">
        <span class="text-xs text-gray-400 dark:text-gray-500 hidden md:block">
          {{ __('Real-time registration') }}
        </span>

        <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
          <button type="button" class="ml-1 text-blue-500 hover:text-blue-700 focus:outline-none px-2 py-1 rounded-full bg-blue-50 dark:bg-blue-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" /></svg>
          </button>
          <div x-show="open" x-cloak class="absolute z-10 inline-block px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-xs w-64 left-1/2 -translate-x-1/2 mt-2 dark:bg-gray-700">
            <div class="mb-1 font-semibold text-blue-200">Glosario de Tipos de Log</div>
            <div><span class="font-mono font-bold text-blue-400">CUTV_EU</span>: Error en Catch-UP sobre usuario final</div>
            <div><span class="font-mono font-bold text-blue-400">CUTV_OR</span>: Error en Catch-UP origen</div>
            <div><span class="font-mono font-bold text-blue-400">CHANNEL_DL</span>: Error sobre el downlink de canal DTH</div>
          </div>
        </div>

      </div>
    </div>

    <div
      id="logs-container"
      wire:poll.2000ms="fetchLogs"
  class="overflow-y-auto font-mono text-[13px] leading-relaxed px-4 py-5 space-y-1 bg-gray-50 dark:bg-gray-800"
      style="scrollbar-width: thin; scrollbar-color: #4b5563 transparent; height: 320px; max-height: 320px; min-height: 0;"
      x-data="{ scrollToBottom() { const el = $el; el.scrollTop = el.scrollHeight; } }"
      x-init="scrollToBottom()"
      x-effect="scrollToBottom()"
    >
      @php
        $tagColors = [
          'HIGH' => 'text-red-400',
          'MID' => 'text-yellow-400',
          'LOW' => 'text-blue-400',
        ];
      @endphp
      @foreach (($logs ?? []) as $i => $log)
        @php 
          $tagColor = $tagColors[strtoupper($log['tag'])] ?? 'text-gray-500';
          $badgeBg = match(strtoupper($log['tag'])) {
            'HIGH' => 'bg-red-100 text-red-600',
            'MID' => 'bg-yellow-100 text-yellow-700',
            'LOW' => 'bg-blue-100 text-blue-700',
            default => 'bg-gray-200 text-gray-600',
          };
          $rowBg = $i % 2 === 0 ? 'bg-white dark:bg-gray-800/40' : 'bg-gray-100 dark:bg-gray-900/30';
        @endphp
        <div class="rounded px-2 py-1 mb-1 {{ $rowBg }}">
          <!-- Fila 1: Fecha (izq) y Tag (der) -->
          <div class="flex items-center justify-between font-mono text-[11px]">
            <span class="text-gray-400">{{ $log['date'] }}</span>
            <span class="px-1.5 py-0.5 rounded {{ $badgeBg }} border border-opacity-30 border-current uppercase font-bold text-[9px]">{{ $log['type'] }}</span>
          </div>
          <!-- Fila 2: Imagen canal y nombre -->
          <div class="flex items-center gap-2 mt-0.5 text-[11px]">
            @if (!empty($log['channel_image']))
              <img src="{{ $log['channel_image'] }}" alt="img canal" class="w-6 h-6 rounded shadow border border-gray-200 dark:border-gray-700 bg-white object-cover" loading="lazy">
            @else
              <div class="w-6 h-6 rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400 text-xs">?</div>
            @endif
            <span class="font-semibold text-gray-700 dark:text-gray-200 truncate max-w-[110px]" title="{{ $log['channel'] }}">{{ $log['channel'] }}</span>
          </div>
          <!-- Fila 3: Descripción -->
          <div class="pl-2 text-[11px] text-gray-600 whitespace-normal break-words">
            {{ $log['description'] }}
          </div>
        </div>
      @endforeach
    </div>

    <div class="px-4 py-2 text-xs text-gray-500 dark:text-gray-600 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-between">
      <span class="font-mono flex items-center gap-1">
        <i class="fa-solid fa-circle text-green-500 text-[6px]"></i>
        {{ __('Connected') }}
      </span>
      <span class="text-[10px]">{{ __('auto-scroll enabled') }}</span>
    </div>
  </div>
</div>
