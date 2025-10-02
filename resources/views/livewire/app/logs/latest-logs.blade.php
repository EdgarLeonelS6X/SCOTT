<div wire:poll.3s>
    @if($error)
        <div class="text-center text-red-500">{{ $error }}</div>
    @else
        <div class="max-h-96 overflow-y-auto text-xs bg-gray-100 dark:bg-gray-900 rounded p-2 border border-gray-200 dark:border-gray-700">
            @forelse($logs as $log)
                <div class="py-1 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <span class="font-mono text-gray-700 dark:text-gray-200">{{ $log->message ?? '' }}</span>
                    <span class="ml-2 text-gray-400">{{ $log->created_at ?? '' }}</span>
                </div>
            @empty
                <div class="text-center text-gray-400">{{ __('No recent logs.') }}</div>
            @endforelse
        </div>
    @endif
</div>
