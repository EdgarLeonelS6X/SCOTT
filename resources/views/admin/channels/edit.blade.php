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
        'name' => __('Update'),
        'icon' => 'fa-solid fa-pencil',
    ],
]">

    @livewire('admin.channels.edit-channel', ['channel' => $channel])

</x-admin-layout>
