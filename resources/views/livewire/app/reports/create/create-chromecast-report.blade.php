<div x-data="{ status: @entangle('reportData.chromecast.status') }">
    <form wire:submit.prevent="saveReport" class="space-y-5">
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <x-checkbox type="checkbox" x-model="status" wire:model="reportData.chromecast.status"
                    class="rounded border-gray-300 focus:ring-primary-500" />
                @error('reportData.reviewed_by')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
                @error('reportData.chromecast.description')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
                @error('reportData.chromecast.status')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
                @error('error')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="ml-3 text-sm">
                <label class="flex items-center">
                    <span class="text-gray-600 dark:text-gray-400" x-text="status ? '{{ __('We proceed to write a problem.') }}' : '{{ __('No problems found.') }}'"></span>
                </label>
            </div>
        </div>

        <div x-show="status" x-transition>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                <i class="fa-solid fa-comment mr-1.5"></i>
                {{ __('Description') }}
            </label>
            <textarea wire:model="reportData.chromecast.description" rows="4"
                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                placeholder="{{ __('Describe the problem...') }}"></textarea>
        </div>

        <div class="flex flex-col md:flex-row justify-end gap-4 mt-6">
            <button type="submit"
                class="w-full md:w-auto py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-bold text-base">
                <i class="fas fa-file-lines mr-1.5"></i> {{ __('Generate report') }}
            </button>
            <button type="button" data-modal-hide="create-chromecast-report-modal"
                class="py-2 px-4 text-base font-bold text-gray-700 bg-white rounded-lg border border-gray-400 hover:border-primary-600 hover:text-primary-600 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:hover:text-primary-400 dark:hover:bg-gray-700">
                <i class="fa-solid fa-xmark"></i> {{ __('Discard') }}
            </button>
        </div>
    </form>
</div>
