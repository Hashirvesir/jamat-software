<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Permissions create karo
        $permissions = [
            'view communities',
            'create communities',
            'edit communities',
            'delete communities',
            'view members',
            'create members',
            'edit members',
            'delete members',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Admin role create karo aur saari permissions assign karo
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions);

        // User role create karo aur sirf view aur create permissions do
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo(['view communities', 'create communities', 'view members', 'create members']);

        // Admin user create karo
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'), // Password: password123
            'email_verified_at' => now(), // Email verified set karo
        ]);
        $admin->assignRole('admin');

        // Normal user create karo
        $user = User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'), // Password: password123
            'email_verified_at' => now(), // Email verified set karo
        ]);
        $user->assignRole('user');
    }
}