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
                            'email'    => $tteUser->email,
                            'nip'      => $tteUser->nip,
                            'nip_lama'   => $tteUser->pegawai_id,
                            'password' => bcrypt('password'), // hanya dipakai saat insert
                        ]
                    );

                    $count++;
                }
            });

        return $count;
    }
}
