@php
    use App\Helpers\ChannelIssueOptions;
    $issueOptions = ChannelIssueOptions::all();
    function issueCell($value, $options) {
        if (!$value) return '<span class="block w-full px-1 py-0.5 text-xs text-gray-400">-</span>';
        $val = strtolower($value);
        if (isset($options[$val])) {
            $color = $options[$val]['color'] ?? 'gray';
            $label = $options[$val]['label'] ?? $value;
            return '<span class="block w-full px-1 py-0.5 rounded text-xs font-semibold bg-'.$color.'-100 text-'.$color.'-800">'.e($label).'</span>';
        }
        return '<span class="block w-full px-1 py-0.5 rounded text-xs font-semibold bg-gray-200 text-gray-600">'.e($value).'</span>';
    }
@endphp
<div class="mt-4">
    <h3 class="text-xs sm:text-sm font-bold text-gray-800 dark:text-white mb-1 flex items-center gap-2">
        <i class="fa-solid fa-folder-open"></i>
        <span class="truncate">{{ $report->title }}</span>
    </h3>
    <div class="w-full overflow-x-auto mt-4 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
    <table class="min-w-full text-[10px] sm:text-xs text-center border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-lg">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-800">
                    <th class="px-1 sm:px-2 py-1 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-white whitespace-nowrap sm:w-[180px]">{{ __('Channel') }}</th>
                    <th class="px-1 sm:px-2 py-1 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-white whitespace-nowrap">{{ __('High') }}<span class="hidden sm:inline text-[10px] text-green-700 dark:text-green-200"> (10 Mbps)</span></th>
                    <th class="px-1 sm:px-2 py-1 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-white whitespace-nowrap">{{ __('Medium') }}<span class="hidden sm:inline text-[10px] text-yellow-700 dark:text-yellow-200"> (2.5 - 3.5 Mbps)</span></th>
                    <th class="px-1 sm:px-2 py-1 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-white whitespace-nowrap">{{ __('Low') }}<span class="hidden sm:inline text-[10px] text-orange-700 dark:text-orange-200"> (1.5 - 2.5 Mbps)</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($report->channelTests->sortBy(fn($d) => $d->channel->number) as $test)
                    @php
                        $profiles = is_string($test->channel->profiles)
                            ? json_decode($test->channel->profiles, true)
                            : ($test->channel->profiles ?? []);
                    @endphp
                    <tr class="bg-white dark:bg-gray-900 hover:bg-green-50 dark:hover:bg-gray-800 transition">
                        <td class="px-1 sm:px-2 py-1 text-left align-middle border border-gray-300 dark:border-gray-700 whitespace-nowrap">
                            <div class="flex items-center justify-between min-w-0 gap-1 sm:gap-2 whitespace-nowrap">
                                <div class="flex items-center gap-1 min-w-0 whitespace-nowrap">
                                    <img src="{{ $test->channel->image }}" alt="{{ $test->channel->name }}" class="w-5 h-5 object-contain rounded flex-shrink-0">
                                    <span class="font-semibold text-[10px] text-primary-700 dark:text-primary-200 whitespace-nowrap">{{ $test->channel->number }}</span>
                                    <span class="font-medium text-gray-800 dark:text-white whitespace-nowrap">{{ $test->channel->name }}</span>
                                </div>
                                <span class="ml-1 sm:ml-2 text-[10px] text-gray-500 dark:text-gray-300 text-right whitespace-nowrap">{{ implode(', ', $profiles) }}</span>
                            </div>
                        </td>
                        <td class="px-1 sm:px-2 py-1 align-middle border border-gray-300 dark:border-gray-700 whitespace-nowrap">
                            {!! issueCell($test->high, $issueOptions) !!}
                        </td>
                        <td class="px-1 sm:px-2 py-1 align-middle border border-gray-300 dark:border-gray-700 whitespace-nowrap">
                            {!! issueCell($test->medium, $issueOptions) !!}
                        </td>
                        <td class="px-1 sm:px-2 py-1 align-middle border border-gray-300 dark:border-gray-700 whitespace-nowrap">
                            {!! issueCell($test->low, $issueOptions) !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
