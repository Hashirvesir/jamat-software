<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class UserController extends Controller
{
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->role);

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Created user ' . $request->name,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User created successfully');
    }
}