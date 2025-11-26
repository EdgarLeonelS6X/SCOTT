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
            class="hidden sm:block text-white {{ Auth::user()->area === 'DTH' ? 'bg-secondary-700 hover:bg-secondary-800 focus:ring-secondary-300 dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-800' : 'bg-primary-700 hover:bg-primary-800 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800' }} font-medium rounded-lg text-sm px-5 py-2 focus:outline-none shadow-xl">
            <i class="fa-solid fa-plus mr-1"></i>
            {{ __('Register new user') }}
        </a>
    </x-slot>
    <a href="{{ route('admin.users.create') }}"
        class="mb-4 sm:hidden block text-center text-white {{ Auth::user()->area === 'DTH' ? 'bg-secondary-700 hover:bg-secondary-800 focus:ring-secondary-300 dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-800' : 'bg-primary-700 hover:bg-primary-800 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800' }} font-medium rounded-lg text-sm px-5 py-2 focus:outline-none shadow-xl">
        <i class="fa-solid fa-plus mr-1"></i>
        {{ __('Register new user') }}
    </a>
    @endrole

    @livewire('admin.users.user-table')

</x-admin-layout>
