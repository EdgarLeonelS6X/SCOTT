<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('status', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.users.show', compact('user', 'roles', 'permissions'));
    }

    public function create()
    {
        if (!auth()->user() || !auth()->user()->hasRole('master')) {
            abort(403);
        }
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    'unique:users,email',
                    'regex:/^[A-Za-z0-9._%+-]+@stargroup\\.com\\.mx$/i',
                ],
                'password' => 'required|string|min:8|confirmed',
                'role' => 'nullable|exists:roles,name',
                'status' => 'required|boolean',
            ], [
                'email.regex' => __('Only @stargroup.com.mx emails are allowed.'),
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $errorMessages = '<ul style="list-style-type: disc; padding-left: 20px;">';
            foreach ($errors as $error) {
                $errorMessages .= "<li style='list-style-position: inside;'>$error</li>";
            }
            $errorMessages .= '</ul>';
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'html' => '<b>' . __('Your registration contains the following errors:') . '</b><br><br>' . $errorMessages,
            ]);
            return redirect()->back()->withInput();
        }
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->status = $data['status'];
        $user->email_verified_at = now();
        $user->save();
        if (isset($data['role'])) {
            $user->syncRoles([$data['role']]);
        }
        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('User created successfully.'),
        ]);
        return redirect()->route('admin.users.show', $user);
    }

    public function edit(User $user)
    {
        if ($user->id == 1) {
            abort(403, 'This user is protected and cannot be edited.');
        }
        if (!auth()->user() || !auth()->user()->hasRole('master')) {
            abort(403);
        }
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->id == 1) {
            abort(403, 'This user is protected and cannot be updated.');
        }
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'role' => 'nullable|exists:roles,name',
                'area' => 'required|in:OTT,DTH',
                'status' => 'required|boolean',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $errorMessages = '<ul style="list-style-type: disc; padding-left: 20px;">';
            foreach ($errors as $error) {
                $errorMessages .= "<li style='list-style-position: inside;'>$error</li>";
            }
            $errorMessages .= '</ul>';
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'html' => '<b>' . __('Your registration contains the following errors:') . '</b><br><br>' . $errorMessages,
            ]);
            return redirect()->back()->withInput();
        }
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->area = $data['area'];
        $user->status = $data['status'];
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->save();
        if (isset($data['role'])) {
            $user->syncRoles([$data['role']]);
        }
        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('User updated successfully.'),
        ]);
        return redirect()->route('admin.users.show', $user);
    }

    public function destroy(User $user)
    {
        if ($user->id == 1) {
            abort(403, 'This user is protected and cannot be deleted.');
        }
        if (!auth()->user() || !auth()->user()->hasRole('master')) {
            abort(403);
        }
        $user->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('User deleted successfully.'),
        ]);
        return redirect()->route('admin.users.index');
    }

    public function updatePermissions(Request $request, User $user)
    {
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
            $blocked = collect($forbidden['user']);
            $filtered = $requestedPermissions->reject(fn($perm) => $blocked->contains($perm));

            $user->syncPermissions($filtered);

            if ($filtered->count() !== $requestedPermissions->count()) {
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

        return redirect()->back()->with('success', __('Permissions updated successfully.'));
    }

    public function switchArea($area): RedirectResponse
    {
        $user = Auth::user();
        if ($user && ($user->role === 'master' || $user->hasRole('master')) && in_array($area, ['OTT', 'DTH'])) {
            $user->area = $area;
            $user->save();
            Auth::setUser($user->fresh());
        }
        return Redirect::back();
    }
}
