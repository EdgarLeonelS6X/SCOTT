<?php

use App\Enums\ChannelCategory;
use App\Enums\ChannelOrigin;

?>

<x-slot name="action">
    <a href="{{ route('admin.channels.index') }}"
        class="flex justify-center items-center text-white bg-gray-600 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm px-5 py-2 text-center">
        <i class="fa-solid fa-arrow-left mr-1.5"></i>
        {{ __('Go back') }}
    </a>
</x-slot>
<div class="w-full bg-white rounded-lg shadow-2xl dark:border md:mt-0 xl:p-0 dark:bg-gray-800 dark:border-gray-700">
    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            <i class="fa-solid fa-tv mr-1.5"></i>
            {{ __('Register new channel') }}
            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                {{ __('Enter the data for the new channel.') }}
            </p>
        </h1>
        <form wire:submit.prevent="store">
            @csrf
            <div class="mt-6 md:mt-0">
                <x-label for="image-input" class="block mb-3 font-semibold">
                    <i class="fa-solid fa-image mr-1"></i>
                    {{ __('Channel image') }}
                </x-label>
                <figure
                    class="bg-gray-100 dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                    <input type="file" id="image-input" class="hidden" wire:model="image_url" accept="image/*">
                    <div id="drop-area"
                        class="flex flex-col justify-center items-center p-10 border border-dashed border-gray-300 rounded-lg">
                        @if ($image_url)
                            <img class="object-contain object-center w-60 h-60 rounded-lg"
                                src="{{ $image_url->temporaryUrl() }}" alt="{{ __('Image preview') }}">
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
                <x-input id="name" class="block mt-1 w-full" type="text" wire:model="name" :value="old('name')"
                    required autocomplete="name" placeholder="{{ __('Channel name') }}" />
            </div>
            <div class="grid grid-cols-2 gap-6 mt-6">
                <div>
                    <x-label for="number">
                        <i class="fa-solid fa-hashtag mr-1"></i>
                        {{ __('Number') }}
                    </x-label>
                    <x-input id="number" class="block mt-1 w-full" type="number" wire:model="number"
                        :value="old('number')" required autocomplete="number" placeholder="{{ __('Channel number') }}" />
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
            <div class="grid grid-cols-2 gap-6 mt-6">
                <div>
                    <x-label for="category">
                        <i class="fa-solid fa-list mr-1"></i>
                        {{ __('Category') }}
                    </x-label>
                    <select id="category"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        wire:model="category" required>
                        <option value="" disabled>{{ __('Select category') }}</option>
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
                <x-label for="status">
                    <i class="fa-solid fa-toggle-on mr-1"></i>
                    {{ __('Status') }}
                </x-label>
                <select id="status"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    wire:model="status" required>
                    <option value="" disabled>{{ __('Select status') }}</option>
                    <option value="1">{{ __('Active') }}</option>
                    <option value="0">{{ __('Inactive') }}</option>
                </select>
            </div>
            <div class="flex justify-end">
                <x-button class="flex justify-center items-center mt-8 font-bold shadow">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    {{ __('Register new channel') }}
                </x-button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('image-input');

        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('border-primary-500');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('border-primary-500');
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('border-primary-500');

            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });

        dropArea.addEventListener('click', () => fileInput.click());
    });
</script>
