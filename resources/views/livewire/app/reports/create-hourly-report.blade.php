<div>
    <form wire:submit.prevent="saveReport" class="space-y-5">
        @foreach ($categories as $index => $category)
            <div x-data="{ open: open, editingName: false }" class="p-6 border bg-white dark:bg-gray-800 rounded-2xl shadow-2xl">
                <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                    <div class="flex items-center">
                        <button type="button" class="text-primary-600" @click="open = !open">
                            <i :class="open ? 'fas fa-chevron-down mr-2' : 'fas fa-chevron-right mr-2'"></i>
                        </button>
                        <div @click.stop="editingName = true" class="dark:text-white text-lg font-semibold">
                            <template x-if="!editingName">
                                <h3>{{ $category['name'] ?: __('New category') }}</h3>
                            </template>
                            <template x-if="editingName">
                                <x-input type="text" wire:model.lazy="categories.{{ $index }}.name"
                                    @click.away="editingName = false" @keydown.enter="editingName = false"
                                    placeholder="{{ __('Category name') }}" autofocus />
                            </template>
                        </div>
                        <span class="ml-2 bg-primary-100 text-primary-800 text-sm font-medium py-1 px-2 rounded-full">
                            {{ __('Contains') }} {{ count($category['channels']) }}
                            {{ count($category['channels']) === 1 ? __('channel') : __('channels') }}
                        </span>
                    </div>
                    <button type="button" wire:click="removeCategory({{ $index }})"
                        class="text-red-500 hover:text-red-700 dark:hover:text-red-400">
                        <i class="fas fa-trash-alt mr-1"></i>
                        {{ __('Delete') }}
                    </button>
                </div>
                <div x-show="open" class="mt-6 space-y-6">
                    @foreach ($category['channels'] as $channelIndex => $channel)
                        <div class="p-6 bg-gray-50 border dark:bg-gray-700 rounded-xl shadow-2xl">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-md font-semibold text-gray-800 dark:text-white">
                                    {{ __('Channel') }} {{ $channelIndex + 1 }}
                                </h4>
                                <button type="button"
                                    wire:click="removeChannel({{ $index }}, {{ $channelIndex }})"
                                    class="text-red-500 hover:text-red-700 dark:hover:text-red-400">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    {{ __('Remove channel') }}
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div x-data="{
                                    open: false,
                                    search: '',
                                    selectedChannelImage: @entangle('selectedChannelImage').defer,
                                    selectedChannelNumber: '',
                                    selectedChannelName: '',
                                    clearSelection() {
                                        this.selectedChannelImage = '';
                                        this.selectedChannelNumber = '';
                                        this.selectedChannelName = '';
                                        this.search = '';
                                        this.open = true; // Abre la lista solo si no hay canal seleccionado
                                    }
                                }">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fa-solid fa-tv mr-1.5"></i> {{ __('Channel') }}
                                    </label>
                                    <div class="relative">
                                        <input type="text" x-model="search"
                                            :placeholder="selectedChannelNumber ? selectedChannelNumber + ' ' + selectedChannelName :
                                                '{{ __('Search channel...') }}'"
                                            @focus="if (!selectedChannelNumber) open = true"
                                            @input="if (search === '') clearSelection()" @click.away="open = false"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 pl-14 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <div
                                            class="absolute left-3 top-1/2 transform -translate-y-1/2 flex items-center space-x-2">
                                            <img x-show="selectedChannelImage" :src="selectedChannelImage"
                                                class="w-8 h-8 object-contain object-center transition-opacity duration-200"
                                                x-cloak>
                                        </div>
                                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300 cursor-pointer transition-transform duration-200"
                                            :class="open ? 'rotate-180' : ''" @click="open = !open"></i>
                                    </div>
                                    <div class="relative">
                                        <div x-show="open" x-cloak
                                            class="absolute z-10 mt-1 w-full max-w-md bg-white border border-gray-300 rounded-lg shadow-2xl dark:bg-gray-700 dark:border-gray-600 transition-all duration-200 ease-in-out">
                                            <ul
                                                class="max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100 dark:scrollbar-thumb-gray-600 dark:scrollbar-track-gray-700">
                                                @php
                                                    $filteredChannels =
                                                        $category['name'] === 'Stingray'
                                                            ? $stingrayChannels
                                                            : $channels;
                                                @endphp
                                                @foreach ($filteredChannels as $channel)
                                                    <li x-show="search === '' || '{{ strtolower($channel->name) }}'.includes(search.toLowerCase()) || '{{ $channel->number }}'.includes(search)"
                                                        @click="
                                                            $wire.set('categories.{{ $index }}.channels.{{ $channelIndex }}.channel_id', {{ $channel->id }});
                                                            selectedChannelImage = '{{ $channel->image }}';
                                                            selectedChannelNumber = '{{ $channel->number }}';
                                                            selectedChannelName = '{{ $channel->name }}';
                                                            search = selectedChannelNumber + ' ' + selectedChannelName;
                                                            open = false;
                                                        "
                                                        class="cursor-pointer px-4 py-2 flex items-center space-x-3 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 ease-in-out">
                                                        <img src="{{ $channel->image }}"
                                                            class="w-10 h-10 object-contain object-center">
                                                        <span
                                                            class="text-sm font-medium text-gray-900 dark:text-gray-300">
                                                            {{ $channel->number }} {{ $channel->name }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fa-solid fa-bars-staggered mr-1.5"></i>
                                        {{ __('Stage') }}
                                    </label>
                                    <select
                                        wire:model="categories.{{ $index }}.channels.{{ $channelIndex }}.stage"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        @if (in_array($category['name'], ['CDN TELMEX', 'CDN CEF+'])) disabled @endif>
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
                                    <select
                                        wire:model="categories.{{ $index }}.channels.{{ $channelIndex }}.protocol"
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
                                    <select
                                        wire:model="categories.{{ $index }}.channels.{{ $channelIndex }}.media"
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
                                    <textarea wire:model="categories.{{ $index }}.channels.{{ $channelIndex }}.description" rows="3"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="{{ __('Enter a description of the problem') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <button type="button" wire:click="addChannel({{ $index }})"
                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-500">
                        <i class="fas fa-plus-circle mr-1"></i>
                        {{ __('Add channel') }}
                    </button>
                </div>
            </div>
        @endforeach
        <button type="button" wire:click="addCategory"
            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-500">
            <i class="fas fa-plus-circle mr-1"></i>
            {{ __('Add a new category') }}
        </button>
        <div class="flex justify-end gap-4">
            <button type="submit"
                class="py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-lg shadow font-bold text-base">
                <i class="fas fa-file-lines mr-1.5"></i>
                {{ __('Generate report') }}
            </button>
            <button data-modal-hide="create-hourly-report-modal" type="button"
                class="py-2 px-4 text-base font-bold text-gray-700 bg-white rounded-lg border border-gray-400 hover:border-primary-600 hover:text-primary-600 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:hover:text-primary-400 dark:hover:bg-gray-700">
                <i class="fa-solid fa-xmark"></i>
                {{ __('Discard') }}
            </button>
        </div>
    </form>
</div>
