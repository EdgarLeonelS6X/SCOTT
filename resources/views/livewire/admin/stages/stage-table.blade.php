<div class="bg-white dark:bg-gray-800 relative shadow-2xl rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs dark:text-white uppercase dark:bg-gray-600 shadow-2xl">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        <i class="fa-solid fa-bars-staggered mr-1"></i>
                        {{ __('Name') }}
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="toggleAreaFilter">
                        <i class="fa-solid fa-building mr-1"></i>
                        <span class="text-gray-500 dark:text-white">
                            @if ($areaFilter === 'all')
                                {{ __('Areas') }}
                            @else
                                {{ $areaFilter }}
                            @endif
                            <i class="ml-1 fa-solid fa-sort"></i>
                        </span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <i class="fa-solid fa-toggle-on mr-1"></i>
                        {{ __('Status') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only">
                            <i class="fa-solid fa-sliders-h mr-1"></i>
                            {{ __('Options') }}
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stages as $stage)
                    <tr onclick="window.location.href='{{ route('admin.stages.show', $stage) }}'"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600 text-black dark:text-white cursor-pointer">
                        <th scope="row" class="px-6 py-3 font-bold text-gray-900 whitespace-nowrap dark:text-white">
                            {{ __($stage->name) }}
                        </th>
                        <td class="px-6 py-3 whitespace-nowrap">
                            @php
    $areaClasses = $stage->area === 'DTH'
        ? 'bg-secondary-200 text-secondary-800 dark:bg-secondary-700 dark:text-secondary-100'
        : 'bg-primary-200 text-primary-800 dark:bg-primary-700 dark:text-primary-100';
                            @endphp
                            @if ($stage->area === 'OTT')
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $areaClasses }}">
                                    <i class="fa-solid fa-cube mr-1"></i> OTT
                                </span>
                            @elseif ($stage->area === 'DTH')
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $areaClasses }}">
                                    <i class="fa-solid fa-satellite-dish mr-1"></i> DTH
                                </span>
                            @else
                                <span class="text-xs text-gray-400 italic">{{ __('N/A') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            @if ($stage->status === 1)
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-200 rounded-full dark:bg-green-800 dark:text-green-200">
                                    <i class="fa-solid fa-check-circle mr-1"></i>
                                    {{ __('Active') }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-800 bg-red-200 rounded-full dark:bg-red-800 dark:text-red-200">
                                    <i class="fa-solid fa-times-circle mr-1"></i>
                                    {{ __('Inactive') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3 flex items-center justify-center">
                            <button id="stage-dropdown-button-{{ $stage->id }}"
                                data-dropdown-toggle="stage-dropdown-{{ $stage->id }}" onclick="event.stopPropagation()"
                                class="inline-flex items-center p-3 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                type="button">
                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                </svg>
                            </button>
                            <div id="stage-dropdown-{{ $stage->id }}"
                                class="hidden z-50 w-44 bg-white rounded divide-y divide-gray-300 shadow-2xl dark:bg-gray-700 dark:divide-gray-600">
                                <ul class="flex flex-col items-start py-1 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="stage-dropdown-button-{{ $stage->id }}">
                                    <li class="w-full">
                                        <a href="{{ route('admin.stages.show', $stage) }}"
                                            title="{{ __('Show stage information') }}"
                                            class="flex items-center w-full py-2 px-3.5 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <i class="fa-solid fa-eye mr-2"></i>
                                            {{ __('Show') }}
                                        </a>
                                    </li>
                                    @can('edit', $stage)
                                        <li class="w-full">
                                            <a href="{{ route('admin.stages.edit', $stage) }}"
                                                title="{{ __('Edit stage') }}"
                                                class="flex items-center w-full py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                <i class="fa-solid fa-pen-to-square mr-2"></i>
                                                {{ __('Edit') }}
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                                @can('delete', $stage)
                                    <div class="w-full py-1">
                                        <form action="{{ route('admin.stages.destroy', $stage) }}" method="POST"
                                            id="delete-form-{{ $stage->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" @click.stop.prevent="confirmDelete({{ $stage->id }})"
                                                title="{{ __('Delete stage') }}"
                                                class="flex items-center w-full text-left py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                <i class="fa-solid fa-trash-can mr-2"></i>
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center bg-white dark:bg-gray-800">
                            <i class="fa-solid fa-circle-info mr-2"></i>
                            {{ __('There are no stages that match your search.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
