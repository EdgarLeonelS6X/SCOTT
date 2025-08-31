<div class="w-full md:w-2/3 pt-6 md:pt-0 md:pl-6 lg:pt-0 lg:pl-6" wire:key="reports-table">
    <div class="bg-white dark:bg-gray-800 relative shadow-2xl rounded-lg overflow-hidden">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
            <div class="w-full md:w-1/2">
                <form class="flex items-center" onsubmit="event.preventDefault();">
                    <label for="simple-search" class="sr-only">Search</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="simple-search" wire:model.live="search"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="{{ __('Search') }}" required autofocus>
                    </div>
                </form>
            </div>
            <div class="w-full md:w-auto flex items-center justify-end space-x-3">
                <a href="{{ route('reports.index') }}"
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    <i class="fa-solid fa-folder mr-1"></i>
                    {{ __('Report history') }}
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            @if ($reports->isEmpty())
                <div class="mt-4 text-center">
                    <span class="text-gray-500 dark:text-gray-300">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        {{ __('There are no reports available at this time.') }}
                    </span>
                </div>
            @else
                <table class="w-full min-w-[640px] text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-600 dark:text-white">
                        <tr>
                            <th class="py-3 px-4 text-left whitespace-nowrap">
                                <i class="fa-solid fa-folder mr-1"></i> {{ __('Report') }}
                            </th>
                            <th class="py-3 px-4 text-left whitespace-nowrap">
                                <i class="fa-solid fa-layer-group mr-1"></i> {{ __('Channels') }}
                            </th>
                            <th class="py-3 px-4 text-left whitespace-nowrap">
                                <i class="fa-solid fa-circle-check mr-1"></i> {{ __('Status') }}
                            </th>
                            <th wire:click="toggleOrder"
                                class="py-3 px-4 text-left flex items-center cursor-pointer whitespace-nowrap">
                                <i class="fa-solid fa-calendar mr-1"></i> {{ __('It was reported ago') }}
                                <i class="fa-solid {{ $order === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }} ml-1"></i>
                            </th>
                            <th class="px-4 py-3 text-center">
                                <span class="sr-only">
                                    <i class="fa-solid fa-sliders-h mr-1"></i> {{ __('Options') }}
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600 text-black dark:text-white cursor-pointer"
                                wire:click="openReportDetails({{ $report->id }})">
                                <td
                                    class="py-3 px-4 font-bold leading-tight truncate whitespace-nowrap overflow-hidden text-ellipsis max-w-[8rem] sm:max-w-xs">
                                    {{ $report->category }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        @foreach ($report->reportDetails->sortBy(fn($detail) => $detail->channel->number)->take(3) as $detail)
                                            <div class="relative w-8 h-8 overflow-hidden">
                                                <img class="w-full h-full object-contain object-center"
                                                    src="{{ $detail->channel->image }}" alt="{{ $detail->channel->name }}"
                                                    title="{{ $detail->channel->number }} {{ $detail->channel->name }}">
                                            </div>
                                        @endforeach
                                        @if ($report->reportDetails->count() > 3)
                                            <span
                                                class="flex items-center justify-center w-8 h-8 text-xs font text-white bg-primary-700 rounded-full shadow-md">
                                                +{{ $report->reportDetails->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-sm font-medium text-yellow-800 bg-yellow-200 dark:bg-yellow-800 dark:text-yellow-200 rounded-full">
                                        <i class="fa-solid fa-magnifying-glass mr-1.5"></i> {{ $report->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-sm font-medium text-blue-800 bg-blue-200 dark:bg-blue-800 dark:text-blue-200 rounded-full">
                                        <i class="fa-solid fa-clock mr-1.5"></i> {{ $report->formatted_date }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    <i class="fa-solid fa-chevron-right text-gray-600 dark:text-gray-300"></i>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="p-4">
            {{ $reports->links() }}
        </div>
    </div>
    @if ($showModal && $selectedReport)
        <div
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-start md:items-center overflow-y-auto">
            @if ($selectedReport)
                <div class="flex flex-col md:flex-row gap-0 md:gap-4 w-full md:w-auto max-h-[90vh] md:max-h-[90vh]">
                    @livewire('app.reports.report-detail-modal', ['reporteId' => $selectedReport->id], key($selectedReport->id))
                    @livewire('app.reports.report-comments-modal', ['reportId' => $selectedReport->id], key('comments-' . $selectedReport->id))
                </div>
            @endif
        </div>
    @endif
</div>

<script>
function downloadM3U(url, number, name) {
        const content = url + "\n";
        let cleanName = (number ? number + '_' : '') + (name ? name : 'canal');
        cleanName = cleanName.replace(/[^a-zA-Z0-9-_]/g, '_');
        const filename = cleanName + '.m3u';
        const blob = new Blob([content], { type: "audio/x-mpegurl" });
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
</script>
