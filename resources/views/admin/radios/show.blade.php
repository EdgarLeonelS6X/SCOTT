<x-admin-layout :breadcrumbs="[
        [
            'name' => __('Dashboard'),
            'icon' => 'fa-solid fa-wrench',
            'route' => route('admin.dashboard'),
        ],
        [
            'name' => __('Radios'),
            'icon' => 'fa-solid fa-radio',
            'route' => route('admin.radios.index'),
        ],
        [
            'name' => __('Radio'),
            'icon' => 'fa-solid fa-circle-info',
        ],
    ]">

    <x-slot name="action">
        <div class="hidden lg:flex space-x-2">
            <a href="{{ route('admin.radios.index') }}"
                class="flex w-full sm:w-auto justify-center items-center text-white bg-gray-600 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm px-4 py-2 text-center">
                <i class="fa-solid fa-arrow-left mr-1.5"></i>
                {{ __('Go back') }}
            </a>
            @can('update', $radio)
                <a href="{{ route('admin.radios.edit', $radio) }}"
                    class="flex w-full sm:w-auto justify-center items-center text-white bg-blue-600 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-4 py-2 text-center">
                    <i class="fa-solid fa-pen-to-square mr-1.5"></i>
                    {{ __('Edit') }}
                </a>
            @endcan
            @can('delete', $radio)
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
                        {{ $radio->name }}
                    </h1>
                    <div class="mt-2 flex flex-wrap justify-center md:justify-start items-center gap-2">
                        @if ($radio->status == 1)
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
                        <span
                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-secondary-800 bg-secondary-200 dark:bg-secondary-800 dark:text-secondary-200 rounded-full">
                            <i class="fa-solid fa-satellite-dish mb-[1px] mr-1"></i>
                            {{ $radio->area }}
                        </span>
                    </div>
                </div>
                <div class="order-0 md:order-none">
                    <img src='{{ $radio->image_url ? asset("storage/{$radio->image_url}") : asset("img/no-image.png") }}'
                        alt="{{ $radio->name }}"
                        class="w-20 h-20 object-center object-contain rounded-lg mx-auto md:mx-0">
                </div>
            </div>

            <div class="space-y-4">
            <div>
                <x-label for="name">
                    <i class="fa-solid fa-radio mr-1"></i>
                    {{ __('Name') }}
                </x-label>
                <x-input id="name" class="block mt-1 w-full" type="text" wire:model="name"
                    value="{{ $radio->name }}" disabled />
            </div>
                <div>
                    <x-label for="url">
                        <i class="fa-solid fa-link mr-1"></i>
                        {{ __('Stream URL') }}
                    </x-label>
                    <x-input id="url" class="block mt-1 w-full" type="text" wire:model="url"
                        value="{{ $radio->url }}" disabled />
                </div>
            </div>
        </div>
    </div>
    <div class="lg:hidden mt-6 space-y-5">
        <a href="{{ route('admin.radios.index') }}"
            class="flex justify-center items-center w-full text-white bg-gray-600 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm px-4 py-2">
            <i class="fa-solid fa-arrow-left mr-1.5"></i>
            {{ __('Go back') }}
        </a>
        @can('update', $radio)
            <a href="{{ route('admin.radios.edit', $radio) }}"
                class="flex justify-center items-center w-full text-white bg-blue-600 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-4 py-2">
                <i class="fa-solid fa-pen-to-square mr-1.5"></i>
                {{ __('Edit') }}
            </a>
        @endcan
        @can('delete', $radio)
            <button onclick="confirmDelete()"
                class="flex justify-center items-center w-full text-white bg-red-600 hover:bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-4 py-2">
                <i class="fa-solid fa-trash-can mr-1.5"></i>
                {{ __('Delete') }}
            </button>
        @endcan
    </div>
    <form action="{{ route('admin.radios.destroy', $radio) }}" method="POST" id="delete-form">
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
