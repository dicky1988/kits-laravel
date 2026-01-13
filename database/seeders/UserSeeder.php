<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'dicky.satria.utama1988@gmail.com'],
            [
                'name' => 'Dicky Satria Utama',
                'nip' => '198808192010121001',
                'nip_lama' => '201000043',
                'username' => '198808192010121001',
                'password' => '$2y$12$v7dtjNpiNE9pciecbR8vOOGGqXc6zaENiMNOTsuZvu/nu9SSrQKyq',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $user = User::find(1);
        $user->assignRole('admin');
    }
}
