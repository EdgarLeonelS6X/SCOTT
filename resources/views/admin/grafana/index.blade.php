<x-admin-layout :breadcrumbs="[
        [
            'name' => __('Dashboard'),
            'icon' => 'fa-solid fa-wrench',
            'route' => route('admin.dashboard'),
        ],
        [
            'name' => __('Grafana'),
            'icon' => 'fa-solid fa-chart-pie',
        ],
    ]">

    @livewire('admin.grafana.grafana-table')

</x-admin-layout>
