<div>
    <x-validation-errors></x-validation-errors>
    <form wire:submit.prevent="saveReport" class="space-y-8">
        <div x-data="{ open: true, editingName: false }" class="p-6 border bg-white dark:bg-gray-800 rounded-2xl shadow-2xl">
            <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                <div class="flex items-center">
                    <div @click.stop="editingName = true" class="dark:text-white text-lg font-semibold">
                        <template x-if="!editingName">
                            <h3 title="{{ __('Click here to edit the name of the report category') }}">
                                {{ $category['name'] ?: __('New report') }}</h3>
                        </template>
                        <template x-if="editingName">
                            <x-input type="text" wire:model.lazy="category.name" @click.away="editingName = false"
                                @keydown.enter="editingName = false" placeholder="{{ __('Category name') }}"
                                autofocus />
                        </template>
                    </div>
                    <span class="ml-2 bg-primary-100 text-primary-800 text-sm font-medium py-1 px-2 rounded-full">
                        {{ __('Contains') }} {{ $this->getChannelCount(0) }}
                        {{ $this->getChannelCount(0) === 1 ? __('channel') : __('channels') }}
                    </span>
                </div>
                <button type="button" class="text-primary-600" @click="open = !open">
                    <i :class="open ? 'fas fa-chevron-down' : 'fas fa-chevron-right'"></i>
                </button>
            </div>
            <div x-show="open" class="mt-6 space-y-6">
                @foreach ($category['channels'] as $channelIndex => $channel)
                    <div class="p-6 bg-gray-50 border dark:bg-gray-700 rounded-xl shadow-2xl">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-white">
                                {{ __('Channel') }} {{ $channelIndex + 1 }}
                            </h4>
                            <button type="button" wire:click="removeChannel({{ $channelIndex }})"
                                class="text-red-500 hover:text-red-700 dark:hover:text-red-400">
                                <i class="fas fa-times-circle mr-1"></i>
                                {{ __('Remove channel') }}
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fa-solid fa-tv mr-1.5"></i>
                                    {{ __('Channel') }}
                                </label>
                                <select wire:model="category.channels.{{ $channelIndex }}.channel_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option disabled selected value="">
                                        {{ __('Select a channel') }}
                                    </option>
                                    @foreach ($channels as $channel)
                                        <option value="{{ $channel->id }}">{{ $channel->number }}  {{ $channel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fa-solid fa-bars-staggered mr-1.5"></i>
                                    {{ __('Stage') }}
                                </label>
                                <select wire:model="category.channels.{{ $channelIndex }}.stage"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option disabled selected value="">
                                        {{ __('Select a stage') }}
                                    </option>
                                    @foreach ($stages as $stage)
                                        <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fa-solid fa-server mr-1.5"></i>
                                    {{ __('Protocol') }}
                                </label>
                                <select wire:model="category.channels.{{ $channelIndex }}.protocol"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="" disabled selected>
                                        {{ __('Select a protocol') }}
                                    </option>
                                    @foreach ($protocols as $protocol)
                                        <option value="{{ $protocol }}">{{ $protocol }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fa-solid fa-forward mr-1.5"></i>
                                    {{ __('Audiovisual') }}
                                </label>
                                <select wire:model="category.channels.{{ $channelIndex }}.media"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="" disabled selected>
                                        {{ __('Select an audiovisual problem') }}
                                    </option>
                                    @foreach ($mediaOptions as $media)
                                        <option value="{{ $media }}">{{ ucfirst($media) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fa-solid fa-comment mr-1.5"></i>
                                    {{ __('Description (Optional)') }}
                                </label>
                                <textarea wire:model="category.channels.{{ $channelIndex }}.description" rows="3"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="{{ __('Enter a description of the problem') }}"></textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
                <button type="button" wire:click="addChannel"
                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-500 flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    {{ __('Add channel') }}
                </button>
            </div>
        </div>
        <div class="flex justify-end gap-4">
            <button type="submit"
                class="py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-lg shadow font-bold text-base">
                <i class="fas fa-file-lines mr-1.5"></i>
                {{ __('Generate report') }}
            </button>
            <button data-modal-hide="create-momently-report-modal" type="button"
                class="flex items-center gap-2 py-2 px-4 text-base font-bold text-gray-700 bg-white rounded-lg border border-gray-400 hover:border-primary-600 hover:text-primary-600 focus:ring-4 focus:ring-primary-200 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:hover:text-primary-400 dark:hover:bg-gray-700">
                <i class="fa-solid fa-xmark"></i>
                {{ __('Discard') }}
            </button>
        </div>
    </form>
</div>
