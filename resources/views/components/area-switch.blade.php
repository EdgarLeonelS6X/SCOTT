@php
    $user = Auth::user();
    $isMaster = $user && $user->role === 'master';
    $currentArea = $user?->area;
    $nextArea = $currentArea === 'OTT' ? 'DTH' : 'OTT';
    $label = $currentArea === 'OTT' ? __('Switch to DTH') : __('Switch to OTT');
@endphp
@if($isMaster)
    <a href="{{ route('user.switch-area', ['area' => $nextArea]) }}"
        class="flex items-center justify-center w-9 h-9 rounded-full text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 transition shadow-sm {{ $currentArea === 'DTH' ? 'focus:ring-secondary-300 dark:focus:ring-secondary-600' : 'focus:ring-primary-300 dark:focus:ring-primary-600' }}">
        <i class="fa-solid fa-arrows-rotate text-lg hover:animate-spin"></i>
    </a>
@endif
