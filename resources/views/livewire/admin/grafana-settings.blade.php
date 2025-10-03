<form wire:submit.prevent="save" class="space-y-6">
    <div>
        <label for="dth_url" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
            <i class="fa-solid fa-link mr-1"></i> {{ __('Multicast Downlink DTH Base URL') }}
        </label>
        <input type="text" id="dth_url" wire:model.defer="dth_url" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500" required>
    </div>
    <div>
        <label for="cutv_url" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
            <i class="fa-solid fa-link mr-1"></i> {{ __('CUTV Base URL') }}
        </label>
        <input type="text" id="cutv_url" wire:model.defer="cutv_url" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500" required>
    </div>
    <div>
        <label for="api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
            <i class="fa-solid fa-key mr-1"></i> {{ __('Grafana API Key') }}
        </label>
        <input type="text" id="api_key" wire:model.defer="api_key" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500" required>
    </div>
    <div class="flex justify-end items-center gap-4">
        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg shadow">
            <i class="fa-solid fa-save mr-1"></i> {{ __('Save') }}
        </button>
        <span class="text-green-600 font-semibold" x-show="$wire.success">{{ __('Settings saved!') }}</span>
        <span class="text-red-600 font-semibold" x-show="$wire.error">{{ $error }}</span>
    </div>
</form>
