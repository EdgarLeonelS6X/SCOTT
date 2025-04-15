<x-admin-layout :breadcrumbs="[
    [
        'name' => __('Dashboard'),
        'icon' => 'fa-solid fa-house',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => __('Users'),
        'icon' => 'fa-solid fa-user-group',
    ],
]">

    <h1 class="text-xl font-bold mb-4">Usuarios</h1>

    <table class="w-full table-auto border-collapse border border-gray-200 dark:border-gray-700">
        <thead class="bg-gray-100 dark:bg-gray-800">
            <tr>
                <th class="px-4 py-2">Nombre</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Rol</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-t dark:border-gray-700">
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">{{ $user->getRoleNames()->join(', ') }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-500 hover:underline">
                            Ver
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</x-admin-layout>
