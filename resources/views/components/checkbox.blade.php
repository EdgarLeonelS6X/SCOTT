@php
    $colorClass = 'text-black focus:ring-black dark:text-white dark:focus:ring-white';
    if (Auth::check()) {
        if (Auth::user()->area === 'DTH') {
            $colorClass = 'text-secondary-600 focus:ring-secondary-500 dark:focus:ring-secondary-600';
        } elseif (Auth::user()->area === 'OTT') {
            $colorClass = 'text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600';
        }
    }
@endphp
<input type="checkbox" {!! $attributes->merge(['class' => 'w-4 h-4 bg-gray-100 border-gray-300 rounded dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 ' . $colorClass]) !!}>
