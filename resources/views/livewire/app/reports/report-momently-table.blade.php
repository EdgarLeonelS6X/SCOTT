<div class="w-full md:w-2/3 pl-6" wire:key="reports-table">
    <div class="bg-white dark:bg-gray-800 relative shadow-2xl sm:rounded-lg overflow-hidden">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
            <div class="w-full md:w-1/2">
                <form class="flex items-center">
                    <label for="simple-search" class="sr-only">Search</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="simple-search" wire:model.live="search"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="{{ __('Search') }}" required="" autofocus>
                    </div>
                </form>
            </div>
            <div class="w-full md:w-auto flex items-center justify-end space-x-3">
                <a href=""
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    <i class="fa-solid fa-folder mr-1"></i>
                    {{ __('Report history') }}
                </a>
            </div>
        </div>
        <div>
            <table class="w-full text-sm text-gray-600 dark:text-gray-300 border-collapse">
                <thead class="bg-gray-100 dark:bg-gray-700 text-xs font-bold uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <i class="fa-solid fa-tv mr-1 text-primary-500"></i>
                            {{ __('Channel') }}
                        </th>
                        <th class="px-4 py-3 text-left">
                            <i class="fa-solid fa-clock-rotate-left mr-1 text-blue-500"></i>
                            {{ __('Reporting Time') }}
                            <a href="#" wire:click.prevent="toggleOrder">
                                <i class="fa-solid fa-sort ms-1 text-gray-500"></i>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <i class="fa-solid fa-bars-staggered mr-1 text-green-500"></i>
                            {{ __('Stage') }}
                        </th>
                        <th class="px-4 py-3 text-left">
                            <i class="fa-solid fa-server mr-1 text-orange-500"></i>
                            {{ __('Protocol') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                        @foreach ($report->reportDetails ?? [] as $detail)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600 text-black dark:text-white cursor-pointer">
                                <td class="px-4 py-3 flex items-center space-x-3">
                                    <img src="{{ $detail->channel->image }}" alt="{{ $detail->channel->name }}"
                                        class="w-10 h-10 object-contain object-center">
                                    <div>
                                        <div class="font-bold text-gray-800 dark:text-white">
                                            <span
                                                class="uppercase inline-flex items-center px-2 py-1 text-xs bg-primary-200 dark:bg-primary-700 rounded-full">
                                                <i class="fa-solid fa-circle-info mr-1"></i>
                                                {{ $detail->channel->number }} {{ $detail->channel->name }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="uppercase inline-flex items-center px-2 py-1 text-xs bg-blue-200 dark:bg-blue-700 rounded-full">
                                        <i class="fa-solid fa-clock mr-1"></i>
                                        {{ $report->formatted_date }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="uppercase inline-flex items-center px-2 py-1 text-xs bg-green-200 dark:bg-green-700 rounded-full">
                                        <i class="fas fa-satellite-dish mr-1"></i>
                                        {{ $detail->stage->name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="uppercase inline-flex items-center px-2 py-1 text-xs bg-orange-200 dark:bg-orange-700 rounded-full">
                                        @if (str_contains($detail->protocol, 'DASH'))
                                            <i class="fas fa-computer-mouse mr-1"></i>
                                        @endif
                                        @if (str_contains($detail->protocol, 'HLS'))
                                            <i class="fas fa-display mr-1"></i>
                                        @endif
                                        {{ $detail->protocol }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4" class="bg-white dark:bg-gray-800 px-4 py-8 text-center">
                                <i class="fa-solid fa-info-circle mr-2 text-gray-500"></i>
                                {{ __('No reports available.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $reports->links() }}
        </div>
    </div>
</div>
