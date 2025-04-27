<div class="w-full max-w-6xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-6 pb-4">
        <div class="flex items-center gap-4">
            <img class="h-20 w-20 rounded-full object-cover shadow-md" src="{{ $user->profile_photo_url }}"
                alt="{{ $user->name }}" />
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $user->name }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    <i class="fa-solid fa-envelope mr-1"></i>
                    {{ $user->email }}
                </p>
                <div x-data="{ open: false }" @preferences-saved.window="open = false" class="relative">
                    <button @click="open = !open" type="button"
                        class="flex items-center text-xs text-primary-600 hover:text-primary-700 transition font-medium">
                        <i class="fa-solid fa-reply-all mr-1"></i>
                        <span>{{ __('Report mail preferences') }}</span>
                        <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                            class="fa-solid ml-2 transition-transform duration-300"></i>
                    </button>
                    <div x-show="open" x-transition.origin.top.left @click.outside="open = false"
                        class="mt-2 bg-white dark:bg-gray-700 shadow-lg rounded-lg p-4 space-y-4 border dark:border-gray-600 absolute z-10 w-72">
                        <form wire:submit.prevent="saveReportPreferences" class="space-y-3">
                            @php
                                $reportMailsList = [
                                    'report_created' => 'Reporte Creado',
                                    'report_updated' => 'Reporte Actualizado',
                                    'report_resolved' => 'Reporte Resuelto',
                                    'report_functions_created' => 'Funciones de Reporte',
                                    'report_general_created' => 'Reporte General',
                                ];
                            @endphp
                            @foreach ($reportMailsList as $key => $label)
                                <x-label class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                                    <x-checkbox type="checkbox" wire:model.defer="reportMails.{{ $key }}" />
                                    <span>{{ $label }}</span>
                                </x-label>
                            @endforeach
                            <div class="flex justify-end pt-2">
                                <button type="submit"
                                    class="text-xs px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition font-semibold">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
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
    <div class="space-y-2">
        <label for="role" class="text-sm font-medium text-gray-700 dark:text-gray-200">
            <i class="fa-solid fa-shield-halved mr-2"></i>{{ __('Role') }}
        </label>
        <select id="role" wire:model="role" x-data x-on:change="$wire.onRoleChanged($event.target.value)"
            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            @disabled(!$canEditRoles)>
            @foreach ($allRoles as $r)
                <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
            @endforeach
        </select>
    </div>
    <div class="mt-6 space-y-2">
        <h2 class="text-sm font-medium text-gray-800 dark:text-white flex items-center">
            <i class="fa-solid fa-key mr-2"></i>
            {{ __('Permissions') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $grouped = collect($allPermissions)->groupBy(fn($perm) => explode('.', $perm->name)[0]);
                $icons = [
                    'channels' => 'fa-tv',
                    'stages' => 'fa-bars-staggered',
                    'roles' => 'fa-shield-halved',
                    'permissions' => 'fa-key',
                ];
            @endphp
            @foreach ($grouped as $group => $perms)
                <div class="p-4 rounded-md border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                    <h3
                        class="text-sm font-bold text-gray-700 dark:text-white uppercase mb-3 flex items-center gap-2 {{ !$canEditPermissions ? 'opacity-50' : '' }}">
                        <i class="fa-solid {{ $icons[$group] ?? 'fa-lock' }}"></i>{{ ucfirst($group) }}
                    </h3>
                    <div class="space-y-2">
                        @foreach ($perms as $permission)
                            <label class="flex items-center gap-2 {{ !$canEditPermissions ? 'opacity-50' : '' }}">
                                <input type="checkbox" value="{{ $permission->name }}" wire:model="permissions"
                                    class="w-4 h-4 text-primary-600 bg-white border-gray-300 rounded focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600"
                                    @disabled(!$canEditPermissions || $role === 'master' || in_array($permission->name, $forbiddenPermissions))>
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
            <button wire:click="save"
                class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-md shadow font-semibold">
                <i class="fa-solid fa-floppy-disk mr-1"></i>
                {{ __('Save changes') }}
            </button>
        </div>
    @endif
</div>
