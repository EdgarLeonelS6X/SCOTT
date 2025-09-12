<div>
    <form wire:submit.prevent="saveReport" class="space-y-5">
        @foreach ($categories as $index => $category)
            @php
                $isFixed = in_array($category['name'], ['CDN TELMEX', 'CDN CEF+', 'STINGRAY']);
            @endphp
            <div x-data="{
                open: true,
                editingName: false,
                isFixed: @json($isFixed),
            }">
                <div class="p-4 md:p-6 border bg-white dark:bg-gray-800 rounded-2xl shadow-2xl mb-5">
                    <div class="flex items-center justify-between cursor-pointer" @click="if (!editingName) open = !open">
                        <div :class="isFixed ? 'flex items-center gap-2 w-full' : 'flex flex-col w-full gap-2'">
                            <div
                                :class="isFixed ? 'flex items-center justify-between gap-2 w-full' :
                                    'flex items-center gap-2 min-w-0'">
                                <button type="button" class="text-primary-600" @click.stop="open = !open">
                                    <i :class="open ? 'fas fa-chevron-down' : 'fas fa-chevron-right'"></i>
                                </button>
                                <div class="flex flex-col dark:text-white text-base sm:text-lg font-semibold relative">
                                    <h3 x-show="!editingName" title="{{ __('Click here to edit the category name') }}"
                                        @click.stop="if (!isFixed) editingName = true"
                                        class="cursor-pointer px-3 py-2 rounded-full shadow-md inline-flex items-center gap-2 transition bg-gray-50 border dark:bg-gray-700 dark:border-white max-w-[280px] md:max-w-md">
                                        <i class="fa-solid fa-pen text-gray-800 dark:text-gray-200"
                                            x-show="!isFixed">...</i>
                                        <span class="truncate max-w-[220px] sm:max-w-[240px] md:max-w-md"
                                            x-text="$wire.categories[{{ $index }}].name">
                                        </span>
                                    </h3>
                                    <x-input type="text" x-show="editingName" x-ref="categoryInput"
                                        wire:model.defer="categories.{{ $index }}.name" @click.stop
                                        @click.away="editingName = false" @keydown.enter.prevent="editingName = false"
                                        placeholder="{{ __('Category name') }}" x-bind:disabled="isFixed"
                                        class="w-full" />
                                </div>
                                <template x-if="isFixed">
                                    <div class="ml-auto">
                                        <span
                                            class="bg-primary-100 text-primary-800 text-sm font-medium py-1 px-3 rounded-full whitespace-nowrap">
                                            <span class="hidden sm:inline">{{ __('Contains') }}</span> {{ $this->getChannelCount($index) }}
                                            {{ $this->getChannelCount($index) === 1 ? __('Channel') : __('Channels') }}
                                        </span>
                                    </div>
                                </template>
                            </div>
                            <template x-if="!isFixed">
                                <div class="flex items-center gap-2 w-full mt-2 ml-auto">
                                    <div class="flex flex-1 items-center gap-2">
                                        <span
                                            class="bg-primary-100 text-primary-800 text-sm font-medium py-1 px-3 rounded-full whitespace-nowrap">
                                            <span class="hidden sm:inline">{{ __('Contains') }}</span> {{ $this->getChannelCount($index) }}
                                            {{ $this->getChannelCount($index) === 1 ? __('Channel') : __('Channels') }}
                                        </span>
                                    </div>
                                    <button type="button" @click.stop="$wire.removeCategory({{ $index }})"
                                        class="inline-flex items-center text-red-500 hover:text-red-700 dark:hover:text-red-400 text-sm">
                                        <i class="fas fa-trash-alt mr-1"></i>{{ __('Delete category') }}
                                    </button>
                                </div>
                            </template>
                        </div>
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
                                    @php
                                        $channelsList = collect(
                                            $category['name'] === 'STINGRAY' ? $stingrayChannels : $channels,
                                        )
                                            ->map(
                                                fn($c) => [
                                                    'id' => $c->id,
                                                    'number' => $c->number,
                                                    'name' => $c->name,
                                                    'image' => $c->image,
                                                ],
                                            )
                                            ->values();

                                        $currentChannelId = data_get(
                                            $categories[$index]['channels'][$channelIndex] ?? [],
                                            'channel_id',
                                        );

                                        $selectedChannel = $channelsList->firstWhere('id', $currentChannelId);
                                    @endphp
                                    <div x-data="{
                                        open: false,
                                        search: '',
                                        selectedChannel: undefined,
                                        channels: @js($channelsList->map(fn($c) => [
                                            'id' => $c['id'],
                                            'number' => $c['number'],
                                            'name' => $c['name'],
                                            'image' => $c['image']
                                        ])),
                                        get filteredChannels() {
                                            if (this.open && this.selectedChannel && this.search === (this.selectedChannel.number + ' ' + this.selectedChannel.name)) {
                                                return this.channels;
                                            }
                                            if (this.search === '') return this.channels;
                                            const term = this.search.toLowerCase();
                                            return this.channels.filter(c => {
                                                const combined = (c.number + ' ' + c.name).toLowerCase();
                                                return c.name.toLowerCase().includes(term)
                                                    || c.number.toString().includes(term)
                                                    || combined.includes(term);
                                            });
                                        },
                                        selectChannel(channel) {
                                            this.selectedChannel = channel;
                                            this.search = channel.number + ' ' + channel.name;
                                            this.open = false;
                                            $wire.set('categories.{{ $index }}.channels.{{ $channelIndex }}.channel_id', channel.id);
                                        },
                                        init() {
                                            this.$nextTick(() => {
                                                const selectedId = $wire.get('categories.{{ $index }}.channels.{{ $channelIndex }}.channel_id');
                                                if (selectedId) {
                                                    const found = this.channels.find(c => c.id == selectedId);
                                                    if (found) {
                                                        this.selectedChannel = found;
                                                        this.search = found.number + ' ' + found.name;
                                                    }
                                                }
                                                this.$watch(() => $wire.get('categories.{{ $index }}.channels.{{ $channelIndex }}.channel_id'), (id) => {
                                                    const found = this.channels.find(c => c.id == id);
                                                    this.selectedChannel = found || undefined;
                                                    if (found) {
                                                        this.search = found.number + ' ' + found.name;
                                                    }
                                                });
                                            });
                                        }
                                    }" x-init="init()">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="fa-solid fa-tv mr-1.5"></i> {{ __('Channel') }}
                                        </label>
                                        <div class="relative flex-1">
                                            <img x-show="selectedChannel && search === (selectedChannel.number + ' ' + selectedChannel.name)" :src="selectedChannel?.image"
                                                class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 object-contain rounded bg-white dark:bg-gray-700">
                                            <input type="text" x-model="search" @focus="open = true" @click="open = true"
                                                @input="open = true" @click.away="open = false" placeholder="{{ __('Search channel...') }}"
                                                class="w-full pl-12 pr-2 py-2 rounded-md bg-gray-50 border border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-all h-10"
                                                autocomplete="off">
                                            <div x-show="open"
                                                class="absolute z-20 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-2xl dark:bg-gray-700 dark:border-gray-600">
                                                <ul class="max-h-48 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600">
                                                    <template x-for="channel in filteredChannels" :key="channel.id">
                                                        <li @click="selectChannel(channel)"
                                                            class="cursor-pointer px-3 py-1.5 flex items-center gap-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition">
                                                            <img :src="channel.image" class="w-6 h-6 object-contain rounded border-gray-200 dark:border-gray-700">
                                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-200 truncate max-w-[280px] block" x-text="channel.number + ' ' + channel.name" :title="channel.number + ' ' + channel.name"></span>
                                                        </li>
                                                    </template>
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
                                    <div class="col-span-1 md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="fa-solid fa-comment mr-1.5"></i>
                                            {{ __('Description') }}
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
        <div class="flex flex-col md:flex-row justify-end gap-4 mt-6">
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
