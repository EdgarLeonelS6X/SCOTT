@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">{{ __('Edit Device') }}</h2>
        <form action="{{ route('devices.update', $device->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-200">{{ __('Name') }}</label>
                    <input type="text" name="name" value="{{ old('name', $device->name) }}" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-2" required />
                    @error('name') <div class="text-xs text-red-600">{{ $message }}</div>@enderror
                </div>
                <div class="flex items-center gap-3">
                    <label class="text-sm text-gray-700 dark:text-gray-200">{{ __('Status') }}</label>
                    <input type="checkbox" name="status" value="1" {{ $device->status ? 'checked' : '' }} />
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('devices.index') }}" class="px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded mr-2">{{ __('Cancel') }}</a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded">{{ __('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
