<x-admin-layout :breadcrumbs="[
        [
            'name' => __('Dashboard'),
            'icon' => 'fa-solid fa-wrench',
            'route' => route('admin.dashboard'),
        ],
        [
            'name' => __('Grafana'),
            'icon' => 'fa-solid fa-chart-pie',
            'route' => route('admin.grafana.index'),
        ],
        [
            'name' => __('New'),
            'icon' => 'fa-solid fa-plus',
        ],
    ]">

    <x-slot name="action">
        <a href="{{ route('admin.grafana.index') }}"
            class="hidden sm:flex justify-center items-center text-white bg-gray-600 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm px-5 py-2 text-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i>
            {{ __('Go back') }}
        </a>
    </x-slot>
    <div class="w-full bg-white rounded-lg shadow-2xl dark:border md:mt-0 xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                <i class="fa-solid fa-chart-pie mr-1.5"></i>
                {{ __('Register new Grafana panel') }}

                <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                    {{ __('Enter the data for the new Grafana panel.') }}
                </p>
            </h1>
            <form action="{{ route('admin.grafana.store') }}" method="POST">
                @csrf
                <div>
                    <x-label for="name">
                        <i class="fa-solid fa-chart-pie mr-1"></i>
                        {{ __('Name') }}
                    </x-label>
                    <div class="flex gap-2">
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" required autofocus autocomplete="name"
                            placeholder="{{ __('Grafana panel name') }}" />
                    </div>
                </div>
                <div class="mt-6">
                    <x-label for="url">
                        <i class="fa-solid fa-link mr-1"></i>
                        {{ __('URL') }}
                    </x-label>
                    <x-input id="url" class="block mt-1 w-full" type="text" name="url"
                        :value="old('url')" required autocomplete="url"
                        placeholder="{{ __('Grafana panel URL') }}" />
                </div>
                <div class="mt-6">
                    <x-label for="api_url">
                        <i class="fa-solid fa-link mr-1"></i>
                        {{ __('API URL') }}
                    </x-label>
                    <x-input id="api_url" class="block mt-1 w-full" type="text" name="api_url"
                        :value="old('api_url')" autocomplete="api_url"
                        required placeholder="{{ __('Grafana panel API URL') }}" />
                </div>
                <div class="mt-6">
                    <x-label for="endpoint">
                        <i class="fa-solid fa-cloud mr-1"></i>
                        {{ __('Endpoint') }}
                    </x-label>
                    <x-input id="endpoint" class="block mt-1 w-full" type="text" name="endpoint"
                        :value="old('endpoint')" autocomplete="endpoint"
                        required placeholder="{{ __('Grafana panel API Endpoint') }}" />
                </div>
                <div class="mt-6">
                    <x-label for="api_key">
                        <i class="fa-solid fa-key mr-1"></i>
                        {{ __('API Key') }}
                    </x-label>
                    <x-input id="api_key" class="block mt-1 w-full" type="text" name="api_key"
                        :value="old('api_key')" autocomplete="api_key"
                        required placeholder="{{ __('Grafana panel API Key') }}" />
                </div>
                <div class="flex justify-end items-center">
                    <x-button class="flex justify-center items-center mt-8 font-bold">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        {{ __('Register new panel') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
