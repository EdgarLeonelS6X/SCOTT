@php
    $links = [
        [
            'name' => __('Home'),
            'icon' => 'fa-solid fa-house',
            'route' => route('dashboard'),
            'active' => request()->routeIs('dashboard'),
            'divider' => true,
        ],
        [
            'name' => __('Dashboard'),
            'icon' => 'fa-solid fa-wrench',
            'route' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
        ],
        [
            'name' => __('Users'),
            'icon' => 'fa-solid fa-user-group',
            'route' => route('admin.users.index'),
            'active' => request()->routeIs('admin.users.*'),
        ],
        [
            'name' => __('Channels'),
            'icon' => 'fa-solid fa-tv',
            'route' => route('admin.channels.index'),
            'active' => request()->routeIs('admin.channels.*'),
        ],
        [
            'name' => __('Stages'),
            'icon' => 'fa-solid fa-bars-staggered',
            'route' => route('admin.stages.index'),
            'active' => request()->routeIs('admin.stages.*'),
        ],
        [
            'name' => __('Grafana'),
            'icon' => 'fa-solid fa-chart-pie',
            'route' => route('admin.stages.index'),
            'active' => request()->routeIs('admin.stages.*'),
        ],
        [
            'name' => __('MySQL'),
            'icon' => 'fa-solid fa-database',
            'route' => 'http://172.16.100.93/phpmyadmin/index.php?route=/database/structure&db=scott_database',
            'external' => true,
            'active' => false,
        ],
    ];
@endphp

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-52 h-[100dvh] pt-[60px] bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 transition-transform duration-300
        -translate-x-full sm:translate-x-0"
    :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }" aria-label="Sidebar"
    x-data="{ openDropdown: null }">
    <div class="flex flex-col h-full px-4 pb-6 mt-2">
        <ul class="flex-1 space-y-1">
            @foreach ($links as $index => $link)
                @if (isset($link['children']))
                    <li>
                        <button @click="openDropdown = openDropdown === {{ $index }} ? null : {{ $index }}" class="flex items-center justify-between gap-3 px-3 py-2 w-full rounded-md transition-colors duration-150 {{ $link['active'] ? 'bg-primary-50 dark:bg-primary-900 text-primary-700 dark:text-primary-300 font-medium'
                    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            <div class="flex items-center gap-3">
                                <i class="{{ $link['icon'] }} w-4 h-4"></i>
                                <span class="truncate">{{ $link['name'] }}</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-xs transition-transform"
                                :class="{ 'rotate-180': openDropdown === {{ $index }} }"></i>
                        </button>
                        <ul x-show="openDropdown === {{ $index }}" x-transition class="pl-8 mt-1 space-y-1">
                            @foreach ($link['children'] as $child)
                                    <li>
                                        <a href="{{ $child['route'] }}" class="flex items-center gap-2 justify-between px-3 py-1.5 rounded-md text-sm transition-colors duration-150 {{ $child['active']
                                ? 'bg-primary-100 dark:bg-primary-800 text-primary-700 dark:text-primary-300'
                                : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                            <span class="flex items-center gap-2">
                                                @if(isset($child['icon']))
                                                    <i class="{{ $child['icon'] }} text-xs"></i>
                                                @endif
                                                <span>{{ $child['name'] }}</span>
                                            </span>
                                        </a>
                                    </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li>
                        @if(isset($link['external']) && $link['external'])
                            <a href="{{ $link['route'] }}" target="_blank" rel="noopener"
                                class="flex items-center justify-between gap-3 px-3 py-2 rounded-md transition-colors duration-150 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                <span class="flex items-center gap-3">
                                    <i class="{{ $link['icon'] }} text-base"></i>
                                    <span class="truncate">{{ $link['name'] }}</span>
                                </span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-gray-400"></i>
                            </a>
                        @else
                            <a href="{{ $link['route'] }}" class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors duration-150 {{ $link['active']
                            ? 'bg-primary-50 dark:bg-primary-900 text-primary-700 dark:text-primary-300 font-medium'
                            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                                aria-current="{{ $link['active'] ? 'page' : false }}">
                                <i class="{{ $link['icon'] }} text-base"></i>
                                <span class="truncate">{{ $link['name'] }}</span>
                            </a>
                        @endif
                    </li>
                @endif

                @if(isset($link['divider']) && $link['divider'])
                    <li>
                        <hr class="my-2 border-gray-200 dark:border-gray-700">
                    </li>
                @endif
            @endforeach
        </ul>

        <x-language-switch />

        <div class="mt-6 border-t border-gray-100 dark:border-gray-800 pt-4">
            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <a href="{{ route('logout') }}" @click.prevent="$root.submit();"
                    class="flex justify-center items-center gap-2 text-xs text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    {{ __('Log Out') }}
                </a>
            </form>
        </div>
    </div>
</aside>
