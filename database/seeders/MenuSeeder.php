<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::create([
            'title' => 'Dashboard',
            'icon' => 'fas fa-home',
            'route' => 'dashboard',
            'permission' => 'menu.dashboard',
            'order' => 1,
        ]);

        $setting = Menu::create([
            'title' => 'Pengaturan',
            'icon' => 'fas fa-cog',
            'permission' => 'menu.setting',
            'order' => 2,
        ]);

        Menu::create([
            'title' => 'User Management',
            'icon' => 'fas fa-users',
            'route' => 'users.index',
            'parent_id' => $setting->id,
            'permission' => 'menu.admin',
        ]);

        $referensi = Menu::create([
            'title' => 'Referensi',
            'icon' => 'fas fa-cog', // gear tetap oke
            'permission' => 'menu.referensi',
            'order' => 3,
        ]);

        Menu::create([
            'title' => 'Jenis Surat',
            'icon' => 'fas fa-file-alt', // ganti menjadi icon dokumen
            'route' => 'modulsurat.index',
            'parent_id' => $referensi->id,
            'permission' => 'menu.referensi.jenissurat',
        ]);

        Menu::create([
            'title' => 'Pegawai',
            'icon' => 'fas fa-users', // ikon kumpulan pegawai / SDM
            'route' => 'pegawai.index',
            'parent_id' => $referensi->id,
            'permission' => 'menu.referensi.pegawai',
        ]);

        $ttesurat = Menu::create([
            'title' => 'TTE Surat',
            'icon' => 'fas fa-envelope-open-text', // surat digital / TTE
            'permission' => 'menu.ttesurat',
            'order' => 4,
        ]);

        Menu::create([
            'title' => 'Monitoring',
            'icon' => 'fas fa-chart-line', // monitoring / tracking
            'route' => 'monitoring.index',
            'parent_id' => $ttesurat->id,
            'permission' => 'menu.ttesurat.monitoring',
        ]);

        Menu::create([
            'title' => 'Arsip',
            'icon' => 'fas fa-file-archive', // monitoring / tracking
            'route' => 'arsip.index',
            'parent_id' => $ttesurat->id,
            'permission' => 'menu.ttesurat.arsip',
        ]);

    }
}
