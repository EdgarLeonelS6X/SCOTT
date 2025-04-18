<x-admin-layout :breadcrumbs="[
    [
        'name' => __('Dashboard'),
        'icon' => 'fa-solid fa-house',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => __('Users'),
        'icon' => 'fa-solid fa-user-group',
    ],
]">

    <div class="bg-white dark:bg-gray-800 relative shadow-2xl sm:rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs dark:text-white uppercase dark:bg-gray-600 shadow-2xl">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            <i class="fa-solid fa-user mr-1.5"></i>
                            {{ __('User') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <i class="fa-solid fa-envelope mr-1.5"></i>
                            {{ __('Email') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <i class="fa-solid fa-shield-halved mr-1.5"></i>
                            {{ __('Role') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr onclick="window.location.href='{{ route('admin.users.show', $user) }}'"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600 text-black dark:text-white cursor-pointer">
                            <td class="px-6 py-4 flex items-center space-x-4">
                                <button class="flex text-sm rounded-full shadow-2xl cursor-default">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ $user->profile_photo_url }}"
                                        alt="{{ $user->name }}" />
                                </button>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-700 dark:text-gray-300">{{ $user->email }}</span>
                            </td>
                            <td class="px-6 py-4">
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
                            <td class="px-6 py-4 text-center">
                                <i class="fa-solid fa-chevron-right text-lg text-gray-400"></i>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-admin-layout>
