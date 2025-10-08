<x-admin-layout :breadcrumbs="[
    [
        'name' => __('Dashboard'),
        'icon' => 'fa-solid fa-wrench',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => __('Grafana'),
        'icon' => 'fa-solid fa-chart-pie',
    ],
]">

    <div class="w-full mb-4 flex flex-col md:flex-row gap-6">
        <div class="flex-1 min-h-[300px] relative" x-data="{ open: false }">
            <div class="flex items-center justify-between mb-4 px-4 py-3 rounded-xl bg-gradient-to-r from-orange-50 via-white to-primary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 shadow-sm border-b-2 border-primary-100 dark:border-primary-900">
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900 shadow border border-orange-200 dark:border-orange-800">
                        <i class="fa-brands fa-grafana text-3xl text-orange-500"></i>
                    </span>
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg md:text-xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">{{ __('Grafana Panel 1') }}</h2>
                            <span class="text-xs px-2 py-0.5 rounded-full border border-primary-200 dark:border-primary-800 bg-primary-50 dark:bg-primary-900 text-primary-700 dark:text-primary-200 font-semibold">{{ __('Preview') }}</span>
                        </div>
                    </div>
                </div>
                <div class="relative group">
                    <button @click="open = true"
                        class="flex items-center justify-center w-10 h-10 rounded-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-primary-600 dark:text-primary-300 shadow hover:bg-primary-50 dark:hover:bg-primary-800 hover:text-primary-800 transition"
                        aria-label="{{ __('Edit panel') }}">
                        <i class="fa-solid fa-gear text-lg"></i>
                    </button>
                    <span class="absolute -top-8 right-0 bg-gray-900 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition pointer-events-none z-50" style="white-space:nowrap;">{{ __('Editar configuración') }}</span>
                </div>
            </div>
            @livewire('app.grafana.grafana-first')
            <div x-show="open" x-cloak class="fixed inset-0 flex items-center justify-center z-50 bg-black/20">
                <div @click.away="open = false" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 w-full max-w-xs mx-auto relative animate-fade-in-up" style="min-width: 320px;">
                    <h4 class="font-semibold mb-4 text-gray-700 dark:text-gray-200 text-base flex items-center gap-2"><i class="fa-brands fa-grafana text-orange-400"></i> {{ __('Editar Panel 1') }}</h4>
                    <form wire:submit.prevent="updatePanel(1)" class="space-y-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1 font-semibold">{{ __('URL') }}</label>
                            <input type="text" wire:model.live="panel1_url" class="w-full rounded border border-gray-300 dark:border-gray-700 text-sm px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 dark:bg-gray-800 transition" placeholder="https://..." />
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1 font-semibold">{{ __('API KEY') }}</label>
                            <input type="text" wire:model.live="panel1_key" class="w-full rounded border border-gray-300 dark:border-gray-700 text-sm px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 dark:bg-gray-800 transition" placeholder="••••••••" />
                        </div>
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" @click="open = false" class="flex items-center gap-1 text-xs px-4 py-2 rounded bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                <i class="fa-solid fa-xmark"></i> <span>{{ __('Cerrar') }}</span>
                            </button>
                            <button type="submit" class="flex items-center gap-1 text-xs px-4 py-2 rounded bg-primary-600 text-white hover:bg-primary-700 transition font-semibold">
                                <i class="fa-solid fa-floppy-disk"></i> <span>{{ __('Guardar') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="flex-1 min-h-[300px] relative" x-data="{ open: false }">
            <div class="flex items-center justify-between mb-4 px-4 py-3 rounded-xl bg-gradient-to-r from-orange-50 via-white to-primary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 shadow-sm border-b-2 border-primary-100 dark:border-primary-900">
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900 shadow border border-orange-200 dark:border-orange-800">
                        <i class="fa-brands fa-grafana text-3xl text-orange-500"></i>
                    </span>
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg md:text-xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">{{ __('Grafana Panel 2') }}</h2>
                            <span class="text-xs px-2 py-0.5 rounded-full border border-primary-200 dark:border-primary-800 bg-primary-50 dark:bg-primary-900 text-primary-700 dark:text-primary-200 font-semibold">{{ __('Preview') }}</span>
                        </div>
                    </div>
                </div>
                <div class="relative group">
                    <button @click="open = true"
                        class="flex items-center justify-center w-10 h-10 rounded-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-primary-600 dark:text-primary-300 shadow hover:bg-primary-50 dark:hover:bg-primary-800 hover:text-primary-800 transition"
                        aria-label="{{ __('Edit panel') }}">
                        <i class="fa-solid fa-gear text-lg"></i>
                    </button>
                    <span class="absolute -top-8 right-0 bg-gray-900 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition pointer-events-none z-50" style="white-space:nowrap;">{{ __('Editar configuración') }}</span>
                </div>
            </div>
            @livewire('app.grafana.grafana-second')
            <div x-show="open" x-cloak class="fixed inset-0 flex items-center justify-center z-50 bg-black/20">
                <div @click.away="open = false" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 w-full max-w-xs mx-auto relative animate-fade-in-up" style="min-width: 320px;">
                    <h4 class="font-semibold mb-4 text-gray-700 dark:text-gray-200 text-base flex items-center gap-2"><i class="fa-brands fa-grafana text-orange-400"></i> {{ __('Editar Panel 2') }}</h4>
                    <form wire:submit.prevent="updatePanel(2)" class="space-y-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1 font-semibold">{{ __('URL') }}</label>
                            <input type="text" wire:model.live="panel2_url" class="w-full rounded border border-gray-300 dark:border-gray-700 text-sm px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 dark:bg-gray-800 transition" placeholder="https://..." />
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1 font-semibold">{{ __('API KEY') }}</label>
                            <input type="text" wire:model.live="panel2_key" class="w-full rounded border border-gray-300 dark:border-gray-700 text-sm px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 dark:bg-gray-800 transition" placeholder="••••••••" />
                        </div>
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" @click="open = false" class="flex items-center gap-1 text-xs px-4 py-2 rounded bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                <i class="fa-solid fa-xmark"></i> <span>{{ __('Cerrar') }}</span>
                            </button>
                            <button type="submit" class="flex items-center gap-1 text-xs px-4 py-2 rounded bg-primary-600 text-white hover:bg-primary-700 transition font-semibold">
                                <i class="fa-solid fa-floppy-disk"></i> <span>{{ __('Guardar') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
