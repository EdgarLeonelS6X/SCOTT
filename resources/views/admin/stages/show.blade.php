<x-admin-layout :breadcrumbs="[
        [
            'name' => __('Dashboard'),
            'icon' => 'fa-solid fa-wrench',
            'route' => route('admin.dashboard'),
        ],
        [
            'name' => __('Stages'),
            'icon' => 'fa-solid fa-bars-staggered',
            'route' => route('admin.stages.index'),
        ],
        [
            'name' => __('Stage'),
            'icon' => 'fa-solid fa-circle-info',
        ],
    ]">

    <x-slot name="action">
        <div class="hidden lg:flex space-x-2">
            <a href="{{ route('admin.stages.index') }}"
                class="flex justify-center items-center text-white bg-gray-600 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm px-5 py-2 text-center">
                <i class="fa-solid fa-arrow-left mr-1.5"></i>
                {{ __('Go back') }}
            </a>
            @can('edit', $stage)
                <a href="{{ route('admin.stages.edit', $stage) }}"
                    class="flex justify-center items-center text-white bg-blue-600 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2 text-center">
                    <i class="fa-solid fa-pen-to-square mr-1.5"></i>
                    {{ __('Edit') }}
                </a>
            @endcan
            @can('delete', $stage)
                <button onclick="confirmDelete()"
                    class="flex justify-center items-center text-white bg-red-600 hover:bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2 text-center">
                    <i class="fa-solid fa-trash-can mr-1.5"></i>
                    {{ __(key: 'Delete') }}
                </button>
            @endcan
        </div>
    </x-slot>
    <div class="w-full bg-white rounded-lg shadow-2xl dark:border md:mt-0 xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-6">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                <div class="flex justify-between items-center">
                    <div class="w-full flex flex-col items-center justify-center gap-4">
                        <div class="w-full flex flex-col md:flex-row md:items-center md:justify-between gap-4 py-4">
                            <span
                                class="block text-center md:text-left font-extrabold text-3xl text-gray-900 dark:text-white mb-2 md:mb-0">{{ __($stage->name) }}</span>
                            <div class="flex flex-row flex-wrap items-center justify-center md:justify-end gap-3">
                                @php
                                    $areaClasses = $stage->area === 'DTH'
                                        ? 'bg-secondary-200 text-secondary-800 dark:bg-secondary-700 dark:text-secondary-100'
                                        : 'bg-primary-200 text-primary-800 dark:bg-primary-700 dark:text-primary-100';
                                @endphp
                                @if ($stage->area === 'OTT')
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full {{ $areaClasses }} shadow transition duration-150"
                                        role="status" aria-label="Área OTT" title="Área OTT">
                                        <i class="fa-solid fa-cube mr-2"></i> OTT
                                    </span>
                                @elseif ($stage->area === 'DTH')
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full {{ $areaClasses }} shadow transition duration-150"
                                        role="status" aria-label="Área DTH" title="Área DTH">
                                        <i class="fa-solid fa-satellite-dish mr-2"></i> DTH
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400 italic">{{ __('N/A') }}</span>
                                @endif
                                @if ($stage->status === 1)
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-sm font-semibold text-green-800 bg-green-200 rounded-full dark:bg-green-800 dark:text-green-200 shadow transition duration-150"
                                        role="status" aria-label="Activo" title="Activo">
                                        <i class="fa-solid fa-check-circle mr-2"></i>
                                        {{ __('Active') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-sm font-semibold text-red-800 bg-red-200 rounded-full dark:bg-red-800 dark:text-red-200 shadow transition duration-150"
                                        role="status" aria-label="Inactivo" title="Inactivo">
                                        <i class="fa-solid fa-times-circle mr-2"></i>
                                        {{ __('Inactive') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </h1>
        </div>
    </div>
    <div class="lg:hidden mt-6 space-y-5">
        <a href="{{ route('admin.stages.index') }}"
            class="flex justify-center items-center w-full text-white bg-gray-600 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm px-4 py-2">
            <i class="fa-solid fa-arrow-left mr-1.5"></i>
            {{ __('Go back') }}
        </a>
        @can('stages.edit')
            <a href="{{ route('admin.stages.edit', $stage) }}"
                class="flex justify-center items-center w-full text-white bg-blue-600 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-4 py-2">
                <i class="fa-solid fa-pen-to-square mr-1.5"></i>
                {{ __('Edit') }}
            </a>
        @endcan
        @can('stages.delete')
            <button onclick="confirmDelete()"
                class="flex justify-center items-center w-full text-white bg-red-600 hover:bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-4 py-2">
                <i class="fa-solid fa-trash-can mr-1.5"></i>
                {{ __('Delete') }}
            </button>
        @endcan
    </div>
    <form action="{{ route('admin.stages.destroy', $stage) }}" method="POST" id="delete-form">
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
