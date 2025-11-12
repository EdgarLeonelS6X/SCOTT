@php
    $user = Auth::user();
    $isMaster = $user && $user->role === 'master';
    $currentArea = $user?->area;
    $nextArea = $currentArea === 'OTT' ? 'DTH' : 'OTT';
    $label = $currentArea === 'OTT' ? __('Switch to DTH') : __('Switch to OTT');
@endphp
@if($isMaster)
    <a href="{{ route('user.switch-area', ['area' => $nextArea]) }}"
        class="inline-flex items-center px-3 py-1 rounded text-xs font-semibold transition
                   {{ $currentArea === 'OTT' ? 'bg-primary-100 text-primary-700 hover:bg-primary-200' : 'bg-secondary-100 text-secondary-700 hover:bg-secondary-200' }}">
        <i class="fa-solid fa-arrows-rotate mr-1"></i>
        {{ $label }}
    </a>
@endif