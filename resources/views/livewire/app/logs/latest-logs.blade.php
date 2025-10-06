<div class="w-full mx-auto">
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden flex flex-col h-[428px]">
    <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-terminal text-primary-600"></i>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
        Logs
        </h2>
      </div>
      <div class="flex items-center gap-3">
        <span class="text-xs text-gray-400 dark:text-gray-500 hidden md:block">
          {{ __('Real-time registration') }}
        </span>
      </div>
    </div>

    <div
      id="logs-container"
      class="flex-1 overflow-y-auto font-mono text-[13px] leading-relaxed px-4 py-5 flex flex-col justify-end space-y-1 bg-gray-50 dark:bg-gray-800"
      style="scrollbar-width: thin; scrollbar-color: #4b5563 transparent;">

      <p class="text-gray-400 font-mono tracking-tight text-xs animate-pulse">SCOTT:~$ github.com/EdgarLSyxz</p>
      <p class="text-red-400 font-mono tracking-tight text-xs">[03/10/2025 14:21:16] [DTH] 101 Azteca UNO: Lost frames</p>
      <p class="text-yellow-400 font-mono tracking-tight text-xs">[03/10/2025 14:21:16] [CUTV] 103 Imagen TV: 11:00 - 13:00</p>
      <p class="text-blue-400 font-mono tracking-tight text-xs">[03/10/2025 14:21:16] [TRANSPONDER] ULTRA: Loss of transponders</p>
    </div>

    <div class="px-4 py-2 text-[11px] text-gray-500 dark:text-gray-600 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-between">
      <span class="font-mono flex items-center gap-1">
        <i class="fa-solid fa-circle text-green-500 text-[6px]"></i>
        {{ __('Connected') }}
      </span>
      <span class="text-[10px]">{{ __('auto-scroll enabled') }}</span>
    </div>
  </div>
</div>
