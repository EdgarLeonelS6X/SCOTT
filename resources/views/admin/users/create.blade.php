<x-admin-layout :breadcrumbs="[
    [
        'name' => __('Dashboard'),
        'icon' => 'fa-solid fa-wrench',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => __('Users'),
        'icon' => 'fa-solid fa-user-group',
        'route' => route('admin.users.index'),
    ],
    [
        'name' => __('New'),
        'icon' => 'fa-solid fa-plus',
    ],
]">

    <x-slot name="action">
        <a href="{{ route('admin.users.index') }}"
            class="hidden sm:flex justify-center items-center text-white bg-gray-600 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm px-5 py-2 text-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i>
            {{ __('Go back') }}
        </a>
    </x-slot>
    <div class="w-full bg-white rounded-lg shadow-2xl dark:border md:mt-0 xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                <i class="fa-solid fa-user-group mr-1.5"></i>
                {{ __('Register new user') }}
                <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                    {{ __('Enter the data for the new user.') }}
                </p>
            </h1>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <x-label for="name">
                            <i class="fa-solid fa-user mr-1"></i>
                            {{ __('Name') }}
                        </x-label>
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" required autofocus autocomplete="name"
                            placeholder="{{ __('User name') }}" />
                    </div>
                    <div>
                        <x-label for="email">
                            <i class="fa-solid fa-envelope mr-1"></i>
                            {{ __('Email') }}
                        </x-label>
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autocomplete="email"
                            placeholder="{{ __('Email address') }}" />
                    </div>
                    <div>
                        <x-label for="password">
                            <i class="fa-solid fa-lock mr-1"></i>
                            {{ __('Password') }}
                        </x-label>
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password"
                            required autocomplete="new-password"
                            placeholder="{{ __('••••••••') }}" />
                    </div>
                    <div>
                        <x-label for="password_confirmation">
                            <i class="fa-solid fa-lock mr-1"></i>
                            {{ __('Confirm Password') }}
                        </x-label>
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation"
                            required autocomplete="new-password"
                            placeholder="{{ __('••••••••') }}" />
                    </div>
                    <div>
                        <x-label for="role">
                            <i class="fa-solid fa-shield-halved mr-1"></i>
                            {{ __('Role') }}
                        </x-label>
                        <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected disabled>{{ __('Select role') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst(__($role->name)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-label for="status">
                            <i class="fa-solid fa-toggle-on mr-1"></i>
                            {{ __('Status') }}
                        </x-label>
                        <select id="status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            name="status" required>
                            <option selected disabled>{{ __('Select status') }}</option>
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end items-center">
                    <x-button class="flex justify-center items-center mt-8 font-bold">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        {{ __('Register new user') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
