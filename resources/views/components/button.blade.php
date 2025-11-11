@php
    $colorClass = 'bg-black text-white hover:bg-opacity-90 dark:bg-white dark:text-black dark:hover:bg-opacity-80';
    if (Auth::check()) {
        if (Auth::user()->area === 'DTH') {
            $colorClass = 'bg-secondary-700 text-white hover:bg-secondary-800 focus:ring-secondary-300 dark:bg-secondary-600 dark:text-white dark:hover:bg-secondary-700 dark:focus:ring-secondary-800';
        } elseif (Auth::user()->area === 'OTT') {
            $colorClass = 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-300 dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700 dark:focus:ring-primary-800';
        }
    }
@endphp
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'font-medium rounded-lg text-base px-5 py-2.5 text-center ' . $colorClass]) }}>
    {{ $slot }}
</button>
