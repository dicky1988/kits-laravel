<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // clear cache
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        /**
         * ========================
         * PERMISSIONS
         * ========================
         */
        $permissions = [
            'menu.dashboard',
            'menu.setting',
            'menu.admin',
            'menu.referensi',
            'menu.referensi.jenissurat',
            'menu.referensi.pegawai',
            'menu.ttesurat',
            'menu.ttesurat.monitoring',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name'       => $perm,
                'guard_name' => 'web',
            ]);
        }

        /**
         * ========================
         * ROLES
         * ========================
         */
        $superadmin = Role::firstOrCreate([
            'name'       => 'superadmin',
            'guard_name' => 'web',
        ]);

        $adminapp = Role::firstOrCreate([
            'name'       => 'adminapp',
            'guard_name' => 'web',
        ]);

        $userRole = Role::firstOrCreate([
            'name'       => 'user',
            'guard_name' => 'web',
        ]);

        $pegawai = Role::firstOrCreate([
            'name'       => 'pegawai',
            'guard_name' => 'web',
        ]);

        $sekretaris = Role::firstOrCreate([
            'name'       => 'sekretaris',
            'guard_name' => 'web',
        ]);

        $struktural = Role::firstOrCreate([
            'name'       => 'struktural',
            'guard_name' => 'web',
        ]);

        /**
         * ========================
         * ROLE → PERMISSION
         * ========================
         */

        // superadmin akses semua
        $superadmin->syncPermissions(Permission::all());

        // adminapp contoh: dashboard + setting
        $adminapp->syncPermissions([
            'menu.dashboard',
            'menu.setting',
        ]);

        // user contoh: dashboard saja
        $userRole->syncPermissions([
            'menu.dashboard',
        ]);

        /**
         * ========================
         * USER → ROLE
         * ========================
         */
        $user = User::find(1);

        if ($user) {
            // assign MULTIPLE roles sekaligus
            $user->syncRoles([
                'superadmin',
                'adminapp',
                'user',
            ]);

            // set active role default (opsional tapi direkomendasikan)
            if (!$user->active_role_id) {
                $user->active_role_id = $superadmin->id;
                $user->save();
            }
        }
    }
}
