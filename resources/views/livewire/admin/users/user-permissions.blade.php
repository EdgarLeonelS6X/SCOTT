<div class="w-full mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4 sm:p-6">
    @php
        $area = Auth::user()?->area;
        $primaryBtn = $area === 'OTT' ? 'bg-primary-600 hover:bg-primary-700' : ($area === 'DTH' ? 'bg-secondary-600 hover:bg-secondary-700' : 'bg-primary-600 hover:bg-primary-700');
        $primaryBtnText = 'text-white';
    @endphp
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 pb-6">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 w-full sm:w-auto">
            <img class="h-16 w-16 sm:h-20 sm:w-20 rounded-full object-cover shadow-md"
                src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
            <div class="text-center sm:text-left space-y-1 w-full">
                @php
                    $userArea = $user->area ?? null;
                    $userAreaClasses = $userArea === 'DTH'
                        ? 'bg-secondary-200 text-secondary-800 dark:bg-secondary-700 dark:text-secondary-100'
                        : ($userArea === 'OTT' ? 'bg-primary-200 text-primary-800 dark:bg-primary-700 dark:text-primary-100' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200');
                @endphp
                <h1
                    class="flex flex-col md:flex-row flex-wrap items-center text-lg font-bold text-gray-900 dark:text-white gap-4">
                    <span>{{ $user->name }}</span>
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-300 break-words">
                    <i class="fa-solid fa-envelope mr-1"></i>
                    {{ $user->email }}
                    @php
                        $auth = auth()->user();
                        $isSelfFirstAdmin = $auth->id === 1 && $user->id === 1 && $auth->hasRole('master');
                    @endphp
                    <button @if($isSelfFirstAdmin) disabled @else wire:click="toggleStatus" @endif type="button"
                        class="ml-2.5 mt-2 sm:mt-0 inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold focus:outline-none transition
                        {{ $user->status ? ($isSelfFirstAdmin ? 'bg-gray-300 text-gray-500 dark:bg-gray-700 dark:text-gray-400' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100') : ($isSelfFirstAdmin ? 'bg-red-300 text-red-500 dark:bg-red-700 dark:text-red-400' : 'bg-red-200 text-red-600 dark:bg-red-700 dark:text-red-300') }}">
                        <i class="fa-solid {{ $user->status ? 'fa-circle-check' : 'fa-circle-xmark' }} mr-1"></i>
                        {{ $user->status ? __('Active') : __('Inactive') }}
                    </button>
                    @if($userArea)
                        <span
                            class="ml-2.5 inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $userAreaClasses }}">
                            <i class="fa-solid {{ $userArea === 'DTH' ? 'fa-satellite-dish' : 'fa-cube' }} mr-1"></i>
                            {{ $userArea }}
                        </span>
                    @endif
                </p>
                @php
                    $auth = auth()->user();
                    $isAuthMaster = $auth->hasRole('master');
                    $isAuthAdmin = $auth->hasRole('admin');
                    $isAuthUser = $auth->hasRole('user');

                    $isTargetMaster = $user->hasRole('master');
                    $isTargetAdmin = $user->hasRole('admin');
                    $isTargetUser = $user->hasRole('user');

                    $isSelf = $auth->id === $user->id;
                    $isFirstMaster = $user->id === 1;

                    $canSave =
                        ($isFirstMaster && $isSelf) ||
                        (!$isFirstMaster &&
                            ($isAuthMaster ||
                                ($isAuthAdmin && $isTargetUser && !$isSelf) ||
                                ($isAuthAdmin && $isSelf) ||
                                ($isAuthUser && $isSelf)));
                @endphp
                <div class="w-full flex justify-center sm:justify-start">
                    @php
                        $prefBtnText = $area === 'OTT' ? 'text-primary-600 hover:text-primary-700' : ($area === 'DTH' ? 'text-secondary-600 hover:text-secondary-700' : 'text-primary-600 hover:text-primary-700');
                    @endphp
                    <div x-data="{ open: false, canSave: {{ $canSave ? 'true' : 'false' }} }"
                        @preferences-saved.window="open = false" class="relative">
                        <button @click="canSave && (open = !open)" :class="{
                                'opacity-50': !canSave,
                                '{{ $area === 'OTT' ? 'hover:text-primary-700' : ($area === 'DTH' ? 'hover:text-secondary-700' : 'hover:text-primary-700') }}': canSave
                            }" :disabled="!canSave" type="button"
                            class="flex items-center justify-center sm:justify-start text-xs transition font-medium {{ $area === 'OTT' ? 'text-primary-600' : ($area === 'DTH' ? 'text-secondary-600' : 'text-primary-600') }}">
                            <i class="fa-solid fa-reply-all mr-2"></i>
                            <span>{{ __('Report mail preferences') }}</span>
                            <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                                class="fa-solid ml-2 transition-transform duration-300"></i>
                        </button>
                        <div x-show="open" x-transition.origin.top.left @click.outside="open = false"
                            class="mt-2 bg-white dark:bg-gray-700 shadow-lg rounded-lg p-4 space-y-4 border dark:border-gray-600 absolute z-10 w-full sm:w-72 left-0 sm:left-auto sm:right-0">
                            <form wire:submit.prevent="saveReportPreferences" class="space-y-3">
                                @php
                                    $reportMailsList = [
                                        'report_created' => __('Report Created'),
                                        'report_updated' => __('Report Updated'),
                                        'report_resolved' => __('Report Resolved'),
                                        'report_functions_created' => __('Report Functions Created'),
                                        'report_general_created' => __('General Report Created'),
                                    ];
                                @endphp
                                @foreach ($reportMailsList as $key => $label)
                                    <x-label class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                                        <x-checkbox type="checkbox" wire:model.defer="reportMails.{{ $key }}" />
                                        <span>{{ $label }}</span>
                                    </x-label>
                                @endforeach
                                <div class="flex justify-end pt-2">
                                    <button type="submit" {{ !$canSave ? 'disabled' : '' }}
                                        class="text-xs px-4 py-2 {{ $primaryBtn }} {{ $primaryBtnText }} rounded-md transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fa-solid fa-floppy-disk mr-1"></i>
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-sm text-center sm:text-right w-full sm:w-auto">
            <div class="text-gray-500 dark:text-gray-300 flex items-center justify-center sm:justify-end gap-2">
                <i class="fa-solid fa-calendar-day"></i>
                <span>{{ __('Joined on') }} {{ $user->created_at->format('d M Y') }}</span>
            </div>
            <div class="mt-2 flex flex-col sm:flex-row gap-2 justify-center sm:justify-end items-center sm:items-end">
                @php $auth = auth()->user(); @endphp
                @if ($user->hasVerifiedEmail())
                    <div
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-100 gap-2">
                        <i class="fa-solid fa-check-circle" aria-hidden="true"></i>
                        <span>{{ __('Email verified') }}</span>
                    </div>
                @else
                    <div
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-gray-700 dark:text-red-200 gap-2">
                        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                        <span>{{ __('Email not verified') }}</span>
                    </div>
                @endif
                @if ($auth && $auth->id === 1 && $auth->id !== $user->id)
                    @php
                        $resetBtnBg = $area === 'OTT' ? 'bg-primary-50 hover:bg-primary-100 dark:bg-primary-900' : ($area === 'DTH' ? 'bg-secondary-50 hover:bg-secondary-100 dark:bg-secondary-900' : 'bg-primary-50 hover:bg-primary-100 dark:bg-primary-900');
                        $resetBtnText = $area === 'OTT' ? 'text-primary-700 dark:text-primary-200' : ($area === 'DTH' ? 'text-secondary-700 dark:text-secondary-200' : 'text-primary-700 dark:text-primary-200');
                    @endphp
                    <button wire:click="sendResetPasswordEmail"
                        class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-full {{ $resetBtnBg }} {{ $resetBtnText }}">
                        <i class="fa-solid fa-envelope-circle-check" aria-hidden="true"></i>
                        <span>{{ __('Reset password') }}</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="space-y-2">
        <label for="role" class="text-sm font-medium text-gray-700 dark:text-gray-200">
            <i class="fa-solid fa-shield-halved mr-2"></i>{{ __('Role') }}
        </label>
        <select id="role" wire:model="role" x-data x-on:change="$wire.onRoleChanged($event.target.value)"
            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white {{ Auth::user()->area === 'DTH' ? 'focus:ring-secondary-600 focus:border-secondary-600 dark:focus:ring-secondary-500 dark:focus:border-secondary-500' : 'focus:ring-primary-600 focus:border-primary-600 dark:focus:ring-primary-500 dark:focus:border-primary-500' }}"
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @php
                $grouped = collect($allPermissions)->groupBy(fn($perm) => explode('.', $perm->name)[0]);
                $icons = [
                    'channels' => 'fa-tv',
                    'stages' => 'fa-bars-staggered',
                    'grafana' => 'fa-chart-pie',
                    'roles' => 'fa-shield-halved',
                    'permissions' => 'fa-key',
                ];
            @endphp
            @foreach ($grouped as $group => $perms)
                @if (!in_array($group, ['roles', 'permissions']))
                    <div class="p-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                        <h3
                            class="text-sm font-bold text-gray-700 dark:text-white uppercase mb-3 flex items-center gap-2 {{ !$canEditPermissions ? 'opacity-50' : '' }}">
                            <i class="fa-solid {{ $icons[$group] ?? 'fa-lock' }}"></i>{{ __(ucfirst($group)) }}
                        </h3>
                        <div class="space-y-2">
                            @php
                                $sortedPerms = collect($perms)->sortBy(function ($p) use ($group) {
                                    $parts = explode('.', $p->name, 2);
                                    $action = $parts[1] ?? '';
                                    return $action === 'view' ? 0 : 1;
                                })->values();
                            @endphp
                            @foreach ($sortedPerms as $permission)
                                @php
                                    $primaryCheckbox = $area === 'OTT' ? 'text-primary-600 focus:ring-primary-500' : ($area === 'DTH' ? 'text-secondary-600 focus:ring-secondary-500' : 'text-primary-600 focus:ring-primary-500');
                                @endphp
                                <label class="flex items-center gap-2 {{ !$canEditPermissions ? 'opacity-50' : '' }}">
                                    <input type="checkbox" value="{{ $permission->name }}" wire:model="permissions"
                                        class="w-4 h-4 {{ $primaryCheckbox }} bg-white border-gray-300 rounded focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        @disabled(!$canEditPermissions || in_array($permission->name, $forbiddenPermissions))>
                                    <span class="text-sm text-gray-800 dark:text-gray-200">
                                        {{ __(ucfirst(str_replace($group . '.', '', $permission->name))) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
            @if(isset($grouped['roles']) || isset($grouped['permissions']))
                <div class="p-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                    <h3
                        class="text-sm font-bold text-gray-700 dark:text-white uppercase mb-3 flex items-center gap-2 {{ !$canEditPermissions ? 'opacity-50' : '' }}">
                        <i class="fa-solid fa-shield-halved"></i>{{ __('Roles & Permissions') }}
                    </h3>
                    <div class="space-y-2">
                        @if(isset($grouped['roles']))
                            @foreach ($grouped['roles'] as $permission)
                                @php
                                    $primaryCheckbox = $area === 'OTT' ? 'text-primary-600 focus:ring-primary-500' : ($area === 'DTH' ? 'text-secondary-600 focus:ring-secondary-500' : 'text-primary-600 focus:ring-primary-500');
                                @endphp
                                <label class="flex items-center gap-2 {{ !$canEditPermissions ? 'opacity-50' : '' }}">
                                    <input type="checkbox" value="{{ $permission->name }}" wire:model="permissions"
                                        class="w-4 h-4 {{ $primaryCheckbox }} bg-white border-gray-300 rounded focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        @disabled(!$canEditPermissions || $role === 'master' || in_array($permission->name, $forbiddenPermissions))>
                                    <span class="text-sm text-gray-800 dark:text-gray-200">
                                        {{ __(ucfirst(str_replace('roles.', '', $permission->name))) }}
                                    </span>
                                </label>
                            @endforeach
                        @endif
                        @if(isset($grouped['permissions']))
                            @foreach ($grouped['permissions'] as $permission)
                                @php
                                    $primaryCheckbox = $area === 'OTT' ? 'text-primary-600 focus:ring-primary-500' : ($area === 'DTH' ? 'text-secondary-600 focus:ring-secondary-500' : 'text-primary-600 focus:ring-primary-500');
                                @endphp
                                <label class="flex items-center gap-2 {{ !$canEditPermissions ? 'opacity-50' : '' }}">
                                    <input type="checkbox" value="{{ $permission->name }}" wire:model="permissions"
                                        class="w-4 h-4 {{ $primaryCheckbox }} bg-white border-gray-300 rounded focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        @disabled(!$canEditPermissions || $role === 'master' || in_array($permission->name, $forbiddenPermissions))>
                                    <span class="text-sm text-gray-800 dark:text-gray-200">
                                        {{ __(ucfirst(str_replace('permissions.', '', $permission->name))) }}
                                    </span>
                                </label>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif
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
                class="{{ $primaryBtn }} {{ $primaryBtnText }} px-6 py-2 rounded-md shadow font-semibold">
                <i class="fa-solid fa-floppy-disk mr-1"></i>
                {{ __('Save changes') }}
            </button>
        </div>
    @endif
</div>
