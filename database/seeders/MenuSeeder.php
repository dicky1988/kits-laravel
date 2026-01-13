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
    }
}
