<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // PERMISSIONS
        $permissions = [
            'menu.dashboard',
            'menu.user',
            'menu.setting',
            'menu.admin',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name'       => $perm,
                'guard_name' => 'web',
            ]);
        }

        // ROLES
        $admin = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        $user = Role::firstOrCreate([
            'name'       => 'user',
            'guard_name' => 'web',
        ]);

        // ASSIGN PERMISSIONS
        $admin->givePermissionTo(Permission::all());
        $user->givePermissionTo('menu.dashboard');
    }
}
