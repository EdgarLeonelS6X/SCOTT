<x-admin-layout :breadcrumbs="[
    [
        'name' => __('Dashboard'),
        'icon' => 'fa-solid fa-house',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => __('Channels'),
        'icon' => 'fa-solid fa-tv',
        'route' => route('admin.channels.index'),
    ],
    [
        'name' => __('Channel'),
        'icon' => 'fa-solid fa-circle-info',
    ],
]">

    <x-slot name="action">
        <div class="hidden lg:flex space-x-2">
            <a href="{{ route('admin.channels.index') }}"
                class="flex w-full sm:w-auto justify-center items-center text-white bg-gray-600 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm px-4 py-2 text-center">
                <i class="fa-solid fa-arrow-left mr-1.5"></i>
                {{ __('Go back') }}
            </a>
            <a href="#" title="{{ __('Play channel') }}"
                onclick="event.preventDefault(); openMiniPlayer('{{ $channel->url }}');"
                class="flex w-full sm:w-auto justify-center items-center text-white bg-primary-600 hover:bg-primary-500 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:focus:ring-primary-800 font-medium rounded-lg text-sm px-4 py-2 text-center">
                <i class="fa-solid fa-play mr-1.5"></i>
                {{ __('Play') }}
            </a>
            @can('channels.edit')
                <a href="{{ route('admin.channels.edit', $channel) }}"
                    class="flex w-full sm:w-auto justify-center items-center text-white bg-blue-600 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-4 py-2 text-center">
                    <i class="fa-solid fa-pen-to-square mr-1.5"></i>
                    {{ __('Edit') }}
                </a>
            @endcan
            @can('channels.delete')
                <button onclick="confirmDelete()"
                    class="flex w-full sm:w-auto justify-center items-center text-white bg-red-600 hover:bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-4 py-2 text-center">
                    <i class="fa-solid fa-trash-can mr-1.5"></i>
                    {{ __('Delete') }}
                </button>
            @endcan
        </div>
    </x-slot>
    <div class="w-full bg-white rounded-lg shadow-2xl dark:border dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-6 sm:p-8">
            <div
                class="flex flex-col md:flex-row items-center md:items-center justify-between text-center md:text-left gap-4">
                <div class="order-1 md:order-none flex flex-col items-center md:items-start">
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">
                        <span class="text-primary-600">{{ $channel->number }}</span> {{ $channel->name }}
                    </h1>
                    <div class="mt-2 flex flex-wrap justify-center md:justify-start items-center gap-2">
                        <span
                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-primary-800 bg-primary-200 rounded-full dark:bg-primary-800 dark:text-primary-200">
                            @switch($channel->category)
                                @case('Standard TV Channel')
                                    <i class="fa-solid fa-tv mr-1.5"></i>
                                @break

                                @case('Stingray Music')
                                    <i class="fa-solid fa-music mr-1.5"></i>
                                @break

                                @case('RESTART/CUTV')
                                    <i class="fa-solid fa-repeat mr-1.5"></i>
                                @break

                                @default
                                    <i class="fa-solid fa-layer-group mr-1.5"></i>
                            @endswitch
                            {{ $channel->category }}
                        </span>
                        @if ($channel->status === 1)
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-200 rounded-full dark:bg-green-800 dark:text-green-200">
                                <i class="fa-solid fa-check-circle mr-1.5"></i>
                                {{ __('Active') }}
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-800 bg-red-200 rounded-full dark:bg-red-800 dark:text-red-200">
                                <i class="fa-solid fa-times-circle mr-1.5"></i>
                                {{ __('Inactive') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="order-0 md:order-none">
                    <img src="{{ $channel->image_url ? asset('storage/' . $channel->image_url) : asset('img/no-image.png') }}"
                        alt="{{ $channel->name }}"
                        class="w-20 h-20 object-center object-contain rounded-md mx-auto md:mx-0">
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <x-label for="name">
                        <i class="fa-solid fa-tv mr-1"></i>
                        {{ __('Name') }}
                    </x-label>
                    <x-input id="name" class="block mt-1 w-full" type="text" wire:model="name"
                        value="{{ $channel->name }}" disabled />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="number">
                            <i class="fa-solid fa-hashtag mr-1"></i>
                            {{ __('Number') }}
                        </x-label>
                        <x-input id="number" class="block mt-1 w-full" type="number" wire:model="number"
                            value="{{ $channel->number }}" disabled />
                    </div>
                    <div>
                        <x-label for="origin">
                            <i class="fa-solid fa-arrow-right-arrow-left mr-1"></i>
                            {{ __('Origin') }}
                        </x-label>
                        <x-input id="origin" class="block mt-1 w-full" type="text" wire:model="origin"
                            value="{{ $channel->origin }}" disabled />
                    </div>
                </div>
                <div class="mt-4">
                    <x-label>
                        <i class="fa-solid fa-sliders mr-1"></i>
                        {{ __('Profiles (Mbps)') }}
                    </x-label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-2">
                        <div>
                            <x-label class="text-sm">{{ __('High') }}</x-label>
                            <x-input type="number" step="0.1" min="0" value="{{ $channel->profiles['high'] ?? '' }}" disabled />
                        </div>
                        <div>
                            <x-label class="text-sm">{{ __('Medium') }}</x-label>
                            <x-input type="number" step="0.1" min="0" value="{{ $channel->profiles['medium'] ?? '' }}" disabled />
                        </div>
                        <div>
                            <x-label class="text-sm">{{ __('Low') }}</x-label>
                            <x-input type="number" step="0.1" min="0" value="{{ $channel->profiles['low'] ?? '' }}" disabled />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="lg:hidden mt-6 space-y-5">
        <a href="{{ route('admin.channels.index') }}"
            class="flex justify-center items-center w-full text-white bg-gray-600 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm px-4 py-2">
            <i class="fa-solid fa-arrow-left mr-1.5"></i>
            {{ __('Go back') }}
        </a>
        <a href="#" title="{{ __('Play channel') }}"
            onclick="event.preventDefault(); openMiniPlayer('{{ $channel->url }}');"
            class="flex justify-center items-center w-full text-white bg-primary-600 hover:bg-primary-500 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:focus:ring-primary-800 font-medium rounded-lg text-sm px-4 py-2">
            <i class="fa-solid fa-play mr-1.5"></i>
            {{ __('Play') }}
        </a>
        @can('channels.edit')
            <a href="{{ route('admin.channels.edit', $channel) }}"
                class="flex justify-center items-center w-full text-white bg-blue-600 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-4 py-2">
                <i class="fa-solid fa-pen-to-square mr-1.5"></i>
                {{ __('Edit') }}
            </a>
        @endcan
        @can('channels.delete')
            <button onclick="confirmDelete()"
                class="flex justify-center items-center w-full text-white bg-red-600 hover:bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-4 py-2">
                <i class="fa-solid fa-trash-can mr-1.5"></i>
                {{ __('Delete') }}
            </button>
        @endcan
    </div>
    <form action="{{ route('admin.channels.destroy', $channel) }}" method="POST" id="delete-form">
        @csrf
        @method('DELETE')
    </form>
    @push('js')
        <script>
            function confirmDelete() {
                Swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('You wont be able to revert this!') }}",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "{{ __('Yes, delete it!') }}",
                    cancelButtonText: "{{ __('Cancel') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form').submit();
                    }
                });
            }
        </script>
    @endpush
</x-admin-layout>

<script>
    function openMiniPlayer(url) {
        const youtubeRegex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]{11})/;
        const match = url.match(youtubeRegex);

        if (match) {
            const videoId = match[1];
            url = `https://www.youtube.com/embed/${videoId}`;
        }

        let playerContainer = document.getElementById('miniPlayerContainer');
        if (!playerContainer) {
            playerContainer = document.createElement('div');
            playerContainer.id = 'miniPlayerContainer';
            playerContainer.classList =
                'fixed bottom-4 right-4 w-80 bg-white shadow-lg rounded-lg overflow-hidden z-50';
            document.body.appendChild(playerContainer);

            const controlBar = document.createElement('div');
            controlBar.classList =
                'w-full flex justify-between items-center bg-primary-600 dark:bg-primary-700 text-white p-2 shadow-2xl';
            controlBar.style.height = '40px';
            controlBar.innerHTML = `
                <span>{{ __('Playing channel') }}</span>
                <button onclick="closeMiniPlayer()" class="text-gray-300 hover:text-white">
                    <i class="fa-solid fa-times"></i>
                </button>
            `;
            playerContainer.appendChild(controlBar);

            const iframe = document.createElement('iframe');
            iframe.classList = 'w-full';
            iframe.style.height =
                'calc(100% - 40px)';
            iframe.frameBorder = 0;
            iframe.allowFullscreen = true;
            playerContainer.appendChild(iframe);
        }

        playerContainer.querySelector('iframe').src = url;
        playerContainer.style.display = 'block';
    }

    function closeMiniPlayer() {
        const playerContainer = document.getElementById('miniPlayerContainer');
        if (playerContainer) {
            playerContainer.style.display = 'none';
            playerContainer.querySelector('iframe').src = '';
        }
    }
</script>
