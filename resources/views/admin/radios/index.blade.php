<x-admin-layout :breadcrumbs="[
        [
            'name' => __('Dashboard'),
            'icon' => 'fa-solid fa-wrench',
            'route' => route('admin.dashboard'),
        ],
        [
            'name' => __('Radios'),
            'icon' => 'fa-solid fa-radio',
        ],
    ]">

    @if ($radios->count())
        @can('create', App\Models\Radio::class)
            <x-slot name="action">
                <a href="{{ route('admin.radios.create') }}"
                    class="hidden sm:block text-white {{ Auth::user()?->area === 'DTH'
                    ? 'bg-secondary-700 hover:bg-secondary-800 focus:ring-4 focus:ring-secondary-300 dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-800'
                    : 'bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800' }} font-medium rounded-lg text-sm px-5 py-2 focus:outline-none shadow-xl">
                    <i class="fa-solid fa-plus mr-1"></i>
                    {{ __('Register new radio') }}
                </a>
            </x-slot>
            <a href="{{ route('admin.radios.create') }}"
                class="mb-4 sm:hidden block text-center text-white {{ Auth::user()?->area === 'DTH'
                ? 'bg-secondary-700 hover:bg-secondary-800 focus:ring-4 focus:ring-secondary-300 dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-800'
                : 'bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800' }} font-medium rounded-lg text-sm px-5 py-2 focus:outline-none shadow-xl">
                <i class="fa-solid fa-plus mr-1"></i>
                {{ __('Register new radio') }}
            </a>
        @endcan

        <div class="bg-white dark:bg-gray-800 relative shadow-2xl rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full table-fixed text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs dark:text-white uppercase dark:bg-gray-600 shadow-2xl">
                        <tr>
                            <th scope="col" class="px-4 py-3 w-[350px]">
                                <i class="fa-solid fa-radio mr-1"></i>
                                {{ __('Name') }}
                            </th>
                            <th scope="col" class="px-4 py-3 w-[220px]">
                                <i class="fa-solid fa-link mr-1"></i>
                                {{ __('URL') }}
                            </th>
                            <th scope="col" class="px-4 py-3 w-[175px]">
                                <i class="fa-solid fa-building mr-1"></i>
                                {{ __('Area') }}
                            </th>
                            <th scope="col" class="px-4 py-3 w-[175px]">
                                <i class="fa-solid fa-toggle-on mr-1"></i>
                                {{ __('Status') }}
                            </th>
                            <th scope="col" class="px-4 py-3 text-center whitespace-nowrap w-[80px]"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($radios as $radio)
                            <tr onclick="window.location.href='{{ route('admin.radios.show', $radio) }}'"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600 text-black dark:text-white cursor-pointer group">
                                <td class="px-4 py-3 whitespace-nowrap flex items-center gap-3">
                                    <button class="flex text-sm rounded-full shadow-2xl cursor-default">
                                        <img src="{{ $radio->image_url ? asset('storage/' . $radio->image_url) : asset('img/no-image.png') }}"
                                            alt="{{ $radio->name }}" class="w-10 h-10 object-center object-contain rounded-sm">
                                    </button>
                                    <span class="font-semibold text-gray-900 dark:text-white truncate block max-w-[290px]">
                                        {{ $radio->name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-200 rounded-full dark:bg-blue-800 dark:text-blue-200">
                                        <i class="fa-solid fa-bullseye mr-1.5"></i>
                                        {{ $radio->url }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-secondary-200 text-secondary-800 dark:bg-secondary-700 dark:text-secondary-100">
                                        <i class="fa-solid fa-satellite-dish mr-1.5"></i>
                                        {{ $radio->area }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if (isset($radio->status) && $radio->status == 1)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-200 rounded-full dark:bg-green-800 dark:text-green-200">
                                            <i class="fa-solid fa-check-circle mr-1.5"></i>
                                            {{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-800 bg-red-200 rounded-full dark:bg-red-800 dark:text-red-200">
                                            <i class="fa-solid fa-times-circle mr-1.5"></i>
                                            {{ __('Inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="flex items-center h-full justify-center"
                                        style="height: 100%; min-height: 24px;">
                                        <i class="fa-solid fa-chevron-right transition-colors text-gray-300 group-hover:text-gray-700 dark:text-gray-500 dark:group-hover:text-gray-400"
                                        style="vertical-align: middle; font-size: 1.1em; line-height: 1;"></i>
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center bg-white dark:bg-gray-800">
                                    <i class="fa-solid fa-circle-info mr-2"></i>
                                    {{ __('There are no radios to display.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 shadow-xl"
            role="alert">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <div>
                    {{ __('There are no radios registered in the database.') }}
                </div>
            </div>

            <div class="flex justify-center sm:justify-end">
                <a href="{{ route('admin.radios.create') }}" class="text-white
                    {{ Auth::user()?->area === 'DTH'
                        ? 'bg-secondary-700 hover:bg-secondary-800 focus:ring-4 focus:ring-secondary-300 dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-800'
                        : 'bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800' }}
                        font-medium rounded-lg text-sm px-5 py-2 focus:outline-none shadow-xl">
                    <i class="fa-solid fa-plus mr-1"></i>
                    {{ __('Register new radio') }}
                </a>
            </div>
        </div>
    @endif

    @push('js')
        <script>
            function confirmDelete(radioID) {
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
                        document.getElementById('delete-form-' + radioID).submit();
                    }
                });
            }
        </script>
    @endpush

</x-admin-layout>
