<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserPermissions extends Component
{
    public User $user;
    public $role;
    public $permissions = [];
    public $forbiddenPermissions = [];
    public $allRoles;
    public $allPermissions;
    public $canEditRoles;
    public $canEditPermissions;
    public $reportMails = [];

    public function mount(User $user)
    {
        $this->user = $user;

        $defaultPreferences = [
            'report_created' => false,
            'report_updated' => false,
            'report_resolved' => false,
            'report_functions_created' => false,
            'report_general_created' => false,
        ];

        $userPreferences = is_string($user->report_mail_preferences)
            ? json_decode($user->report_mail_preferences, true)
            : $user->report_mail_preferences;

        if (!is_array($userPreferences)) {
            $userPreferences = [];
        }

        $this->reportMails = array_merge($defaultPreferences, $userPreferences);

        $this->allRoles = Role::all();
        $this->allPermissions = Permission::all();

        $this->role = $user->roles->pluck('name')->first();
        if ($user->id === 1) {
            $this->permissions = $this->allPermissions->pluck('name')->toArray();
        } else {
            $this->permissions = $user->permissions->pluck('name')->toArray();
        }

        $auth = auth()->user();

        $isAuthMaster = $auth->hasRole('master');
        $isAuthAdmin = $auth->hasRole('admin');
        $isTargetAdmin = $user->hasRole('admin');
        $isTargetUser = $user->hasRole('user');
        $isSelf = $auth->id === $user->id;

        $this->canEditRoles = $isAuthMaster && !$isSelf;
        $this->canEditPermissions = ($isAuthMaster && !$isSelf) || ($isAuthAdmin && $isTargetUser && !$isSelf);

        $firstMasterId = User::role('master')->orderBy('id')->value('id');

        $isEditingFirstMaster = $user->id === $firstMasterId && $auth->id !== $firstMasterId;

        if ($isEditingFirstMaster) {
            $this->canEditRoles = false;
            $this->canEditPermissions = false;
        }
    }

    public function saveReportPreferences()
    {
        $auth = auth()->user();

        $isAuthMaster = $auth->hasRole('master');
        $isAuthAdmin = $auth->hasRole('admin');
        $isAuthUser = $auth->hasRole('user');

        $isTargetMaster = $this->user->hasRole('master');
        $isTargetAdmin = $this->user->hasRole('admin');
        $isTargetUser = $this->user->hasRole('user');

        $isSelf = $auth->id === $this->user->id;
        $isFirstMaster = $this->user->id === 1;

        $canEditPreferences =
            ($isFirstMaster && $isSelf) ||
            (!$isFirstMaster && (
                ($isAuthMaster) ||
                ($isAuthAdmin && ($isTargetUser || $isSelf)) ||
                ($isAuthUser && $isSelf)
            ));

        if (!$canEditPreferences) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => __('Access Denied'),
                'text' => __('You are not authorized to update these preferences.'),
            ]);
            return;
        }

        $reportMailOptions = [
            'report_created',
            'report_updated',
            'report_resolved',
            'report_functions_created',
            'report_general_created',
        ];

        $finalPreferences = [];

        foreach ($reportMailOptions as $option) {
            $finalPreferences[$option] = $this->reportMails[$option] ?? false;
        }

        $this->user->report_mail_preferences = $finalPreferences;
        $this->user->save();

        $this->dispatch('preferences-saved');

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => __('Preferences Updated'),
            'text' => __('Mail preferences saved successfully.'),
        ]);
    }

    public function toggleStatus()
    {
        $auth = auth()->user();
        if (!($auth->id === 1 && $auth->hasRole('master'))) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => __('Access Denied'),
                'text' => __('You are not authorized to change this status.'),
            ]);
            return;
        }

        $this->user->status = !$this->user->status;
        $this->user->save();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('User status changed successfully.'),
        ]);
    }

    public function updatedRole($value)
    {
        if (!$this->canEditPermissions)
            return;

        $this->applyRoleBasedPermissions($value);
    }

    public function applyRoleBasedPermissions($role)
    {
        $forbidden = [
            'admin' => ['roles.edit'],
            'user' => ['roles.edit', 'permissions.assign'],
        ];

        $this->forbiddenPermissions = $forbidden[$role] ?? [];

        if ($role === 'master') {
            $this->permissions = $this->allPermissions->pluck('name')->toArray();
        } else {
            $allowed = $this->allPermissions->filter(function ($perm) {
                return !in_array($perm->name, $this->forbiddenPermissions);
            });
            $this->permissions = $allowed->pluck('name')->toArray();
        }
    }

    public function onRoleChanged($value)
    {
        $this->role = $value;
        $this->updatedRole($value);
    }

    public function save()
    {
        $auth = auth()->user();

        if (!$this->canEditRoles && !$this->canEditPermissions) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => __('Access Denied'),
                'text' => __('You are not authorized to perform this action.')
            ]);
            return;
        }

        $requestedPermissions = collect($this->permissions);
        $forbidden = [
            'admin' => ['roles.edit'],
            'user' => ['roles.edit', 'permissions.assign'],
        ];

        if ($auth->hasRole('master')) {
            if ($this->canEditRoles && $this->role) {
                $this->user->syncRoles([$this->role]);
            }

            if ($this->canEditPermissions) {
                $this->user->syncPermissions($requestedPermissions);
            }

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => __('Well done!'),
                'text' => __('Permissions updated successfully.')
            ]);
        } elseif ($auth->hasRole('admin') && $this->user->hasRole('user')) {
            $blocked = collect($forbidden['user']);
            $filtered = $requestedPermissions->reject(fn($perm) => $blocked->contains($perm));

            $this->user->syncPermissions($filtered);

            $message = $filtered->count() !== $requestedPermissions->count()
                ? ['icon' => 'warning', 'title' => __('Not allowed'), 'text' => __('Some permissions were not allowed and have been excluded.')]
                : ['icon' => 'success', 'title' => __('Well done!'), 'text' => __('Permissions updated successfully.')];

            $this->dispatch('swal', $message);
        }
    }

    public function sendResetPasswordEmail()
    {
        $auth = auth()->user();
        if ($auth->id !== 1 || $this->user->id === 1) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => __('Access Denied'),
                'text' => __('You are not authorized to send a password reset email to this user.'),
            ]);
            return;
        }

        try {
            \Password::broker()->sendResetLink(['email' => $this->user->email]);
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => __('Email sent'),
                'text' => __('A password reset email has been sent to the user.'),
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => __('Error'),
                'text' => __('There was a problem sending the password reset email.'),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.users.user-permissions');
    }
}
