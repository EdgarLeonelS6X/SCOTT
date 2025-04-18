<x-admin-layout :breadcrumbs="[
    ['name' => __('Dashboard'), 'icon' => 'fa-solid fa-house', 'route' => route('admin.dashboard')],
    ['name' => __('Users'), 'icon' => 'fa-solid fa-user-group', 'route' => route('admin.users.index')],
    ['name' => $user->name, 'icon' => 'fa-solid fa-user'],
]">

    @php
        $auth = auth()->user();
        $isAuthMaster = $auth->hasRole('master');
        $isAuthAdmin = $auth->hasRole('admin');
        $isTargetAdmin = $user->hasRole('admin');
        $isTargetUser = $user->hasRole('user');
        $isSelf = $auth->id === $user->id;
        $canEditRoles = $isAuthMaster && !$isSelf;
        $canEditPermissions = ($isAuthMaster && !$isSelf) || ($isAuthAdmin && $isTargetUser && !$isSelf);
    @endphp

    <div class="w-full max-w-6xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-6 pb-4">
            <div class="flex items-center gap-4">
                <img class="h-20 w-20 rounded-full object-cover shadow-md" src="{{ $user->profile_photo_url }}"
                    alt="{{ $user->name }}" />
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <i class="fa-solid fa-envelope mr-1"></i>{{ $user->email }}
                    </p>
                </div>
            </div>
            <div class="text-sm text-right">
                <div class="text-gray-500 dark:text-gray-300 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-day"></i>
                    <span>{{ __('Joined on') }} {{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="mt-2">
                    @if ($user->hasVerifiedEmail())
                        <span
                            class="inline-flex items-center text-xs font-medium text-green-700 bg-green-200 dark:bg-green-800 dark:text-green-100 px-3 py-1 rounded-full">
                            <i class="fa-solid fa-check-circle mr-1"></i>{{ __('Email verified') }}
                        </span>
                    @else
                        <span
                            class="inline-flex items-center text-xs font-medium text-gray-600 bg-gray-200 dark:bg-gray-600 dark:text-gray-200 px-3 py-1 rounded-full">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i>{{ __('Email not verified') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.users.permissions', $user) }}">
            @csrf
            @method('PUT')
            <div class="space-y-2">
                <label for="role" class="text-sm font-medium text-gray-700 dark:text-gray-200">
                    <i class="fa-solid fa-shield-halved mr-2"></i>{{ __('Role') }}
                </label>
                <select id="role" name="role"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    @disabled(!$canEditRoles)>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @selected($user->hasRole($role->name))>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mt-6 space-y-2">
                <h2 class="text-sm font-medium text-gray-800 dark:text-white flex items-center">
                    <i class="fa-solid fa-key mr-2"></i>{{ __('Permissions') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php
                        $grouped = collect($permissions)->groupBy(fn($perm) => explode('.', $perm->name)[0]);
                        $icons = [
                            'channels' => 'fa-tv',
                            'stages' => 'fa-bars-staggered',
                            'roles' => 'fa-shield-halved',
                            'permissions' => 'fa-key',
                        ];
                    @endphp
                    @foreach ($grouped as $group => $perms)
                        <div
                            class="p-4 rounded-md border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                            <h3
                                class="text-sm font-bold text-gray-700 dark:text-white uppercase mb-3 flex items-center gap-2 {{ !$canEditPermissions ? 'opacity-50' : '' }}">
                                <i class="fa-solid {{ $icons[$group] ?? 'fa-lock' }}"></i>{{ ucfirst($group) }}
                            </h3>
                            <div class="space-y-2">
                                @foreach ($perms as $permission)
                                    <label
                                        class="flex items-center gap-2 {{ !$canEditPermissions ? 'opacity-50' : '' }}">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            class="w-4 h-4 text-primary-600 bg-white border-gray-300 rounded focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600"
                                            @checked($user->hasPermissionTo($permission->name)) @disabled(!$canEditPermissions)>
                                        <span class="text-sm text-gray-800 dark:text-gray-200">
                                            {{ ucfirst(str_replace($group . '.', '', $permission->name)) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @if (!$canEditRoles && !$canEditPermissions)
                <div class="text-center mt-5 text-sm text-gray-500 dark:text-gray-400 italic">
                    {{ __("You don't have permission to edit this user's roles or permissions.") }}
                </div>
            @endif
            @if ($canEditRoles || $canEditPermissions)
                <div class="mt-5 text-right">
                    <button
                        class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-md shadow font-semibold">
                        <i class="fa-solid fa-floppy-disk mr-1"></i>
                        {{ __('Save changes') }}
                    </button>
                </div>
            @endif
        </form>
    </div>

</x-admin-layout>

<script>
    const allPermissions = @json($permissions->pluck('name'));
    const userHasPermissions = @json($user->getPermissionNames());

    const forbiddenByRole = {
        admin: ['roles.edit'],
        user: ['roles.edit', 'permissions.assign'],
    };

    function isForbidden(role, permission) {
        return forbiddenByRole[role]?.includes(permission);
    }

    function applyRolePermissions(role) {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
        const forbidden = forbiddenByRole[role] || [];

        checkboxes.forEach(checkbox => {
            const perm = checkbox.value;

            // Solo desactiva los prohibidos
            checkbox.disabled = forbidden.includes(perm);

            // Marcar todos excepto los explícitamente prohibidos
            checkbox.checked = !isForbidden(role, perm);

            // Desactivar si está prohibido
            checkbox.disabled = isForbidden(role, perm);
        });
    }

    function warnManualAssign(role) {
        const forbidden = forbiddenByRole[role] || [];
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="permissions[]"]');

        checkboxes.forEach(checkbox => {
            const perm = checkbox.value;

            // Limpiar cualquier listener anterior
            checkbox.replaceWith(checkbox.cloneNode(true));
        });

        // Re-asignar evento después del replaceWith
        const refreshedCheckboxes = document.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
        refreshedCheckboxes.forEach(checkbox => {
            const perm = checkbox.value;

            if (!isForbidden(role, perm)) {
                checkbox.addEventListener('change', () => {
                    // Aquí no se hace nada a menos que esté prohibido
                });
            } else {
                // Ya está desactivado, pero por si acaso
                checkbox.disabled = true;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const roleSelect = document.querySelector('select[name="role"]');
        if (roleSelect) {
            applyRolePermissions(roleSelect.value);
            warnManualAssign(roleSelect.value);

            roleSelect.addEventListener('change', (e) => {
                const selectedRole = e.target.value;
                applyRolePermissions(selectedRole);
                warnManualAssign(selectedRole);
            });
        }
    });
</script>
