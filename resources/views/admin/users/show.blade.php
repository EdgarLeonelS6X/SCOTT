<x-admin-layout :breadcrumbs="[
    ['name' => __('Dashboard'), 'icon' => 'fa-solid fa-wrench', 'route' => route('admin.dashboard')],
    ['name' => __('Users'), 'icon' => 'fa-solid fa-user-group', 'route' => route('admin.users.index')],
    ['name' => __('User'), 'icon' => 'fa-solid fa-circle-info'],
]">

    @livewire('admin.users.user-permissions', ['user' => $user])

</x-admin-layout>
