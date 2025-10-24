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

    @role('master')
    <x-slot name="action">
        <a href="{{ route('admin.users.create') }}"
            class="hidden sm:block text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 shadow-xl">
            <i class="fa-solid fa-plus mr-1"></i>
            {{ __('Register new user') }}
        </a>
    </x-slot>
    <a href="{{ route('admin.users.create') }}"
        class="mb-4 sm:hidden block text-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 shadow-xl">
        <i class="fa-solid fa-plus mr-1"></i>
        {{ __('Register new user') }}
    </a>
    @endrole
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
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">{{ __('No role') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
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