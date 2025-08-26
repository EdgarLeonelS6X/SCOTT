<x-admin-layout :breadcrumbs="[
        [
            'name' => __('Dashboard'),
            'icon' => 'fa-solid fa-wrench',
        ],
    ]">

    @livewire('admin.grafana-panel')

</x-admin-layout>
