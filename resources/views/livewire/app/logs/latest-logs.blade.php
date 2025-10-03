<div class="w-full mx-auto">
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden h-[428px] flex flex-col">
    <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200 dark:border-gray-700">
      <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2 font-mono">
        <i class="fa-solid fa-terminal text-primary-600"></i>
        Logs
      </h2>
      <span class="text-xs text-gray-400 dark:text-gray-500 md:block hidden font-mono">
        {{ __('Real-time registration') }}
      </span>
    </div>

    <div
      id="logs-container"
      class="flex-1 overflow-y-auto font-mono text-sm leading-relaxed px-3 py-6 flex flex-col justify-end"
      style="scrollbar-width: none;">
      {{-- <p class="text-gray-400 text-xs">$ tail -f /var/log/app.log</p> --}}
      <p class="text-red-400 text-xs">[03/10/25 14:21:16] [DTH] 101 Azteca UNO: Lost frames</p>
      <p class="text-yellow-400 text-xs">[03/10/2025 14:21:16] [CUTV] 103 Imagen TV: 11:00 - 13:00</p>
      <p class="text-blue-400 text-xs">[03/10/2025 14:21:16] [TRANSPONDER] ULTRA: Loss of transponders</p>
    </div>
  </div>
</div>
