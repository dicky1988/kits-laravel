<?php

namespace App\Services;

use App\Models\SuratTte;
use App\Models\TteNew;

class TteSyncService
{
    /**
     * Sinkronisasi seluruh data surat TTE
     * dari DB tte_new → DB utama
     *
     * @return int jumlah data yang diproses
     */
    public static function syncByAll(): int
    {
        $count = 0;

        TteNew::chunk(100, function ($surats) use (&$count) {

            foreach ($surats as $tteNew) {

                // ===============================
                // CEK DATA SUDAH ADA
                // ===============================
                $surat = SuratTte::where('id', $tteNew->id)->first();

                $payload = [
                    'tte_template_id'  => $tteNew->tte_template_id,
                    'tte_draft_id'     => $tteNew->tte_draft_id,
                    'esignmoduls_id'   => $tteNew->esignmoduls_id,
                    'number'           => $tteNew->number,
                    'name'             => $tteNew->name,
                    'unitCode'         => $tteNew->unitCode,
                    'upload_date'      => $tteNew->upload_date,
                    'signed_by'        => $tteNew->signed_by,
                    'unique_code'      => $tteNew->unique_code,
                    'qr_location_stat' => $tteNew->qr_location_stat,
                    'qr_location_page' => $tteNew->qr_location_page,
                    'created_by'       => $tteNew->created_by,
                    'phone_number'     => $tteNew->phone_number,
                    'stat'             => $tteNew->stat,
                    'reviu_last'       => $tteNew->reviu_last,
                ];

                // ===============================
                // JIKA SUDAH ADA → UPDATE
                // ===============================
                if ($surat) {
                    $surat->update($payload);
                    $count++;
                    continue;
                }

                // ===============================
                // JIKA BELUM ADA → CREATE
                // ===============================
                SuratTte::create(array_merge([
                    'id' => $tteNew->id,
                ], $payload));

                $count++;
            }
        });

        return $count;
    }
}
