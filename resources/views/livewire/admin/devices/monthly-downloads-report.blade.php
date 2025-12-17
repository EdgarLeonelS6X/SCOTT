<div class="space-y-4">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex-1 flex items-center">
            <div class="flex items-center gap-4 w-full rounded-lg h-[44px]">
                <div class="flex items-center gap-3 w-full justify-start">
                    <div class="flex items-center gap-2 w-44">
                        <label class="sr-only">{{ __('Month') }}</label>
                        <select aria-label="Month" wire:model="month" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white {{ Auth::user()?->area === 'DTH'
                            ? 'focus:ring-secondary-600 focus:border-secondary-600 dark:focus:ring-secondary-500 dark:focus:border-secondary-500'
                            : 'focus:ring-primary-600 focus:border-primary-600 dark:focus:ring-primary-500 dark:focus:border-primary-500' }}">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}">{{ __(DateTime::createFromFormat('!m', $m)->format('F')) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-44">
                        <label class="sr-only">{{ __('Year') }}</label>
                        <select aria-label="Year" wire:model="year" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white {{ Auth::user()?->area === 'DTH'
                            ? 'focus:ring-secondary-600 focus:border-secondary-600 dark:focus:ring-secondary-500 dark:focus:border-secondary-500'
                            : 'focus:ring-primary-600 focus:border-primary-600 dark:focus:ring-primary-500 dark:focus:border-primary-500' }}">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <div title="{{ __('Devices') }}" class="h-[44px] px-2 grid grid-cols-[28px_1fr_auto] items-center rounded-md bg-blue-50/40 dark:bg-sky-800/10 border border-blue-100 dark:border-sky-600 text-sm text-sky-800 dark:text-sky-200 min-w-[104px] hover:shadow-sm transition-shadow">
                            <div class="flex items-center justify-center text-sky-600 dark:text-sky-300">
                                <i class="fa-solid fa-microchip text-base"></i>
                            </div>
                            <div class="text-left text-xs uppercase tracking-wide font-medium text-sky-700 dark:text-sky-200 px-2">{{ __('Devices') }}</div>
                            <div class="text-right font-semibold text-base text-sky-800 dark:text-sky-200 ml-[6px]">{{ $devices->count() }}</div>
                        </div>

                        <div title="{{ __('Total downloads for month') }}" class="h-[44px] px-2 grid grid-cols-[28px_1fr_auto] items-center rounded-md bg-green-50/40 dark:bg-emerald-900/10 border border-green-200 dark:border-green-700 text-sm text-green-800 min-w-[104px] hover:shadow-sm transition-shadow ml-1">
                            <div class="flex items-center justify-center text-green-600 dark:text-green-300">
                                <i class="fa-solid fa-chart-column text-base"></i>
                            </div>
                            <div class="text-left text-xs uppercase tracking-wide font-medium text-green-700 dark:text-gray-200 px-2">{{ __('Total') }}</div>
                            <div class="text-right font-semibold text-base text-green-800 dark:text-green-300 ml-[6px]">{{ $this->total }}</div>
                        </div>

                        <div title="{{ __('Average downloads') }}" class="h-[44px] px-2 grid grid-cols-[28px_1fr_auto] items-center rounded-md bg-amber-50/40 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-700 text-sm text-amber-800 min-w-[104px] hover:shadow-sm transition-shadow ml-1">
                            <div class="flex items-center justify-center text-amber-600 dark:text-amber-300">
                                <i class="fa-solid fa-divide text-base"></i>
                            </div>
                            <div class="text-left text-xs uppercase tracking-wide font-medium text-amber-700  dark:text-amber-200 px-2">{{ __('Average') }}</div>
                            <div class="text-right font-semibold text-base text-amber-800 dark:text-amber-300 ml-[6px]">{{ $this->average }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between lg:justify-end gap-3">
            <x-button wire:click="save" wire:target="save">
                <i class="fa-solid fa-floppy-disk mr-1"></i>
                <span>{{ __('Save report') }}</span>
            </x-button>
        </div>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg mt-4 shadow-sm border border-gray-100 dark:border-gray-600">
        <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-300">
            <thead class="text-xs uppercase text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th class="px-5 py-3 text-left w-2/3">
                        <i class="fa-solid fa-hard-drive mr-1"></i>
                        {{ __('Device') }}
                    </th>
                    <th class="px-5 py-3 text-left">
                        <i class="fa-solid fa-download mr-1"></i>
                        {{ __('Downloads') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($devices as $device)
                    <tr>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @php $img = $device->image ?? $device->image_url ?? $device->thumbnail ?? null; @endphp
                                <div class="w-8 h-8 overflow-hidden flex items-center justify-center">
                                    @if($img)
                                        <img src="{{ $img }}" alt="{{ $device->name }}" class="w-8 h-8 object-contain object-center" loading="lazy" />
                                    @else
                                        <i class="fa-solid fa-hard-drive text-gray-400"></i>
                                    @endif
                                </div>

                                <div class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $device->name }}</div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <x-input type="number" min="0" wire:model.defer="counts.{{ $device->id }}" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
