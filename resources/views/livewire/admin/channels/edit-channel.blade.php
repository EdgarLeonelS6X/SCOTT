<?php

use App\Enums\ChannelCategory;
use App\Enums\ChannelOrigin;

?>

<x-slot name="action">
    <a href="{{ route('admin.channels.index') }}"
        class="hidden md:block sm:flex justify-center items-center text-white bg-gray-600 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm px-5 py-2 text-center">
        <i class="fa-solid fa-arrow-left mr-1.5"></i>
        {{ __('Go back') }}
    </a>
    <div id="overlay"
        class="fixed inset-0 bg-black bg-opacity-50 text-white text-xl flex items-center justify-center z-50 hidden transition-opacity duration-300 ease-in-out">
        <div class="text-center">
            <i class="fa-solid fa-upload text-4xl mb-4 animate-bounce"></i>
            <p>
                {{ __('Drop your image here to upload it...') }}
            </p>
        </div>
    </div>
</x-slot>
<div class="w-full bg-white rounded-lg shadow-2xl dark:border md:mt-0 xl:p-0 dark:bg-gray-800 dark:border-gray-700">
    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            <i class="fa-solid fa-tv mr-1.5"></i>
            {{ __('Update channel') }}

            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                {{ __('Update the data for this channel.') }}
            </p>
        </h1>
        <form wire:submit.prevent="update" class="space-y-6">
            @csrf
            <div class="mt-6 md:mt-0">
                <x-label for="image-input" class="block mb-3 font-semibold">
                    <i class="fa-solid fa-image mr-1"></i>
                    {{ __('Channel image') }}
                </x-label>
                <figure
                    class="bg-gray-100 dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                    <input type="file" id="image-input" class="hidden" wire:model="new_image" accept="image/*">
                    <div id="drop-area"
                        class="flex flex-col justify-center items-center p-10 border border-dashed border-gray-300 rounded-lg">
                        @if ($image_url)
                            <img class="object-contain object-center w-60 h-60 rounded-lg"
                                src="{{ $new_image ? $new_image->temporaryUrl() : ($image_url ? asset('storage/' . $image_url) : asset('img/no-image.png')) }}"
                                alt="{{ __('Image preview') }}">
                        @else
                            <p class="text-sm text-gray-400 dark:text-gray-300 text-center">
                                <i class="fa-solid fa-cloud-arrow-up text-xl mb-2"></i><br>
                                {{ __('Drag and drop an image here or') }}
                                <span class="text-primary-600 cursor-pointer underline"
                                    onclick="document.getElementById('image-input').click()">
                                    {{ __('select one') }}
                                </span>
                            </p>
                        @endif
                    </div>
                </figure>
            </div>
            <div class="mt-6">
                <x-label for="name">
                    <i class="fa-solid fa-tv mr-1"></i>
                    {{ __('Name') }}
                </x-label>
                <x-input id="name" class="block mt-1 w-full" type="text" wire:model="name" :value="old('name')" required
                    autocomplete="name" placeholder="{{ __('Channel name') }}" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <x-label for="number">
                        <i class="fa-solid fa-hashtag mr-1"></i>
                        {{ __('Number') }}
                    </x-label>
                    <x-input id="number" class="block mt-1 w-full" type="number" wire:model="number"
                        :value="old('number')" required autocomplete="number"
                        placeholder="{{ __('Channel number') }}" />
                </div>
                <div>
                    <x-label for="url">
                        <i class="fa-solid fa-link mr-1"></i>
                        {{ __('URL') }}
                    </x-label>
                    <x-input id="url" class="block mt-1 w-full" type="text" wire:model="url" :value="old('url')"
                        required autocomplete="url" placeholder="{{ __('Channel URL') }}" />
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <x-label for="category">
                        <i class="fa-solid fa-list mr-1"></i>
                        {{ __('Category') }}
                    </x-label>
                    <select id="category"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        wire:model="category" required>
                        <option value="" selected disabled>{{ __('Select category') }}</option>
                        @foreach (ChannelCategory::cases() as $category)
                            <option value="{{ $category->value }}">{{ $category->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-label for="origin">
                        <i class="fa-solid fa-arrow-right-arrow-left mr-1"></i>
                        {{ __('Origin') }}
                    </x-label>
                    <select id="origin"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        wire:model="origin" required>
                        <option value="" selected disabled>{{ __('Select origin') }}</option>
                        @foreach (ChannelOrigin::cases() as $origin)
                            <option value="{{ $origin->value }}">{{ $origin->value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-6">
                <x-label>
                    <i class="fa-solid fa-sliders mr-1"></i>
                    {{ __('Profiles (Mbps)') }}
                </x-label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-2">
                    <div>
                        <x-label for="profile_high" class="text-sm">{{ __('High') }}</x-label>
                        <x-input id="profile_high" type="number" step="0.1" min="0" wire:model="profiles.high"
                            placeholder="{{ __('ex. 6') }}" />
                    </div>
                    <div>
                        <x-label for="profile_medium" class="text-sm">{{ __('Medium') }}</x-label>
                        <x-input id="profile_medium" type="number" step="0.1" min="0" wire:model="profiles.medium"
                            placeholder="{{ __('ex. 3') }}" />
                    </div>
                    <div>
                        <x-label for="profile_low" class="text-sm">{{ __('Low') }}</x-label>
                        <x-input id="profile_low" type="number" step="0.1" min="0" wire:model="profiles.low"
                            placeholder="{{ __('ex. 1.5') }}" />
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('Leave blank if this channel does not have that profile.') }}
                </p>
            </div>
            <div class="mt-6">
                <x-label for="status">
                    <i class="fa-solid fa-toggle-on mr-1"></i>
                    {{ __('Status') }}
                </x-label>
                <select id="status"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    wire:model="status" required>
                    <option value="" selected disabled>{{ __('Select status') }}</option>
                    <option value="1">{{ __('Active') }}</option>
                    <option value="0">{{ __('Inactive') }}</option>
                </select>
            </div>
            <div class="flex justify-end">
                <x-button class="flex justify-center items-center font-bold shadow">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    {{ __('Update channel') }}
                </x-button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('image-input');
        const overlay = document.getElementById('overlay');
        let dragCounter = 0;

        const showOverlay = () => {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
        };

        const hideOverlay = () => {
            overlay.classList.remove('flex');
            overlay.classList.add('hidden');
        };

        window.addEventListener('dragenter', (e) => {
            e.preventDefault();
            dragCounter++;
            showOverlay();
        });

        window.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dragCounter--;
            if (dragCounter === 0) {
                hideOverlay();
            }
        });

        window.addEventListener('dragover', (e) => {
            e.preventDefault();
        });

        window.addEventListener('drop', (e) => {
            e.preventDefault();
            dragCounter = 0;
            hideOverlay();

            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });

        fileInput.addEventListener('change', () => {
            dragCounter = 0;
            hideOverlay();
        });

        dropArea.addEventListener('click', () => fileInput.click());
    });
</script>
