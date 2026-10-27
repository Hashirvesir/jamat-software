<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\ReportController; // Ensure yeh line hai
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Public self-registration disabled: users/admins are created only via the
// admin panel (UserController) so a role is always assigned.

Route::middleware('auth')->group(function () {
    // Dashboard (view permission required)
    Route::get('/dashboard', [MemberController::class, 'dashboard'])
        ->name('dashboard')
        ->middleware('permission:view members');

    Route::get('/report', [ReportController::class, 'report'])
        ->name('members.report')
        ->middleware('permission:view members');
Route::get('/members/report/export', [ReportController::class, 'export'])->name('members.report.export')->middleware('role:admin');
    // Members Routes
    Route::get('/', [MemberController::class, 'index'])
        ->name('members.index')
        ->middleware('permission:view members');
    
    // Convenient aliases so /members/show/{id} or /member/{id} never 404
    Route::get('/members/show/{member}', function ($member) {
        return redirect()->route('members.show', $member);
    })->middleware('auth');
    Route::get('/member/{member}', function ($member) {
        return redirect()->route('members.show', $member);
    })->middleware('auth');
    Route::get('/member/show/{member}', function ($member) {
        return redirect()->route('members.show', $member);
    })->middleware('auth');

    Route::resource('members', MemberController::class)->except(['index'])->middleware('auth');
    
    // Family Tree Routes
    Route::get('/family-tree', [MemberController::class, 'selectFamilyTree'])
        ->name('members.select-family-tree')
        ->middleware('permission:view members');
    Route::get('/members/family-tree/{member}', [MemberController::class, 'familyTree'])
        ->name('members.family-tree')
        ->middleware('permission:view members');

    // Search and PDF Download Routes
    Route::get('/api/members/search-by-cnic', [MemberController::class, 'searchByCnic'])
        ->name('members.search-by-cnic')
        ->middleware('permission:view members');
    Route::get('/members/{member}/download-pdf', [MemberController::class, 'downloadPdf'])
        ->name('members.download-pdf')
        ->middleware('permission:view members');
    Route::get('/members/{member}/card', [MemberController::class, 'downloadCard'])
        ->name('members.card')
        ->middleware('permission:view members');
    Route::get('/members/{member}/download-family-tree-pdf', [MemberController::class, 'downloadFamilyTreePdf'])
        ->name('members.family-tree-pdf')
        ->middleware('permission:view members');

    // Communities Routes
    Route::resource('communities', CommunityController::class)
        ->only(['index', 'create', 'store'])
        ->middleware('auth');
    
    Route::get('/communities/{community}/edit', [CommunityController::class, 'edit'])
        ->name('communities.edit')
        ->middleware('permission:edit communities');
    Route::put('/communities/{community}', [CommunityController::class, 'update'])
        ->name('communities.update')
        ->middleware('permission:edit communities');
    Route::delete('/communities/{community}', [CommunityController::class, 'destroy'])
        ->name('communities.destroy')
        ->middleware('permission:delete communities');



    // Admin Routes (User Management, Permissions, Audit Logs, Roles)
    Route::middleware('role:admin')->group(function () {
        // Admin Dashboard
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/admin/users/{id}/roles', [AdminController::class, 'updateRoles'])->name('admin.users.roles');
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

        // Permission Management
        Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('admin.permissions');
        Route::post('/admin/permissions', [PermissionController::class, 'store'])->name('admin.permissions.store');
        Route::put('/admin/permissions/{id}', [PermissionController::class, 'update'])->name('admin.permissions.update');

        // Role Management
        Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles');
        Route::post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store');

        // User Creation
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');

        // Audit Logs   
        Route::get('/admin/audit-logs', [AdminController::class, 'auditLogs'])->name('admin.audit_logs');
    });
});