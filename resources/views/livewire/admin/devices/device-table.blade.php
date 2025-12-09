<div class="w-full bg-white dark:bg-gray-800 shadow rounded-lg p-4">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Devices') }}</h2>
        <div class="flex items-center gap-2">
            @php $auth = auth()->user(); @endphp
            @if($auth && ($auth->id === 1 || ($auth->area ?? '') === 'OTT'))
                <button wire:click="create" class="px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded">
                    <i class="fa-solid fa-plus mr-2"></i> {{ __('New Device') }}
                </button>
            @endif
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="text-xs text-gray-500 uppercase">
                    <th class="px-3 py-2">{{ __('ID') }}</th>
                    <th class="px-3 py-2">{{ __('Name') }}</th>
                    <th class="px-3 py-2">{{ __('Status') }}</th>
                    <th class="px-3 py-2">{{ __('Area') }}</th>
                    <th class="px-3 py-2">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($devices as $device)
                    <tr class="border-t border-gray-100 dark:border-gray-700">
                        <td class="px-3 py-2">{{ $device->id }}</td>
                        <td class="px-3 py-2">{{ $device->name }}</td>
                        <td class="px-3 py-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs {{ $device->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $device->status ? __('Active') : __('Inactive') }}</span>
                        </td>
                        <td class="px-3 py-2">{{ $device->area }}</td>
                        <td class="px-3 py-2">
                            <div class="flex items-center gap-2">
                                @can('update', $device)
                                    <button wire:click="edit({{ $device->id }})" class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs"> <i class="fa-solid fa-pen"></i></button>
                                @endcan
                                @can('update', $device)
                                    <button wire:click="toggleStatus({{ $device->id }})" class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs"> <i class="fa-solid fa-toggle-on"></i></button>
                                @endcan
                                @can('delete', $device)
                                    <button wire:click="delete({{ $device->id }})" class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs"> <i class="fa-solid fa-trash"></i></button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-sm text-gray-500">{{ __('No devices found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $devices->links() }}
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-4">
                <h3 class="text-lg font-semibold mb-4">{{ $editingId ? __('Edit Device') : __('Create Device') }}</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-700 dark:text-gray-200">{{ __('Name') }}</label>
                        <input type="text" wire:model.defer="name" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-2" />
                        @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="text-sm text-gray-700 dark:text-gray-200">{{ __('Status') }}</label>
                        <input type="checkbox" wire:model="status" class="h-4 w-4" />
                    </div>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button wire:click="save" class="px-3 py-2 bg-primary-600 text-white rounded">{{ __('Save') }}</button>
                    <button wire:click="$set('showModal', false)" class="px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded">{{ __('Cancel') }}</button>
                </div>
            </div>
        </div>
    @endif
</div>
