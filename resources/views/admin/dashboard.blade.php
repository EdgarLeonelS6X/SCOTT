<x-admin-layout :breadcrumbs="[
        [
            'name' => __('Dashboard'),
            'icon' => 'fa-solid fa-house',
        ],
    ]">

    @livewire('admin.grafana-panel')

</x-admin-layout>
