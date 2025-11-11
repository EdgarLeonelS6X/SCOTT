@if (count($breadcrumbs))
    @php
        $area = Auth::user()?->area;
        $hoverColor = $area === 'OTT' ? 'hover:text-primary-600 dark:hover:text-primary-500' : ($area === 'DTH' ? 'hover:text-secondary-600 dark:hover:text-secondary-500' : 'hover:text-primary-600 dark:hover:text-primary-500');
    @endphp
    <nav class="flex items-center px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 text-gray-800 dark:text-gray-300 text-sm"
        aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            @foreach ($breadcrumbs as $item)
                <li class="inline-flex items-center">
                    @isset($item['route'])
                        <a href="{{ $item['route'] }}"
                            class="inline-flex items-center gap-1 text-gray-600 dark:text-gray-400 transition-colors duration-200 {{ $hoverColor }}">
                            @isset($item['icon'])
                                <i class="{{ $item['icon'] }} text-xs opacity-75"></i>
                            @endisset
                            <span class="truncate max-w-[120px]">{{ $item['name'] }}</span>
                        </a>
                    @else
                        <span class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-500 cursor-default">
                            @isset($item['icon'])
                                <i class="{{ $item['icon'] }} text-xs opacity-70"></i>
                            @endisset
                            <span class="truncate max-w-[120px] font-medium">{{ $item['name'] }}</span>
                        </span>
                    @endisset

                    @if (!$loop->last)
                        <svg class="rtl:rotate-180 w-3 h-3 mx-2 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
