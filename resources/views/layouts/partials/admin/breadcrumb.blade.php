@if (count($breadcrumbs))
    <nav class="flex px-4 py-1 mt-2 shadow-2xl text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
        aria-label="Breadcrumb">
        <ol class="inline-flex items-center">
            @foreach ($breadcrumbs as $item)
                <li class="inline-flex items-center">
                    @isset($item['route'])
                        <a href="{{ $item['route'] }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-600">
                            @isset($item['icon'])
                                <i class="{{ $item['icon'] }} me-2"></i>
                            @endisset
                            <span class="inline-flex items-center">
                                {{ $item['name'] }}
                            </span>
                        </a>
                    @else
                        <div class="inline-flex items-center dark:text-gray-400 text-sm">
                            @isset($item['icon'])
                                <i class="{{ $item['icon'] }} me-2"></i>
                            @endisset
                            <span class="inline-flex items-center">
                                {{ $item['name'] }}
                            </span>
                        </div>
                    @endisset
                    @if (!$loop->last)
                        <svg class="rtl:rotate-180 block w-3 h-3 mx-2 text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
