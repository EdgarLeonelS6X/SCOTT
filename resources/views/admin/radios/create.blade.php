<x-admin-layout :breadcrumbs="[
        [
            'name' => __('Dashboard'),
            'icon' => 'fa-solid fa-wrench',
            'route' => route('admin.dashboard'),
        ],
        [
            'name' => __('Radios'),
            'icon' => 'fa-solid fa-radio',
            'route' => route('admin.radios.index'),
        ],
        [
            'name' => __('New'),
            'icon' => 'fa-solid fa-plus',
        ],
    ]">

    @livewire('admin.radios.create-radio')

</x-admin-layout>
