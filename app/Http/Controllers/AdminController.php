<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('admin.dashboard', compact('users', 'roles'));
    }

    public function updateRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'roles' => ['array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);
        $roles = $request->input('roles', []);
        $user->syncRoles($roles);

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Updated roles for user ' . $user->name,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Roles updated successfully');
    }

    public function deleteUser($id)
    {
        if ((int) $id === (int) auth()->id()) {
            return redirect()->route('admin.dashboard')->with('error', 'You cannot delete your own account.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Deleted user ' . $user->name,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully');
    }

    public function auditLogs()
    {
        $logs = AuditLog::with('user')->latest()->get();
        return view('admin.audit_logs', compact('logs'));
    }
}