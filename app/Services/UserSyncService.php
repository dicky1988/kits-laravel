<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserTteNew;
use Illuminate\Support\Facades\Hash;

class UserSyncService
{
    public static function syncByNipLama(): int
    {
        $count = 0;

        UserTteNew::whereNotNull('pegawai_id')
            ->chunk(100, function ($tteUsers) use (&$count) {

                foreach ($tteUsers as $tteUser) {

                    $user = User::where('nip_lama', $tteUser->pegawai_id)->first();
                    $usertte = UserTteNew::where('pegawai_id', $tteUser->pegawai_id)->first();
                    if($usertte->role == 99) {
                        $user->syncRoles(['superadmin']);
                    }
                    if($usertte->role == 1) {
                        $user->syncRoles(['struktural']);
                    }
                    if($usertte->role == 2) {
                        $user->syncRoles(['pegawai']);
                    }
                    if($usertte->role == 26) {
                        $user->syncRoles(['sekretaris']);
                    }

                    // ===============================
                    // JIKA DATA SUDAH ADA
                    // ===============================
                    if ($user) {

                        // 🚫 Jangan update jika is_sync = 0
                        if ((int) $user->is_sync === 0) {
                            continue;
                        }

                        // Update data
                        $user->update([
                            'name'           => $tteUser->name,
                            'username'       => $tteUser->nip,
                            'email'          => $tteUser->email,
                            'nip'            => $tteUser->nip,
                            'nik'            => $tteUser->nik,
                            'eselon'         => $tteUser->eselon,
                            'akses_modul'    => $tteUser->akses_modul,
                            'is_ujikom'      => $tteUser->is_ujikom,
                            'is_sertifikat'  => $tteUser->is_sertifikat,
                            'is_bangkom'     => $tteUser->is_bangkom,
                            'is_skp'         => $tteUser->is_skp,
                            'is_bidang3'     => $tteUser->is_bidang3,
                            'is_aktif'       => $tteUser->is_aktif,
                        ]);

                        $count++;
                        continue;
                    }

                    // ===============================
                    // JIKA DATA BELUM ADA → INSERT
                    // ===============================
                    User::create([
                        'name'           => $tteUser->name,
                        'username'       => $tteUser->nip,
                        'email'          => $tteUser->email,
                        'nip'            => $tteUser->nip,
                        'nip_lama'       => $tteUser->pegawai_id,
                        'password'       => Hash::make('password'),
                        'nik'            => $tteUser->nik,
                        'eselon'         => $tteUser->eselon,
                        'akses_modul'    => $tteUser->akses_modul,
                        'is_ujikom'      => $tteUser->is_ujikom,
                        'is_sertifikat'  => $tteUser->is_sertifikat,
                        'is_bangkom'     => $tteUser->is_bangkom,
                        'is_skp'         => $tteUser->is_skp,
                        'is_bidang3'     => $tteUser->is_bidang3,
                        'is_aktif'       => $tteUser->is_aktif,
                        //'is_sync'        => 1, // default sync aktif
                    ]);

                    $count++;
                }
            });

        return $count;
    }
}
