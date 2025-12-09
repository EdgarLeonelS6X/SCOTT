@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">{{ __('Device') }} #{{ $device->id }}</h2>
        <div class="space-y-3">
            <div><strong>{{ __('Name') }}:</strong> {{ $device->name }}</div>
            <div><strong>{{ __('Status') }}:</strong> {{ $device->status ? __('Active') : __('Inactive') }}</div>
            <div><strong>{{ __('Area') }}:</strong> {{ $device->area }}</div>
        </div>

        <div class="mt-6">
            <a href="{{ route('devices.index') }}" class="px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded">{{ __('Back') }}</a>
            @can('update', $device)
                <a href="{{ route('devices.edit', $device->id) }}" class="px-3 py-2 bg-primary-600 text-white rounded">{{ __('Edit') }}</a>
            @endcan
        </div>
    </div>
</div>
@endsection
