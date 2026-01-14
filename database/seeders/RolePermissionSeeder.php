<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // PERMISSIONS
        $permissions = [
            'menu.dashboard',
            'menu.setting',
            'menu.admin',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web',
            ]);
        }

        // ROLE
        $superadmin = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
        ]);

        // 🔥 INI YANG MENGISI role_has_permissions
        $superadmin->givePermissionTo(Permission::all());

        // USER
        $user = User::find(1);
        if ($user) {
            $user->assignRole($superadmin);
        }
    }
}
