<x-admin-layout :breadcrumbs="[
    [
        'name' => __('Dashboard'),
        'icon' => 'fa-solid fa-house',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => __('Users'),
        'icon' => 'fa-solid fa-user-group',
        'route' => route('admin.users.index'),
    ],
    [
        'name' => $user->name,
        'icon' => 'fa-solid fa-circle-info',
    ],
]">

    <h1 class="text-xl font-bold mb-4">{{ $user->name }}</h1>

    <form method="POST" action="{{ route('admin.users.permissions', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="font-semibold">Rol:</label>
            <select name="role" class="w-full mt-1 rounded-md">
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" @if ($user->hasRole($role->name)) selected @endif>
                        {{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Permisos:</label>
            @foreach ($permissions as $permission)
                <div class="flex items-center">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                        @if ($user->hasPermissionTo($permission->name)) checked @endif>
                    <span class="ml-2">{{ $permission->name }}</span>
                </div>
            @endforeach
        </div>

        <button class="bg-primary-600 hover:bg-primary-500 text-white px-4 py-2 rounded-md">
            Guardar cambios
        </button>
    </form>

</x-admin-layout>
