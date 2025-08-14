<div class="flex space-x-4">
    @if ($media === 'VIDEO' || $media === 'AUDIO/VIDEO')
        <div class="tooltip" title="{{ __('The channel does not have video') }}">
            <i class="fa-solid fa-video-slash text-red-500 text-xl"></i>
        </div>
    @else
        <div class="tooltip" title="{{ __('The channel has video') }}">
            <i class="fa-solid fa-video text-green-500 text-xl"></i>
        </div>
    @endif
    @if ($media === 'AUDIO' || $media === 'AUDIO/VIDEO')
        <div class="tooltip" title="{{ __('The channel does not have audio') }}">
            <i class="fa-solid fa-volume-xmark text-red-500 text-xl"></i>
        </div>
    @else
        <div class="tooltip" title="{{ __('The channel has audio') }}">
            <i class="fa-solid fa-volume-up text-green-500 text-xl"></i>
        </div>
    @endif
    @if ($protocol === 'DASH' || $protocol === 'DASH/HLS')
        <div class="tooltip" title="{{ __('Not working on Web Client (DASH)') }}">
            <i class="fa-solid fa-computer text-red-500 text-xl"></i>
        </div>
    @else
        <div class="tooltip" title="{{ __('Working on Web Client (DASH)') }}">
            <i class="fa-solid fa-computer text-green-500 text-xl"></i>
        </div>
    @endif
    @if ($protocol === 'HLS' || $protocol === 'DASH/HLS')
        <div class="tooltip" title="{{ __('Not working on Set Up Box (HLS)') }}">
            <i class="fa-solid fa-tv text-red-500 text-xl"></i>
        </div>
    @else
        <div class="tooltip" title="{{ __('Working on Set Up Box (HLS)') }}">
            <i class="fa-solid fa-tv text-green-500 text-xl"></i>
        </div>
    @endif
</div>
