<nav
    class="fixed top-0 z-50 w-full bg-white/90 dark:bg-gray-900/90 backdrop-blur border-b border-gray-200 dark:border-gray-800 shadow-sm">
    <div class="px-4 py-2 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button x-on:click="sidebarOpen = !sidebarOpen" aria-controls="logo-sidebar" type="button"
                class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-200 dark:focus:ring-primary-700 sm:hidden transition">
                <span class="sr-only">Open sidebar</span>
                <i class="fa-solid fa-bars text-lg"></i>
            </button>

            <a class="flex items-center gap-2 font-semibold text-gray-900 dark:text-white text-lg"
                href="{{ route('dashboard') }}">
                <span
                    class="inline-flex items-center justify-center w-8 h-8 rounded bg-primary-100 dark:bg-primary-900">
                    <i class="fa-solid fa-cube text-primary-600 dark:text-primary-400 text-xl"></i>
                </span>
                <span class="leading-tight">
                    {{ config('app.name', 'Laravel') }}
                </span>
            </a>

            <span class="hidden sm:inline text-xs font-normal text-gray-500 dark:text-gray-400 ml-2">
                {{ __('OTT Communications System') }}
            </span>
        </div>

        <div class="flex items-center gap-2">
            <x-theme-toggle />

            <x-user-dropdown />
        </div>
    </div>
</nav>
