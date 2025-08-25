<div class="space-y-4">
    <div class="flex flex-wrap gap-3 items-end">
        <div x-data="{
        open: false,
        search: '',
        selectedChannel: @entangle('selectedChannel').defer,
        channels: @js($channels->map(fn($c) => [
            'id' => $c->id,
            'number' => $c->number,
            'name' => $c->name,
            'image' => $c->image,
        ])),
        get filteredChannels() {
            if (this.search === '') return this.channels;
            const term = this.search.toLowerCase();
            return this.channels.filter(c =>
                c.name.toLowerCase().includes(term) ||
                c.number.toString().includes(term)
            );
        },
        selectChannel(channel) {
            this.selectedChannel = channel; // guardamos el objeto completo
            this.search = channel.number + ' ' + channel.name;
            this.open = false;
            $wire.set('selectedChannel', channel.id);
        }
    }" class="relative" style="width: 320px;">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                <i class="fa-solid fa-tv mr-1.5 mb-3"></i>
                {{ __('Selector de canal') }}
            </label>

            <div class="relative">
                <img x-show="selectedChannel" :src="selectedChannel.image"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 object-contain object-center">

                <input type="text" x-model="search" @focus="open = true" @click="open = true; search = ''"
                    @input="open = true" @click.away="open = false" :placeholder="'Buscar canal...'"
                    class="w-full min-w-[180px] max-w-[320px] p-2.5 pl-14 rounded-lg bg-gray-50 border border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-primary-600 focus:border-primary-600"
                    autocomplete="off">
            </div>

            <div x-show="open"
                class="absolute z-10 mt-1 w-full max-w-md bg-white border border-gray-300 rounded-lg shadow-2xl dark:bg-gray-700 dark:border-gray-600 transition-all duration-200 ease-in-out">
                <ul
                    class="max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100 dark:scrollbar-thumb-gray-600 dark:scrollbar-track-gray-700">
                    <template x-for="channel in filteredChannels" :key="channel.id">
                        <li @click="selectChannel(channel)"
                            class="cursor-pointer px-4 py-2 flex items-center space-x-3 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 ease-in-out">
                            <img :src="channel.image" class="w-10 h-10 object-contain object-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-300"
                                x-text="channel.number + ' ' + channel.name"></span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                <i class="fa-solid fa-clock mr-1.5"></i>
                {{ __('Selector de tiempo') }}
            </label>
            <select wire:model.live="preset"
                class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                <option value="1h">Última hora</option>
                <option value="6h">Últimas 6 horas</option>
                <option value="12h">Últimas 12 horas</option>
                <option value="24h">Último día</option>
                <option value="7d">Última semana</option>
            </select>
        </div>
    </div>

    <iframe wire:key="{{ $this->iframeKey }}" src="{{ $this->grafanaUrl }}" width="1000" height="300" frameborder="0"
        loading="lazy" referrerpolicy="no-referrer" class="w-full rounded border"></iframe>

    <div class="text-xs text-gray-500">
        URL actual: <code class="break-all">{{ $this->grafanaUrl }}</code>
    </div>
</div>
