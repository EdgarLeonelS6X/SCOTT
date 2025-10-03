<div class="w-full mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                <i class="fa-solid fa-diagram-project text-primary-600"></i>
                {{ __('Multicast Downlink DTH') }}
            </h2>
            <span class="text-xs text-gray-400 dark:text-gray-500 md:block hidden flex items-center gap-2">
                {{ __('Auto-refresh 5s') }}
                <button type="button" data-modal-target="grafana-config-modal" data-modal-toggle="grafana-config-modal"
                    class="ml-2 text-primary-600 hover:text-primary-700" title="{{ __('Edit Grafana settings') }}">
                    <i class="fa-solid fa-gear"></i>
                </button>
            </span>
        </div>

        <div id="grafana-config-modal" tabindex="-1"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full mt-12">
            <div class="relative w-full max-w-lg max-h-full mx-auto">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                            <i class="fa-solid fa-gear mr-2 text-primary-600"></i>
                            {{ __('Edit Grafana panel settings') }}
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="grafana-config-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">{{ __('Close') }}</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        @livewire('admin.grafana-settings')
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div x-data="{
                open: false,
                search: '',
                selectedChannel: undefined,
                channels: @js($channels->map(fn($c) => [
                    'id' => $c->id,
                    'number' => $c->number,
                    'name' => $c->name,
                    'image' => $c->image,
                    'url' => $c->url,
                    'multicast' => $channelMulticasts[$c->number] ?? null,
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
                    $wire.set('selectedChannel', channel.id);
                },
                init() {
                    this.$nextTick(() => {
                        if (this.$wire.selectedChannel) {
                            const found = this.channels.find(c => c.id == this.$wire.selectedChannel);
                            if (found) {
                                this.selectedChannel = found;
                                this.search = found.number + ' ' + found.name;
                            }
                        }
                        this.$watch('$wire.selectedChannel', (id) => {
                            const found = this.channels.find(c => c.id == id);
                            this.selectedChannel = found || undefined;
                            if (found) {
                                this.search = found.number + ' ' + found.name;
                            }
                        });
                    });
                }
            }" x-init="init()" class="flex flex-col gap-4">
                <label class="block text-base font-semibold text-gray-700 dark:text-gray-200">
                    <i class="fa-solid fa-tv mr-1.5"></i>
                    {{ __('Select channel') }}
                </label>

                <div class="flex flex-col md:flex-row items-stretch gap-4">
                    <div class="relative w-full">
                        <img x-show="selectedChannel && search === (selectedChannel.number + ' ' + selectedChannel.name)"
                            :src="selectedChannel.image"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 object-contain rounded bg-white dark:bg-gray-700">
                        <input type="text" x-model="search" @focus="open = true" @click="open = true"
                            @input="open = true" @click.away="open = false" placeholder="{{ __('Search channel...') }}"
                            class="w-full pl-16 pr-4 py-3 rounded-lg bg-gray-50 border border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all cursor-pointer"
                            autocomplete="off">

                        <div x-show="open"
                            class="absolute z-20 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-2xl dark:bg-gray-700 dark:border-gray-600">
                            <ul
                                class="max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600">
                                <template x-for="channel in filteredChannels" :key="channel.id">
                                    <li @click="selectChannel(channel)"
                                        class="cursor-pointer px-4 py-2 flex items-center gap-3 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition">
                                        <img :src="channel.image"
                                            class="w-8 h-8 object-contain rounded border-gray-200 dark:border-gray-700">
                                        <span class="text-base font-medium text-gray-900 dark:text-gray-200"
                                            x-text="channel.number + ' ' + channel.name"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300 cursor-pointer transition-transform duration-200"
                            :class="open ? 'rotate-180' : ''" @click="open = !open"></i>
                    </div>

                    <div class="flex items-center">
                        <button type="button" x-show="selectedChannel && selectedChannel.multicast"
                            @click="window.downloadM3U(selectedChannel.multicast, selectedChannel.number, selectedChannel.name)"
                            class="flex justify-center items-center w-full md:w-auto text-white bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:focus:ring-primary-800 font-semibold rounded-lg text-base px-4 py-3 shadow transition">
                            <i class="fa-solid fa-video mr-2"></i>
                            {{ __('Multicast') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <label class="block text-base font-semibold text-gray-700 dark:text-gray-200">
                    <i class="fa-solid fa-clock mr-1.5"></i>
                    {{ __('Select time range') }}
                </label>
                <select wire:model.live="preset"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-700 dark:text-white transition-all">
                    <option value="5m">{{ __('Last 5 minutes') }}</option>
                    <option value="15m">{{ __('Last 15 minutes') }}</option>
                    <option value="30m">{{ __('Last 30 minutes') }}</option>
                    <option value="45m">{{ __('Last 45 minutes') }}</option>
                    <option value="1h">{{ __('Last hour') }}</option>
                    <option value="6h">{{ __('Last 6 hours') }}</option>
                    <option value="12h">{{ __('Last 12 hours') }}</option>
                    <option value="24h">{{ __('Last day') }}</option>
                    <option value="7d">{{ __('Last week') }}</option>
                </select>
                <div class="text-xs text-gray-400 dark:text-gray-500">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    {{ __('The selected time range will update the Grafana panel.') }}
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-6 shadow-2xl">
            <iframe wire:key="{{ $this->iframeKey }}" src="{{ $this->grafanaUrl }}" height="500" frameborder="0"
                loading="lazy" referrerpolicy="no-referrer"
                class="w-full rounded-lg border shadow-inner transition-all duration-200"
                x-data="{ theme: localStorage.getItem('color-theme') === 'dark' ? 'dark' : 'light' }" x-init="
                    $watch('theme', value => {
                        $wire.set('theme', value);
                    });
                    window.addEventListener('storage', (e) => {
                        if (e.key === 'color-theme') {
                            theme = localStorage.getItem('color-theme') === 'dark' ? 'dark' : 'light';
                        }
                    });
                    window.addEventListener('grafana-theme-changed', (e) => {
                        theme = e.detail.theme;
                    });
                    theme = localStorage.getItem('color-theme') === 'dark' ? 'dark' : 'light';
                    $wire.set('theme', theme);
                "></iframe>

            {{-- <div class="text-xs text-gray-500 mt-4">
                {{ __('Current URL:') }} <code class="break-all">{{ $this->grafanaUrl }}</code>
            </div> --}}
        </div>
    </div>
</div>

<script>
    window.downloadM3U = function (multicast, number, name) {
        if (!multicast) return;
        const udpUrl = 'udp://@' + multicast;
        const m3u = "#EXTM3U\n#EXTINF:-1," + (number ? number + ' ' : '') + (name ?? 'Canal') + "\n" + udpUrl + "\n";
        let cleanName = (number ? number + '_' : '') + (name ? name : 'canal');
        cleanName = cleanName.replace(/[^a-zA-Z0-9-_]/g, '_');
        const filename = cleanName + '.m3u';
        const blob = new Blob([m3u], { type: "audio/x-mpegurl" });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        setTimeout(() => {
            URL.revokeObjectURL(a.href);
            document.body.removeChild(a);
        }, 100);
    }

    function syncGrafanaThemeToLivewire() {
        const theme = localStorage.getItem('color-theme') === 'dark' ? 'dark' : 'light';
        if (window.Livewire) {
            window.Livewire.find(document.querySelector('[wire\:key]')?.getAttribute('wire:key'))?.set('theme', theme);
        } else if (window.livewire) {
            window.livewire.emit('setTheme', theme);
        }
    }

    window.addEventListener('storage', (e) => {
        if (e.key === 'color-theme') {
            syncGrafanaThemeToLivewire();
        }
    });
    document.addEventListener('DOMContentLoaded', syncGrafanaThemeToLivewire);

    document.addEventListener('click', function (e) {
        if (e.target.closest('#theme-toggle')) {
            setTimeout(syncGrafanaThemeToLivewire, 100);
        }
    });
</script>
