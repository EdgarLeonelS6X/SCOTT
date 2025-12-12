<x-admin-layout :breadcrumbs="[
        [
            'name' => __('Dashboard'),
            'icon' => 'fa-solid fa-wrench',
            'route' => route('admin.dashboard'),
        ],
        [
            'name' => __('Devices'),
            'icon' => 'fa-solid fa-hard-drive',
            'route' => route('admin.devices.index'),
        ],
        [
            'name' => __('Update'),
            'icon' => 'fa-solid fa-pencil',
        ],
    ]">

    @livewire('admin.devices.edit-device', ['device' => $device])

</x-admin-layout>
