<x-admin-layout :breadcrumbs="[
    [
        'name' => __('Dashboard'),
        'icon' => 'fa-solid fa-house',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => __('Channels'),
        'icon' => 'fa-solid fa-tv',
        'route' => route('admin.channels.index'),
    ],
    [
        'name' => __('Update channel'),
        'icon' => 'fa-solid fa-pen-to-square',
    ],
]">

    @livewire('admin.channels.edit-channel', ['channel' => $channel])

</x-admin-layout>