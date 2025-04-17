<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view("admin.users.index", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', [
            'user' => $user,
            'roles' => Role::all(),
            'permissions' => Permission::all(),
        ]);
    }

    public function updatePermissions(Request $request, User $user)
    {
        $auth = Auth()->user();

        // Nadie puede modificar al super admin (ID 1)
        if ($user->id === 1 && $user->hasRole('admin')) {
            session()->flash('swal', [
                'icon' => 'info',
                'title' => __('Action not allowed'),
                'text' => __('You cannot change the role or permissions of the main admin account.'),
            ]);
            return redirect()->route('admin.users.show', $user);
        }

        // Solo admins pueden asignar roles y permisos
        if (!$auth->hasRole('admin')) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => __('Access Denied'),
                'text' => __('You do not have permission to modify roles or permissions.'),
            ]);
            return redirect()->route('admin.users.show', $user);
        }

        // Evitar que se asigne el rol admin si no lo tiene el usuario actual
        $requestedRole = $request->input('role');
        if ($requestedRole === 'admin' && !$auth->hasRole('admin')) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => __('Access Denied'),
                'text' => __('Only admins can assign the admin role.'),
            ]);
            return redirect()->route('admin.users.show', $user);
        }

        $user->syncRoles($requestedRole);
        $user->syncPermissions($request->input('permissions', []));

        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Changes saved!'),
            'text' => __('Roles and permissions were successfully updated.')
        ]);

        return redirect()->route('admin.users.show', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
