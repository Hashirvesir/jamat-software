<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name']);
        Permission::create(['name' => $request->name]);

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Created permission ' . $request->name,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.permissions')->with('success', 'Permission created successfully');
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $request->validate(['name' => 'required|unique:permissions,name,' . $id]);
        $oldName = $permission->name;
        $permission->update(['name' => $request->name]);

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => "Updated permission from $oldName to $request->name",
            'created_at' => now(),
        ]);

        return redirect()->route('admin.permissions')->with('success', 'Permission updated successfully');
    }
}