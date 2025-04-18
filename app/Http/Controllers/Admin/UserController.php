<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $users = User::all();
        return view("admin.users.index", compact("users"));
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.users.show', compact('user', 'roles', 'permissions'));
    }

    public function updatePermissions(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'role' => 'nullable|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $auth = $request->user();
        $requestedPermissions = collect($data['permissions'] ?? []);
        $forbidden = [
            'admin' => ['roles.edit'],
            'user' => ['roles.edit', 'permissions.assign'],
        ];

        if ($auth->hasRole('master')) {
            if (isset($data['role'])) {
                $user->syncRoles([$data['role']]);
            }

            $user->syncPermissions($requestedPermissions);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => __('Well done!'),
                'text' => __('Permissions updated successfully.'),
            ]);
        } elseif ($auth->hasRole('admin') && $user->hasRole('user')) {
            // Bloquear solo los permisos prohibidos
            $blocked = collect($forbidden['user']);
            $filteredPermissions = $requestedPermissions->reject(fn($perm) => $blocked->contains($perm));

            $user->syncPermissions($filteredPermissions);

            if ($filteredPermissions->count() !== $requestedPermissions->count()) {
                session()->flash('swal', [
                    'icon' => 'warning',
                    'title' => __('Not allowed'),
                    'text' => __('Some permissions were not allowed and have been excluded.'),
                ]);
            } else {
                session()->flash('swal', [
                    'icon' => 'success',
                    'title' => __('Well done!'),
                    'text' => __('Permissions updated successfully.'),
                ]);
            }
        } else {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => __('Access Denied'),
                'text' => __('You are not authorized to perform this action.'),
            ]);
        }

        return redirect()->back();
    }
}
