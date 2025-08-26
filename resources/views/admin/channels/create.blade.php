<x-admin-layout :breadcrumbs="[
    [
        'name' => __('Dashboard'),
        'icon' => 'fa-solid fa-wrench',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => __('Channels'),
        'icon' => 'fa-solid fa-tv',
        'route' => route('admin.channels.index'),
    ],
    [
        'name' => __('New'),
        'icon' => 'fa-solid fa-plus',
    ],
]">

    @livewire('admin.channels.create-channel')

</x-admin-layout>
