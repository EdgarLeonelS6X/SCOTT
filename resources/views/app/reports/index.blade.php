<x-app-layout :breadcrumbs="[
    [
        'name' => __('Dashboard'),
        'icon' => 'fa-solid fa-wrench',
        'route' => route('dashboard'),
    ],
    [
        'name' => __('Reports'),
        'icon' => 'fa-solid fa-paste',
    ],
]">

    <div class="px-6 py-1">
        @livewire('app.reports.report-history-table')
    </div>
</x-app-layout>
