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
            'name' => __('Update'),
            'icon' => 'fa-solid fa-pencil',
        ],
    ]">

    @livewire('admin.radios.edit-radio', ['radio' => $radio])

</x-admin-layout>
