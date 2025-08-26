<x-app-layout :breadcrumbs="[
        [
            'name' => __('Home'),
            'icon' => 'fa-solid fa-house',
            'route' => route('dashboard'),
        ],
        [
            'name' => __('Reports'),
            'icon' => 'fa-solid fa-paste',
            'route' => route('reports.index'),
        ],
        [
            'name' => __('Report'),
            'icon' => 'fa-solid fa-pencil',
        ],
    ]">

    @php
        $component = match (strtolower($report->type)) {
            'momentary' => 'app.reports.edit.edit-momently-report',
            'hourly' => 'app.reports.edit.edit-hourly-report',
            'functions' => 'app.reports.edit.edit-functions-report',
        };
    @endphp

    @livewire($component, ['report' => $report])

</x-app-layout>
