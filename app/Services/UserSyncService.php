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

                    // ===============================
                    // JIKA USER SUDAH ADA
                    // ===============================
                    if ($user) {

                        // 🚫 Skip jika is_sync = 0
                        if ((int) $user->is_sync === 0) {
                            continue;
                        }

                        // Update data user
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

                        // ===============================
                        // SYNC ROLE (SETELAH USER ADA)
                        // ===============================
                        switch ((int) $tteUser->role) {

                            case 99:
                                if ($tteUser->pegawai_id === 201000043) {
                                    $user->syncRoles([
                                        'superadmin',
                                        'adminapp',
                                        'user',
                                        'pegawai',
                                        'sekretaris',
                                        'struktural'
                                    ]);
                                } else {
                                    $user->syncRoles(['superadmin']);
                                }
                                break;

                            case 1:
                                $user->syncRoles(['struktural']);
                                break;

                            case 2:
                                $user->syncRoles(['pegawai']);
                                break;

                            case 26:
                                $user->syncRoles(['sekretaris']);
                                break;
                        }

                        $count++;
                        continue;
                    }

                    // ===============================
                    // JIKA USER BELUM ADA → CREATE
                    // ===============================
                    $user = User::create([
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
                        'is_sync'        => 1,
                    ]);

                    // ===============================
                    // SYNC ROLE UNTUK USER BARU
                    // ===============================
                    switch ((int) $tteUser->role) {

                        case 99:
                            $user->syncRoles(['superadmin']);
                            break;

                        case 1:
                            $user->syncRoles(['struktural']);
                            break;

                        case 2:
                            $user->syncRoles(['pegawai']);
                            break;

                        case 26:
                            $user->syncRoles(['sekretaris']);
                            break;
                    }

                    $count++;
                }
            });

        return $count;
    }
}
