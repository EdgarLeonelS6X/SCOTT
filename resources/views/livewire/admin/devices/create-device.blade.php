<x-slot name="action">
    <a href="{{ route('admin.devices.index') }}"
        class="hidden md:block sm:flex justify-center items-center text-white bg-gray-600 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm px-5 py-2 text-center">
        <i class="fa-solid fa-arrow-left mr-1.5"></i>
        {{ __('Go back') }}
    </a>
</x-slot>
<div class="w-full bg-white rounded-lg shadow-2xl dark:border md:mt-0 xl:p-0 dark:bg-gray-800 dark:border-gray-700">
    <div class="p-6 space-y-6 sm:p-8">
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            <i class="fa-solid fa-hard-drive mr-1.5"></i>
            {{ __('Register new device') }}
            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                {{ __('Enter the data for the new device.') }}
            </p>
        </h1>
        <form wire:submit.prevent="store" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <x-label for="name">
                        <i class="fa-solid fa-hard-drive mr-1"></i>
                        {{ __('Name') }}
                    </x-label>
                    <x-input id="name" class="block mt-1 w-full" type="text" wire:model="name" :value="old('name')" required
                        autocomplete="name" placeholder="{{ __('Device name') }}" />
                </div>
                <div>
                    <x-label for="protocol">
                        <i class="fa-solid fa-server mr-1"></i>
                        {{ __('Protocol') }}
                    </x-label>
                    <x-input id="protocol" class="block mt-1 w-full" type="text" wire:model="protocol" :value="old('protocol')"
                        placeholder="{{ __('Device protocol') }}" />
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <x-label for="store_url">
                        <i class="fa-solid fa-link mr-1"></i>
                        {{ __('StarTV Stream URL') }}
                    </x-label>
                    <x-input id="store_url" class="block mt-1 w-full" type="url" wire:model="store_url" :value="old('store_url')"
                        placeholder="{{ __('StarTV Stream URL in the app store') }}" />
                </div>
                <div>
                    <x-label for="status">
                        <i class="fa-solid fa-toggle-on mr-1"></i>
                        {{ __('Status') }}
                    </x-label>
                    <select id="status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white {{ Auth::user()?->area === 'DTH'
                        ? 'focus:ring-secondary-600 focus:border-secondary-600 dark:focus:ring-secondary-500 dark:focus:border-secondary-500'
                        : 'focus:ring-primary-600 focus:border-primary-600 dark:focus:ring-primary-500 dark:focus:border-primary-500' }}"
                        wire:model="status" required>
                        <option value="" disabled>{{ __('Select status') }}</option>
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Inactive') }}</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end">
                <x-button class="flex justify-center items-center font-bold shadow mt-2">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    {{ __('Register new device') }}
                </x-button>
            </div>
        </form>
    </div>
</div>
