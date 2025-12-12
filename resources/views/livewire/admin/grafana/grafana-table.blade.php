<div>
    @can('create', App\Models\GrafanaPanel::class)
        <x-slot name="action">
            <a href="{{ route('admin.grafana.create') }}" class="hidden sm:block text-white {{ Auth::user()?->area === 'DTH'
            ? 'bg-secondary-700 hover:bg-secondary-800 focus:ring-4 focus:ring-secondary-300 dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-800'
            : 'bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800' }} font-medium rounded-lg text-sm px-5 py-2 focus:outline-none shadow-xl">
                <i class="fa-solid fa-plus mr-1"></i>
                {{ __('Register new panel') }}
            </a>
        </x-slot>
        <a href="{{ route('admin.grafana.create') }}" class="mb-4 sm:hidden block text-center text-white {{ Auth::user()?->area === 'DTH'
            ? 'bg-secondary-700 hover:bg-secondary-800 focus:ring-4 focus:ring-secondary-300 dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-800'
            : 'bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800' }} font-medium rounded-lg text-sm px-5 py-2 focus:outline-none shadow-xl">
            <i class="fa-solid fa-plus mr-1"></i>
            {{ __('Register new panel') }}
        </a>
    @endcan

    <div class="bg-white dark:bg-gray-800 relative shadow-2xl rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs dark:text-white uppercase dark:bg-gray-600 shadow-2xl">
                    <tr>
                        <th scope="col" class="px-4 py-3 min-w-[200px] max-w-[340px]">
                            <i class="fa-solid fa-chart-pie mr-1"></i>
                            {{ __('Name') }}
                        </th>
                        <th scope="col" class="px-4 py-3 min-w-[260px] max-w-[480px] pr-4">
                            <i class="fa-solid fa-link mr-1"></i>
                            {{ __('URL') }}
                        </th>
                        <th scope="col" class="px-4 py-3 min-w-[180px] max-w-[320px] pl-4">
                            <i class="fa-solid fa-cloud mr-1"></i>
                            {{ __('Endpoint') }}
                        </th>
                        <th scope="col" class="px-4 py-3 min-w-[60px] max-w-[80px]">
                            <i class="fa-solid fa-building mr-1"></i>
                            {{ __('Area') }}
                        </th>
                        <th scope="col" class="px-4 py-3 min-w-[80px] max-w-[120px]">
                            <span class="sr-only">
                                <i class="fa-solid fa-sliders-h mr-1"></i>
                                {{ __('Options') }}
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($panels as $panel)
                        <tr onclick="window.location.href='{{ route('admin.grafana.show', $panel) }}'"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600 text-black dark:text-white cursor-pointer group">
                            <th scope="row"
                                class="px-4 py-4 font-bold text-gray-900 whitespace-nowrap dark:text-white min-w-[200px] max-w-[340px]">
                                {{ __($panel->name) }}
                            </th>
                            <td class="px-4 py-4 min-w-[260px] max-w-[480px]">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full text-xs font-medium relative"
                                    x-data="{ show: false }" @mouseenter="show = true" @mouseleave="show = false"
                                    @focusin="show = true" @focusout="show = false">
                                    <i class="fa-solid fa-bullseye text-blue-500"></i>
                                    <span class="block truncate"
                                        style="max-width: 400px; text-overflow: ellipsis;">{{ $panel->url }}</span>
                                    @if(strlen($panel->url) > 40)
                                        <span x-show="show" x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            class="absolute left-1/2 transform -translate-x-1/2 mt-2 px-4 py-2 text-xs rounded shadow-lg max-w-2xl min-w-[400px] break-all bg-gray-900 text-white dark:bg-gray-100 dark:text-gray-900"
                                            style="display: none; z-index: 50;" x-cloak>
                                            {{ $panel->url }}
                                        </span>
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-4 min-w-[180px] max-w-[320px] pr-4">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full text-xs font-medium relative"
                                    x-data="{ show: false }" @mouseenter="show = true" @mouseleave="show = false"
                                    @focusin="show = true" @focusout="show = false">
                                    <i class="fa-solid fa-code text-green-500"></i>
                                    <span>{{ $panel->endpoint }}</span>
                                    @if(strlen($panel->endpoint) > 40)
                                        <span x-show="show" x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            class="fixed z-50 px-4 py-2 text-xs rounded shadow-lg w-auto max-w-xs break-all bg-gray-900 text-white dark:bg-gray-100 dark:text-gray-900"
                                            style="display: none;" x-cloak
                                            x-bind:style="'top: ' + ($el.closest('td').getBoundingClientRect().top + window.scrollY + 32) + 'px; left: ' + ($el.closest('td').getBoundingClientRect().left + window.scrollX + $el.closest('td').offsetWidth/2) + 'px; transform: translateX(-50%);'">
                                            {{ $panel->endpoint }}
                                        </span>
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-4 min-w-[120px] max-w-[120px] pl-4">
                                @php
                                    $dthClasses = 'inline-flex items-center px-2 py-1 text-xs font-medium rounded-full ' .
                                        'bg-secondary-200 text-secondary-800 dark:bg-secondary-700 dark:text-secondary-100';
                                    $ottClasses = 'inline-flex items-center px-2 py-1 text-xs font-medium rounded-full ' .
                                        'bg-primary-200 text-primary-800 dark:bg-primary-700 dark:text-primary-100';
                                @endphp

                                @if (in_array($panel->id, [1, 3]))
                                    <div class="flex items-center gap-2">
                                        <span class="{{ $dthClasses }}">
                                            <i class="fa-solid fa-satellite-dish mr-1"></i> DTH
                                        </span>
                                        <span class="{{ $ottClasses }}">
                                            <i class="fa-solid fa-cube mr-1"></i> OTT
                                        </span>
                                    </div>
                                @else
                                    @if ($panel->area === 'OTT')
                                        <span class="{{ $ottClasses }}">
                                            <i class="fa-solid fa-cube mr-1"></i> OTT
                                        </span>
                                    @elseif ($panel->area === 'DTH')
                                        <span class="{{ $dthClasses }}">
                                            <i class="fa-solid fa-satellite-dish mr-1"></i> DTH
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">{{ __('N/A') }}</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-4 py-4 flex items-center justify-end align-middle w-full">
                                <span class="flex items-center h-full justify-center"
                                    style="height: 100%; min-height: 24px;">
                                    <i class="fa-solid fa-chevron-right transition-colors text-gray-300 group-hover:text-gray-700 dark:text-gray-500 dark:group-hover:text-gray-400"
                                    style="vertical-align: middle; font-size: 1.1em; line-height: 1;"></i>
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center bg-white dark:bg-gray-800">
                                <i class="fa-solid fa-circle-info mr-2"></i>
                                {{ __('There are no Grafana panels that match your search.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
