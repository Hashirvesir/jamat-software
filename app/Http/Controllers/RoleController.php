<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('admin.roles', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);
        $role = Role::create(['name' => $request->name]);
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Created role ' . $request->name,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.roles')->with('success', 'Role created successfully');
    }
}