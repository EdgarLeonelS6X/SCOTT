<div class="bg-white dark:bg-gray-800 relative shadow-2xl rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full table-fixed text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs dark:text-white uppercase dark:bg-gray-600 shadow-2xl">
                <tr>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap min-w-[300px] max-w-[300px] w-[300px]">
                        <i class="fa-solid fa-user mr-1.5"></i>
                        {{ __('User') }}
                    </th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap min-w-[220px] max-w-[220px] w-[220px]">
                        <i class="fa-solid fa-envelope mr-1.5"></i>
                        {{ __('Email') }}
                    </th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap min-w-[140px] max-w-[140px] w-[140px]">
                        <i class="fa-solid fa-shield-halved mr-1.5"></i>
                        {{ __('Role') }}
                    </th>
                    <th class="py-3 px-6 whitespace-nowrap cursor-pointer min-w-[150px] max-w-[150px] w-[150px]"
                        wire:click="toggleAreaFilter">
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
                    <th scope="col" class="px-6 py-3 whitespace-nowrap min-w-[120px] max-w-[120px] w-[120px]">
                        <i class="fa-solid fa-arrows-left-right mr-1.5"></i>
                        {{ __('Switch') }}
                    </th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap min-w-[120px] max-w-[120px] w-[120px]">
                        <i class="fa-solid fa-toggle-on mr-1.5"></i>
                        {{ __('Status') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-center whitespace-nowrap min-w-[40px] max-w-[40px] w-[40px]">
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr onclick="window.location.href='{{ route('admin.users.show', $user) }}'"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600 text-black dark:text-white cursor-pointer hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap flex items-center space-x-4 min-w-[300px] max-w-[300px] w-[300px]">
                            <button class="flex text-sm rounded-full shadow-2xl cursor-default">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $user->profile_photo_url }}"
                                    alt="{{ $user->name }}" />
                            </button>
                            <span class="font-semibold text-gray-900 dark:text-white truncate block max-w-[300px]">{{ $user->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap min-w-[220px] max-w-[220px] w-[220px]">
                            <span class="text-gray-700 dark:text-gray-300 truncate block max-w-[200px]">{{ $user->email }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap min-w-[140px] max-w-[140px] w-[140px]">
                            @php
                                $role = $user->roles->first()?->name;
                                if ($user->id === 1) {
                                    $badge = [
                                        'label' => 'Developer',
                                        'icon' => 'fa-code',
                                        'bg' => 'bg-red-600',
                                        'text' => 'text-white',
                                        'dark_bg' => 'dark:bg-red-700',
                                        'dark_text' => 'dark:text-white',
                                    ];
                                } elseif ($role === 'master') {
                                    $badge = [
                                        'label' => 'Master',
                                        'icon' => 'fa-crown',
                                        'bg' => 'bg-yellow-200',
                                        'text' => 'text-yellow-900',
                                        'dark_bg' => 'dark:bg-yellow-700',
                                        'dark_text' => 'dark:text-yellow-100',
                                    ];
                                } elseif ($role === 'admin') {
                                    $badge = [
                                        'label' => 'Admin',
                                        'icon' => 'fa-gear',
                                        'bg' => 'bg-blue-600',
                                        'text' => 'text-white',
                                        'dark_bg' => 'dark:bg-blue-800',
                                        'dark_text' => 'dark:text-white',
                                    ];
                                } elseif ($role === 'user') {
                                    $badge = [
                                        'label' => 'User',
                                        'icon' => 'fa-user',
                                        'bg' => 'bg-gray-400',
                                        'text' => 'text-white',
                                        'dark_bg' => 'dark:bg-gray-700',
                                        'dark_text' => 'dark:text-white',
                                    ];
                                } else {
                                    $badge = null;
                                }
                            @endphp
                            @if ($badge)
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['dark_bg'] }} {{ $badge['dark_text'] }}">
                                    <i class="fa-solid {{ $badge['icon'] }} mr-1 text-xs"></i>
                                    {{ $badge['label'] }}
                                    @if (in_array($user->id, [2, 5, 7, 8]))
                                        ({{ __('Engineering') }})
                                    @endif
                                </span>
                            @else
                                <span class="text-xs text-gray-400 italic">{{ __('No role') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap min-w-[150px] max-w-[150px] w-[150px]">
                            @php
                                $defaultArea = $user->default_area ?? $user->area;
                                $currentArea = $user->area ?? null;
                                $isDifferentArea = $defaultArea && $currentArea && $currentArea !== $defaultArea;
                                $defaultAreaClasses = $defaultArea === 'DTH'
                                    ? 'bg-secondary-200 text-secondary-800 dark:bg-secondary-700 dark:text-secondary-100'
                                    : 'bg-primary-200 text-primary-800 dark:bg-primary-700 dark:text-primary-100';
                                $currentAreaClasses = $currentArea === 'DTH'
                                    ? 'bg-secondary-200 text-secondary-800 dark:bg-secondary-700 dark:text-secondary-100'
                                    : 'bg-primary-200 text-primary-800 dark:bg-primary-700 dark:text-primary-100';
                                $defaultIcon = $defaultArea === 'DTH' ? 'fa-satellite-dish' : 'fa-cube';
                                $currentIcon = $currentArea === 'DTH' ? 'fa-satellite-dish' : 'fa-cube';
                            @endphp

                            @if($defaultArea)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $defaultAreaClasses }}">
                                    <i class="fa-solid {{ $defaultIcon }} mr-1"></i>
                                    <span class="truncate">{{ $defaultArea }}</span>
                                </span>

                                @if($isDifferentArea)
                                    <i class="fa-solid fa-arrow-right mx-2 text-xs text-gray-400 dark:text-gray-300" aria-hidden="true"></i>

                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $currentAreaClasses }} opacity-95">
                                        <i class="fa-solid {{ $currentIcon }} mr-1"></i>
                                        <span class="truncate">{{ $currentArea }}</span>
                                    </span>
                                @endif
                            @else
                                <span class="text-xs text-gray-400 italic">{{ __('N/A') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap min-w-[120px] max-w-[120px] w-[120px]">
                            @if ($user->can_switch_area)
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100">
                                    <i class="fa-solid fa-circle-check mr-1"></i>
                                    {{ __('Yes') }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <i class="fa-solid fa-ban mr-1"></i>
                                    {{ __('No') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap min-w-[120px] max-w-[120px] w-[120px]">
                            @if ($user->status)
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100">
                                    <i class="fa-solid fa-circle-check mr-1"></i> {{ __('Active') }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-200 text-red-600 dark:bg-red-700 dark:text-red-300">
                                    <i class="fa-solid fa-circle-xmark mr-1"></i> {{ __('Inactive') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap min-w-[40px] max-w-[40px] w-[40px]">
                            <i class="fa-solid fa-chevron-right text-lg text-gray-400"></i>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="bg-white dark:bg-gray-800 py-6 text-center">
                            <i class="fa-solid fa-circle-info mr-1"></i>
                            {{ __('There are no users registered for this area.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
