<x-admin-layout :breadcrumbs="[
    [
        'name' => __('Dashboard'),
        'icon' => 'fa-solid fa-wrench',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => __('Users'),
        'icon' => 'fa-solid fa-user-group',
    ],
]">

    <div class="bg-white dark:bg-gray-800 relative shadow-2xl rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs dark:text-white uppercase dark:bg-gray-600 shadow-2xl">
                    <tr>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap min-w-[300px] sm:min-w-[300px]">
                            <i class="fa-solid fa-user mr-1.5"></i>
                            {{ __('User') }}
                        </th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                            <i class="fa-solid fa-envelope mr-1.5"></i>
                            {{ __('Email') }}
                        </th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                            <i class="fa-solid fa-shield-halved mr-1.5"></i>
                            {{ __('Role') }}
                        </th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap text-center">
                            <i class="fa-solid fa-toggle-on mr-1.5"></i>
                            {{ __('Status') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center whitespace-nowrap">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr onclick="window.location.href='{{ route('admin.users.show', $user) }}'"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600 text-black dark:text-white cursor-pointer hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap flex items-center space-x-4">
                                <button class="flex text-sm rounded-full shadow-2xl cursor-default">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ $user->profile_photo_url }}"
                                        alt="{{ $user->name }}" />
                                </button>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-700 dark:text-gray-300">{{ $user->email }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $role = $user->roles->first()?->name;
                                @endphp
                                @if ($role)
                                    @php
                                        $roleName = ucfirst($role);
                                        $roleStyles = match ($role) {
                                            'master' => [
                                                'bg' => 'bg-yellow-200',
                                                'text' => 'text-yellow-900',
                                                'dark_bg' => 'dark:bg-yellow-700',
                                                'dark_text' => 'dark:text-yellow-100',
                                                'icon' => 'fa-crown',
                                            ],
                                            'admin' => [
                                                'bg' => 'bg-red-200',
                                                'text' => 'text-red-800',
                                                'dark_bg' => 'dark:bg-red-900',
                                                'dark_text' => 'dark:text-red-200',
                                                'icon' => 'fa-gear',
                                            ],
                                            default => [
                                                'bg' => 'bg-blue-100',
                                                'text' => 'text-blue-800',
                                                'dark_bg' => 'dark:bg-blue-900',
                                                'dark_text' => 'dark:text-blue-200',
                                                'icon' => 'fa-user',
                                            ],
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $roleStyles['bg'] }} {{ $roleStyles['text'] }} {{ $roleStyles['dark_bg'] }} {{ $roleStyles['dark_text'] }}">
                                        <i class="fa-solid {{ $roleStyles['icon'] }} mr-1 text-xs"></i>
                                        {{ $roleName }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">{{ __('No role') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if ($user->status)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100">
                                        <i class="fa-solid fa-circle-check mr-1"></i> {{ __('Active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-200 text-red-600 dark:bg-red-700 dark:text-red-300">
                                        <i class="fa-solid fa-circle-xmark mr-1"></i> {{ __('Inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <i class="fa-solid fa-chevron-right text-lg text-gray-400"></i>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-admin-layout>
