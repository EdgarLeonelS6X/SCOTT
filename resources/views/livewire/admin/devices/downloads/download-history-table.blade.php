<div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">{{ __('Downloads history') }}</h2>
        <div class="flex items-center gap-2">
            <input id="history-search" type="search" placeholder="{{ __('Search...') }}"
                class="border rounded px-2 py-1 text-sm" />
            <a href="#" id="history-export" class="text-sm text-gray-600 hover:underline">{{ __('Export CSV')
                        }}</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table
            class="min-w-full text-sm text-left text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-700">
            <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2">{{ __('Device') }}</th>
                    <th class="px-4 py-2">{{ __('Year') }}</th>
                    <th class="px-4 py-2">{{ __('Month') }}</th>
                    <th class="px-4 py-2">{{ __('Count') }}</th>
                    <th class="px-4 py-2">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody id="history-body" class="bg-white dark:bg-gray-800">
                <tr class="border-t hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 text-gray-500" colspan="5">{{ __('No data. Use the controls above to
                                load or create monthly download reports.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
