<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserTteNew;

class UserSyncService
{
    public static function syncByNipLama(): int
    {
        $count = 0;

        UserTteNew::whereNotNull('pegawai_id')
            ->chunk(100, function ($tteUsers) use (&$count) {

                foreach ($tteUsers as $tteUser) {

                    User::updateOrCreate(
                    // KEY (untuk cari data)
                        [
                            'nip_lama' => $tteUser->pegawai_id,
                        ],

                        // DATA YANG DIUPDATE / INSERT
                        [
                            'name'     => $tteUser->name,
                            //'username' => $tteUser->username
                            //    ?? strtolower(str_replace(' ', '.', $tteUser->name)),
                            'username' => $tteUser->nip,
                            'email'    => $tteUser->email,
                            'nip'      => $tteUser->nip,
                            'nip_lama' => $tteUser->pegawai_id,
                            'password' => bcrypt('password'), // hanya dipakai saat insert
                            'nik'      => $tteUser->nik,
                            'eselon'           => $tteUser->eselon,
                            'akses_modul'      => $tteUser->akses_modul,
                            'is_ujikom'        => $tteUser->is_ujikom,
                            'is_sertifikat'    => $tteUser->is_sertifikat,
                            'is_bangkom'       => $tteUser->is_bangkom,
                            'is_skp'           => $tteUser->is_skp,
                            'is_bidang3'       => $tteUser->is_bidang3,
                            'is_aktif'         => $tteUser->is_aktif,
                        ]
                    );

                    $count++;
                }
            });

        return $count;
    }
}
