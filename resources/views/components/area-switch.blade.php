@php
    $user = Auth::user();
    $isMaster = $user && $user->role === 'master';
    $currentArea = $user?->area;
    $nextArea = $currentArea === 'OTT' ? 'DTH' : 'OTT';
    $label = $currentArea === 'OTT' ? __('Switch to DTH') : __('Switch to OTT');
    $show = Auth::check() && $user && ($isMaster || $user->can_switch_area);
@endphp
@if($show)
    <a href="{{ route('admin.user.switch-area', ['area' => $nextArea]) }}"
        class="group inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-800 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 transition {{ $currentArea === 'DTH' ? 'focus:ring-secondary-200 dark:focus:ring-secondary-700' : 'focus:ring-primary-200 dark:focus:ring-primary-700' }}">
        <span class="mr-2">{{ $label }}</span>
        @php
            $icon = $currentArea === 'DTH' ? 'fa-cube' : 'fa-satellite-dish';
        @endphp
        <i @class([
            'fa-solid',
            $icon,
            'text-sm',
            'text-gray-600',
            'dark:text-gray-400',
            'transition',
        ])></i>
    </a>
@endif