<x-admin-layout :breadcrumbs="[
        [
            'name' => __('Dashboard'),
            'icon' => 'fa-solid fa-wrench',
        ]
    ]">

    <div class="max-w-xl mx-auto">
        <div class="dark:bg-gray-900 dark:border-gray-800 rounded-lg p-2 text-center relative overflow-hidden">
            <div class="relative flex flex-col items-center gap-3">
                <div
                    class="w-20 h-20 flex items-center justify-center rounded-full bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-2xl ring-4 ring-primary-500/20">
                    <i class="fa-solid fa-cube text-3xl"></i>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ config('app.name', 'Laravel') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-300 text-sm tracking-wide">
                    {{ __('OTT Communications System') }}
                </p>
            </div>

            <div class="relative mt-3">
                <span
                    class="px-4 py-1 text-xs font-semibold rounded-full bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300 shadow-sm uppercase tracking-wider">
                    {{ __('Version') }} 2.0
                </span>
            </div>

            <div class="my-5 border-t border-gray-200 dark:border-gray-800"></div>

            <div class="relative text-left space-y-4 w-[270px] mx-auto">
                <div class="flex items-center gap-3">
                    <span
                        class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary-50 dark:bg-primary-900/40 shadow-sm">
                        <i class="fa-solid fa-user text-primary-500 dark:text-primary-400"></i>
                    </span>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('Developer') }}</p>
                        <p class="text-gray-900 dark:text-gray-100 font-medium">Edgar Leonel Acevedo Cuevas</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span
                        class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary-50 dark:bg-primary-900/40 shadow-sm">
                        <i class="fa-solid fa-envelope text-primary-500 dark:text-primary-400"></i>
                    </span>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('Contact') }}</p>
                        <a href="mailto:ecuevas@stargroup.com.mx"
                            class="text-gray-900 dark:text-gray-100 font-medium hover:text-primary-600 dark:hover:text-primary-400">
                            ecuevas@stargroup.com.mx
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span
                        class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary-50 dark:bg-primary-900/40 shadow-sm">
                        <i class="fa-solid fa-calendar-day text-primary-500 dark:text-primary-400"></i>
                    </span>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('Last update') }}</p>
                        <p class="text-gray-900 dark:text-gray-100 font-medium">{{ __('October') }} 2025</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>