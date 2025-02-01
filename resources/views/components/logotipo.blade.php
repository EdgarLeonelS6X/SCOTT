<a href="{{ route('login') }}" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
    <img class="w-12 h-12 mr-2 rounded-md" src="https://play-lh.googleusercontent.com/f6dYwg3khIfRRVnsnpOLkfPvuINkGaSvDdxJhEtcRc5TQv8rrKLrkPipAnaVLxEM1Cc=w240-h480-rw"
        alt="Logotipo">
    <div class="flex flex-col text-left ms-2">
        {{ config('app.name', 'Laravel') }}
        <span class="text-sm font-normal text-gray-600 dark:text-gray-400">
            {{ __('OTT Communications System') }}
        </span>
    </div>
</a>