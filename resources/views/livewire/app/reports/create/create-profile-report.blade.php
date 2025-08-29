<?php

use App\Enums\ChannelIssues;

?>

<div>
    <form wire:submit.prevent="saveReport" class="space-y-5">
        <div
            x-data="{ open: true, editingTitle: false, firstEdit: true, reportTitle: @entangle('reportData.title').defer || '' }">
            <div class="p-4 md:p-6 border bg-white dark:bg-gray-800 rounded-2xl shadow-2xl">
                <div class="flex flex-wrap items-center justify-between cursor-pointer"
                    @click="if (!editingTitle) open = !open">
                    <div class="flex items-center gap-3 min-w-0">
                        <button type="button" class="text-primary-600 flex-shrink-0" @click.stop="open = !open">
                            <i :class="open ? 'fas fa-chevron-down' : 'fas fa-chevron-right'"></i>
                        </button>
                        <div class="dark:text-white text-lg font-semibold relative min-w-0">
                            <h3 title="Click to edit the title" x-show="!editingTitle"
                                @click.stop="editingTitle = true; if (firstEdit) { reportTitle = ''; firstEdit = false; }"
                                class="cursor-pointer px-3 py-2 rounded-full shadow-md flex items-center gap-2 transition bg-gray-50 border dark:bg-gray-700 dark:border-white truncate max-w-[280px] md:max-w-md">
                                <i class="fa-solid fa-pen text-gray-800 dark:text-gray-200">...</i>
                                <span class="truncate" x-text="reportTitle"></span>
                            </h3>
                            <input x-show="editingTitle" x-model="reportTitle" @click.stop
                                @click.away="editingTitle = false; if (reportTitle.trim() === '') { reportTitle = ''; } $wire.set('reportData.title', reportTitle);"
                                @keydown.enter.prevent="editingTitle = false; if (reportTitle.trim() === '') { reportTitle = ''; } $wire.set('reportData.title', reportTitle);"
                                placeholder="Title" autofocus
                                class="max-w-[240px] md:max-w-md truncate border rounded p-2" />
                        </div>
                    </div>
                    <div class="flex items-center gap-3 transition-all duration-300"
                        :class="(reportTitle.length > 8 || editingTitle) ? 'mt-4 sm:mt-0' : 'mt-0'">
                        <span class="bg-primary-100 text-primary-800 text-sm font-medium py-1 px-3 rounded-full">
                            {{ __('Contains') }} {{ count($reportData['channels']) }}
                            {{ count($reportData['channels']) === 1 ? __('Channel') : __('Channels') }}
                        </span>
                    </div>
                </div>
                <div x-show="open" class="mt-6 space-y-6">
                    @foreach ($reportData['channels'] as $index => $channel)
                        <div class="p-4 md:p-6 bg-gray-50 border dark:bg-gray-700 rounded-xl shadow-2xl">
                            <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
                                <h4 class="text-md font-semibold text-gray-800 dark:text-white">
                                    {{ __('Channel') }} {{ $index + 1 }}
                                </h4>
                                <button type="button" wire:click="removeChannel({{ $index }})"
                                    class="text-red-500 hover:text-red-700 dark:hover:text-red-400">
                                    <i class="fas fa-times-circle mr-1"></i> {{ __('Remove channel') }}
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div x-data="{
                                                                            open: false,
                                                                            search: '',
                                                                            selectedChannelImage: '',
                                                                            selectedChannelNumber: '',
                                                                            selectedChannelName: '',
                                                                            selectedChannelProfiles: null,
                                                                            channels: {{ $channels->toJson() }},
                                                                            clearSelection() {
                                                                                this.selectedChannelImage = '';
                                                                                this.selectedChannelNumber = '';
                                                                                this.selectedChannelName = '';
                                                                                this.selectedChannelProfiles = null;
                                                                                this.search = '';
                                                                                this.open = true;
                                                                            },
                                                                            get filteredChannels() {
                                                                                if (this.search === '') return this.channels;
                                                                                const term = this.search.toLowerCase();
                                                                                return this.channels.filter(c =>
                                                                                    (c.name.toLowerCase().includes(term)) ||
                                                                                    (c.number.toString().includes(term)) ||
                                                                                    ((c.number + ' ' + c.name).toLowerCase().includes(term))
                                                                                );
                                                                            },
                                                                        }" x-init="
                                                                            const selectedId = @js(data_get($reportData['channels'][$index] ?? [], 'channel_id'));
                                                                            if (selectedId) {
                                                                                const selected = channels.find(c => c.id === selectedId);
                                                                                if (selected) {
                                                                                    selectedChannelImage = selected.image;
                                                                                    selectedChannelNumber = selected.number;
                                                                                    selectedChannelName = selected.name;
                                                                                    selectedChannelProfiles = selected.profiles;
                                                                                    search = selected.number + ' ' + selected.name;
                                                                                }
                                                                            }
                                                                        ">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fa-solid fa-tv mr-1.5"></i> {{ __('Channel') }}
                                    </label>

                                    <div class="relative mb-2">
                                        <input type="text" x-model="search" :placeholder="selectedChannelNumber
                                                                                    ? selectedChannelNumber + ' ' + selectedChannelName
                                                                                    : '{{ __('Search channel...') }}'"
                                            @focus="open = true" @input="if (search === '') clearSelection()"
                                            @click.away="open = false"
                                            class="w-full p-2.5 pl-14 rounded-lg bg-gray-50 border border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-primary-600 focus:border-primary-600">
                                        <div class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center">
                                            <img x-show="selectedChannelImage" :src="selectedChannelImage"
                                                class="w-8 h-8 object-contain object-center" x-cloak>
                                        </div>
                                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300 cursor-pointer"
                                            :class="open ? 'rotate-180' : ''" @click="open = !open"></i>
                                    </div>

                                    <template x-if="selectedChannelProfiles">
                                        <div class="flex gap-2 text-xs mb-3 ml-1">
                                            <span class="px-2 py-0.5 rounded-lg bg-green-300 text-green-700">
                                                {{ __('High:') }} <span x-text="selectedChannelProfiles.high ?? '-'"></span>
                                            </span>
                                            <span class="px-2 py-0.5 rounded-lg bg-yellow-300 text-yellow-700">
                                                {{ __('Medium:') }} <span x-text="selectedChannelProfiles.medium ?? '-'"></span>
                                            </span>
                                            <span class="px-2 py-0.5 rounded-lg bg-red-300 text-red-700">
                                                {{ __('Low:') }} <span x-text="selectedChannelProfiles.low ?? '-'"></span>
                                            </span>
                                        </div>
                                    </template>

                                    <div class="relative">
                                        <div x-show="open" x-cloak
                                            class="absolute z-10 w-full mt-1 bg-white border rounded-lg shadow-2xl dark:bg-gray-700 transition-all">
                                            <ul
                                                class="max-h-60 overflow-y-auto scrollbar-thin dark:scrollbar-thumb-gray-600">
                                                <template x-for="channel in filteredChannels" :key="channel.id">
                                                    <li @click="
                                                                                                $wire.set('reportData.channels.{{ $index }}.channel_id', channel.id);
                                                                                                selectedChannelImage = channel.image;
                                                                                                selectedChannelNumber = channel.number;
                                                                                                selectedChannelName = channel.name;
                                                                                                selectedChannelProfiles = channel.profiles;
                                                                                                search = selectedChannelNumber + ' ' + selectedChannelName;
                                                                                                open = false;
                                                                                            "
                                                        class="flex flex-col px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                                                        <div class="flex items-center">
                                                            <img :src="channel.image" class="w-8 h-8 object-contain">
                                                            <span class="ml-3 text-sm text-gray-900 dark:text-gray-300"
                                                                x-text="channel.number + ' ' + channel.name"></span>
                                                        </div>
                                                        <div class="flex gap-2 mt-1 text-xs">
                                                            <span
                                                                class="px-2 py-0.5 rounded-lg bg-green-300 text-green-700 dark:bg-green-800 dark:text-green-200">
                                                                {{ __('High:') }} <span x-text="channel.profiles?.high ?? '-'"></span>
                                                            </span>
                                                            <span
                                                                class="px-2 py-0.5 rounded-lg bg-yellow-300 text-yellow-700 dark:bg-yellow-800 dark:text-yellow-200">
                                                                {{ __('Medium:') }} <span
                                                                    x-text="channel.profiles?.medium ?? '-'"></span>
                                                            </span>
                                                            <span
                                                                class="px-2 py-0.5 rounded-lg bg-red-300 text-red-700 dark:bg-red-800 dark:text-red-200">
                                                                {{ __('Low:') }} <span x-text="channel.profiles?.low ?? '-'"></span>
                                                            </span>
                                                        </div>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>

                                    @error('reportData.channels.' . $index . '.channel_id')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <i class="fa-solid fa-arrow-up mr-1.5"></i>
                                        {{ __('High') }} (10 Mbps)
                                    </label>
                                    <select wire:model="reportData.channels.{{ $index }}.high"
                                        class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="" disabled selected>{{ __('Select an option') }}</option>
                                        @foreach (ChannelIssues::cases() as $reviewer)
                                            <option value="{{ $reviewer->value }}">{{ $reviewer->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <i class="fa-solid fa-arrows-up-down mr-1.5"></i>
                                        {{ __('Medium') }} (2.5 - 3.5 Mbps)
                                    </label>
                                    <select wire:model="reportData.channels.{{ $index }}.medium"
                                        class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="" disabled selected>
                                            {{ __('Select an option') }}
                                        </option>
                                        @foreach (ChannelIssues::cases() as $reviewer)
                                            <option value="{{ $reviewer->value }}">{{ $reviewer->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <i class="fa-solid fa-arrow-down mr-1.5"></i>
                                        {{ __('Low') }} (1.5 - 2.5 Mbps)
                                    </label>
                                    <select wire:model="reportData.channels.{{ $index }}.low"
                                        class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="" disabled selected>
                                            {{ __('Select an option') }}
                                        </option>
                                        @foreach (ChannelIssues::cases() as $reviewer)
                                            <option value="{{ $reviewer->value }}">{{ $reviewer->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <button type="button" wire:click="addChannel"
                        class="flex items-center gap-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-500 transition">
                        <i class="fas fa-plus-circle"></i> {{ __('Add channel') }}
                    </button>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-end gap-4 mt-6">
                <button type="submit"
                    class="w-full md:w-auto py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-bold text-base">
                    <i class="fas fa-file-lines mr-1.5"></i> {{ __('Generate report') }}
                </button>
                <button data-modal-hide="create-profile-report-modal" type="button"
                    class="py-2 px-4 text-base font-bold text-gray-700 bg-white rounded-lg border border-gray-400 hover:border-primary-600 hover:text-primary-600 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:hover:text-primary-400 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-xmark"></i>
                    {{ __('Discard') }}
                </button>
            </div>
        </div>
    </form>
    @if(session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-2 mt-4">
            {{ session('message') }}
        </div>
    @endif
</div>
