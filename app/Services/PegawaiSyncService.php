<?php

namespace App\Services;

use App\Models\EsignModule;
use App\Models\ModulSurat;
use App\Models\Pegawai;
use App\Models\PegawaiTteNew;
use App\Models\User;
use App\Models\UserTteNew;
use Illuminate\Support\Facades\Hash;

class PegawaiSyncService
{
    /**
     * Sinkronisasi seluruh data pegawai
     * dari DB tte_new → DB utama
     *
     * @return int jumlah data yang diproses
     */
    public static function syncByAll(): int
    {
        $count = 0;

        PegawaiTteNew::chunk(100, function ($pegawais) use (&$count) {

            foreach ($pegawais as $pegawaiTte) {

                // ===============================
                // CEK DATA SUDAH ADA
                // ===============================
                $pegawai = Pegawai::where('pegawaiID', $pegawaiTte->pegawaiID)->first();

                $payload = [
                    'nik'                         => $pegawaiTte->nik,
                    'pegawaiName'                => $pegawaiTte->pegawaiName,
                    'pegawaiNIP'                 => $pegawaiTte->pegawaiNIP,
                    'pegawaiNIPLama'             => $pegawaiTte->pegawaiNIPLama,
                    'pegawaiUnit'                => $pegawaiTte->pegawaiUnit,
                    'pegawaiUnitName'            => $pegawaiTte->pegawaiUnitName,
                    's_kd_instansiunitorg'       => $pegawaiTte->s_kd_instansiunitorg,
                    's_nama_instansiunitorg'     => $pegawaiTte->s_nama_instansiunitorg,
                    's_kd_instansiunitkerjal1'   => $pegawaiTte->s_kd_instansiunitkerjal1,
                    's_nama_instansiunitkerjal1' => $pegawaiTte->s_nama_instansiunitkerjal1,
                    's_kd_instansiunitkerjal2'   => $pegawaiTte->s_kd_instansiunitkerjal2,
                    's_nama_instansiunitkerjal2' => $pegawaiTte->s_nama_instansiunitkerjal2,
                    's_kd_instansiunitkerjal3'   => $pegawaiTte->s_kd_instansiunitkerjal3,
                    's_nama_instansiunitkerjal3' => $pegawaiTte->s_nama_instansiunitkerjal3,
                    's_kd_jabdetail'             => $pegawaiTte->s_kd_jabdetail,
                    'jabatan'                    => $pegawaiTte->jabatan,
                    'eselon'                     => $pegawaiTte->eselon,
                    'isPusdiklatwas'             => $pegawaiTte->isPusdiklatwas,
                    'isPusbinjfa'                => $pegawaiTte->isPusbinjfa,
                ];

                // ===============================
                // JIKA SUDAH ADA → UPDATE
                // ===============================
                if ($pegawai) {
                    $pegawai->update($payload);
                    $count++;
                    continue;
                }

                // ===============================
                // JIKA BELUM ADA → CREATE
                // ===============================
                Pegawai::create(array_merge([
                    'pegawaiID' => $pegawaiTte->pegawaiID,
                ], $payload));

                $count++;
            }
        });

        return $count;
    }
}
