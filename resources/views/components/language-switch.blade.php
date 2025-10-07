<form method="POST" action="{{ route('language.switch') }}" class="relative">
    @csrf
    <label for="locale" class="sr-only">{{ __('Language') }}</label>
    <div
        class="flex items-center justify-center gap-2 px-4 py-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm transition focus-within:ring-2 focus-within:ring-primary-200 dark:focus-within:ring-primary-700 min-w-[120px] relative">
        @if(app()->getLocale() === 'en')
            <img src="https://flagcdn.com/w20/us.png" alt="{{ __('English') }}" class="w-5 h-3 shadow">
        @else
            <img src="https://flagcdn.com/w20/mx.png" alt="{{ __('Spanish') }}" class="w-5 h-3 shadow">
        @endif

        <select id="locale" name="locale" onchange="this.form.submit()"
            class="appearance-none bg-transparent border-0 pl-2 pr-6 text-sm font-semibold text-gray-700 dark:text-gray-100 focus:outline-none cursor-pointer min-w-[70px] focus:ring-0 focus:border-0">
            <option value="en" @selected(app()->getLocale() === 'en') style="color:#1f2937;" class="dark:text-gray-100">{{ __('English') }}</option>
            <option value="es" @selected(app()->getLocale() === 'es') style="color:#1f2937;" class="dark:text-gray-100">{{ __('Español') }}</option>
        </select>
    </div>
</form>
