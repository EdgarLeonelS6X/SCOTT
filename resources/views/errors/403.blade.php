<x-app-layout>
    <div class="bg-gray-200 dark:bg-gray-900 flex items-center justify-center px-4">
        <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-lg p-10 max-w-xl w-full text-center">
            <div class="flex flex-col items-center gap-6">
                <i class="fas fa-ban text-primary-600 dark:text-primary-400 text-6xl"></i>
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-center gap-2 text-2xl sm:text-3xl font-bold">
                    <span class="text-gray-800 dark:text-white">
                        {{ __('Access Denied') }}
                    </span>
                    <span class="text-primary-600 dark:text-primary-400">
                        403
                    </span>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base">
                    {{ __("Oops! You don't have permission to access this page.") }}
                </p>
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center gap-2 text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:focus:ring-primary-800 font-medium rounded-md text-sm px-5 py-2.5 transition">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Go Back') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
