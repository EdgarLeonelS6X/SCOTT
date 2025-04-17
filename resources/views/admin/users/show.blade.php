<x-admin-layout :breadcrumbs="[
    [
        'name' => __('Dashboard'),
        'icon' => 'fa-solid fa-house',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => __('Users'),
        'icon' => 'fa-solid fa-user-group',
        'route' => route('admin.users.index'),
    ],
    [
        'name' => $user->name,
        'icon' => 'fa-solid fa-circle-info',
    ],
]">

    <div class="w-full mx-auto bg-white dark:bg-gray-800 shadow-2xl rounded-lg p-6 space-y-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-4">
                <button class="flex text-sm rounded-full shadow-2xl cursor-default">
                    <img class="h-20 w-20 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                        alt="{{ Auth::user()->name }}" />
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                    <p class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <i class="fa-solid fa-envelope mr-1"></i>{{ $user->email }}
                    </p>

                </div>
            </div>
            <div class="flex flex-col items-end justify-center text-sm text-gray-500 dark:text-gray-400">
                <div class="flex items-center space-x-2 mb-1">
                    <i class="fa-solid fa-calendar-day"></i>
                    <span>{{ __('Joined on') }} {{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="mt-1">
                    @if ($user->hasVerifiedEmail())
                        <span
                            class="inline-flex items-center text-xs font-medium text-green-800 bg-green-200 rounded-full px-2 py-1 dark:bg-green-800 dark:text-green-200">
                            <i class="fa-solid fa-check-circle mr-1"></i>{{ __('Email verified') }}
                        </span>
                    @else
                        <span
                            class="inline-flex items-center text-xs font-medium text-gray-500 bg-gray-200 rounded-full px-2 py-1 dark:bg-gray-600 dark:text-gray-300">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i>{{ __('Email not verified') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.users.permissions', $user) }}">
            @csrf
            @method('PUT')
            <div>
                <x-label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    <i class="fa-solid fa-shield-halved mr-2"></i>{{ __('Role') }}
                </x-label>
                @php $auth = auth()->user(); @endphp
                <select id="role" name="role"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    @if (!$auth->hasRole('admin') || ($user->id === 1 && $user->hasRole('admin'))) disabled @endif>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @if ($user->hasRole($role->name)) selected @endif>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mt-6">
                <h2 class="text-sm font-semibold text-gray-800 dark:text-white mb-2">
                    <i class="fa-solid fa-key mr-2"></i>{{ __('Permissions') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    @php
                        $grouped = collect($permissions)->groupBy(function ($perm) {
                            return explode('.', $perm->name)[0];
                        });

                        $icons = [
                            'channels' => 'fa-tv',
                            'stages' => 'fa-bars-staggered',
                            'roles' => 'fa-shield-halved',
                            'permissions' => 'fa-key',
                        ];
                    @endphp
                    @foreach ($grouped as $category => $perms)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md shadow-lg">
                            @php
                                $isMainAdmin = $user->id === 1 && $user->hasRole('admin');
                            @endphp
                            <h3
                                class="text-sm font-bold mb-3 text-gray-700 dark:text-gray-200 uppercase {{ $isMainAdmin ? 'opacity-50' : '' }}">
                                <i class="fa-solid {{ $icons[$category] ?? 'fa-lock' }} mr-1"></i>
                                {{ ucfirst($category) }}
                            </h3>
                            <div class="space-y-3">
                                @foreach ($perms as $permission)
                                    @php
                                        $isMainAdmin = $user->id === 1 && $user->hasRole('admin');
                                        $cannotEdit = !$auth->hasRole('admin') || $isMainAdmin;
                                    @endphp
                                    <label
                                        class="flex items-center {{ $isMainAdmin ? 'opacity-50 cursor-default' : 'cursor-pointer' }}">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            class="sr-only peer" @if ($user->hasPermissionTo($permission->name)) checked @endif
                                            @if ($cannotEdit) disabled @endif>
                                        <div
                                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 
                                                    peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full 
                                                    peer-checked:after:border-white 
                                                    after:content-[''] after:absolute after:top-[2px] after:start-[2px] 
                                                    after:bg-white after:border-gray-300 after:border after:rounded-full 
                                                    after:h-5 after:w-5 after:transition-all dark:border-gray-600 
                                                    peer-checked:bg-primary-600 dark:peer-checked:bg-primary-500">
                                        </div>
                                        <span class="ml-3 text-sm text-gray-800 dark:text-gray-200">
                                            {{ ucfirst(str_replace($category . '.', '', $permission->name)) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @php
                $cannotSave = !$auth->hasRole('admin') || ($user->id === 1 && $user->hasRole('admin'));
            @endphp
            <div class="mt-6 text-right">
                <button class="bg-primary-600 text-white px-6 py-2 rounded-md shadow font-semibold"
                    {{ $cannotSave ? 'disabled' : '' }}>
                    <i class="fa-solid fa-floppy-disk mr-1"></i>
                    {{ __('Save changes') }}
                </button>
            </div>
        </form>
    </div>


</x-admin-layout>
