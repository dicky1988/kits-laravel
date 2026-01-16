<?php

namespace App\Services;

use App\Models\EsignModule;
use App\Models\ModulSurat;
use App\Models\User;
use App\Models\UserTteNew;
use Illuminate\Support\Facades\Hash;

class ModulSuratSyncService
{
    public static function syncByAll(): int
    {
        $count = 0;

        EsignModule::chunk(100, function ($esignmoduls) use (&$count) {

                foreach ($esignmoduls as $esignmoduls) {

                    $modul_surat = ModulSurat::where('id', $esignmoduls->id)->first();

                    // ===============================
                    // JIKA USER SUDAH ADA
                    // ===============================
                    if ($modul_surat) {

                        // Update data user
                        $modul_surat->update([
                            'nama'           => $esignmoduls->namamodul,
                            'icon'           => $esignmoduls->icon,
                            'color'          => $esignmoduls->color,
                            'nomor_pj'       => $esignmoduls->nomor_pj,
                        ]);

                        $count++;
                        continue;
                    }

                    // ===============================
                    // JIKA USER BELUM ADA → CREATE
                    // ===============================
                    $modul_surat_new = ModulSurat::create([
                        'nama'           => $esignmoduls->namamodul,
                        'icon'           => $esignmoduls->icon,
                        'color'          => $esignmoduls->color,
                        'nomor_pj'       => $esignmoduls->nomor_pj,
                        'is_aktif'       => 1,
                    ]);

                    $count++;
                }
            });

        return $count;
    }
}
