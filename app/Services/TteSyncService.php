<?php

namespace App\Services;

use App\Models\SuratTte;
use App\Models\SuratTteFiles;
use App\Models\SuratTteFilesTteNew;
use App\Models\SuratTteReviewers;
use App\Models\SuratTteReviewersTteNew;
use App\Models\SuratTteReviews;
use App\Models\SuratTteReviewsTteNew;
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
                    'modul_surat_id'   => $tteNew->esignmoduls_id,
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

    /**
     * Sinkronisasi seluruh data reviewer surat TTE
     * dari DB tte_new → DB utama
     *
     * @return int jumlah data yang diproses
     */
    public static function syncByAllReviewers(): int
    {
        $count = 0;

        SuratTteReviewersTteNew::chunk(100, function ($reviewers) use (&$count) {

            foreach ($reviewers as $reviewerNew) {

                // ===============================
                // VALIDASI TTE_ID HARUS ADA
                // ===============================
                $existsTte = SuratTte::where('id', $reviewerNew->tte_id)->exists();

                if (! $existsTte) {
                    // skip jika surat TTE belum ada
                    continue;
                }

                // ===============================
                // CEK DATA SUDAH ADA
                // ===============================
                $reviewer = SuratTteReviewers::where('id', $reviewerNew->id)->first();

                $payload = [
                    'tte_id'     => $reviewerNew->tte_id,
                    'eselon'     => $reviewerNew->eselon,
                    'review_by'  => $reviewerNew->review_by,
                    'created_at' => $reviewerNew->created_at,
                    'updated_at' => $reviewerNew->updated_at,
                    'deleted_at' => $reviewerNew->deleted_at,
                ];

                // ===============================
                // JIKA SUDAH ADA → UPDATE
                // ===============================
                if ($reviewer) {
                    $reviewer->update($payload);
                    $count++;
                    continue;
                }

                // ===============================
                // JIKA BELUM ADA → CREATE
                // ===============================
                SuratTteReviewers::create(array_merge([
                    'id' => $reviewerNew->id,
                ], $payload));

                $count++;
            }
        });

        return $count;
    }

    /**
     * Sinkronisasi seluruh data review surat TTE
     * dari DB tte_new → DB utama
     *
     * @return int
     */
    public static function syncByAllReviews(): int
    {
        $count = 0;

        // ===============================
        // AMBIL SEMUA TTE ID SEKALI
        // ===============================
        $tteIds = SuratTte::pluck('id')->flip();

        SuratTteReviewsTteNew::chunk(500, function ($reviews) use (&$count, $tteIds) {

            foreach ($reviews as $reviewNew) {

                // ===============================
                // VALIDASI TTE_ID (TANPA QUERY)
                // ===============================
                if (! isset($tteIds[$reviewNew->tte_id])) {
                    continue;
                }

                // ===============================
                // CEK DATA SUDAH ADA
                // ===============================
                $review = SuratTteReviews::find($reviewNew->id);

                $payload = [
                    'tte_id'                 => $reviewNew->tte_id,
                    'review_number'          => $reviewNew->review_number,
                    'note'                   => $reviewNew->note,
                    'stat'                   => $reviewNew->stat,
                    'review_by'              => $reviewNew->review_by,
                    'type'                   => $reviewNew->type,
                    'filterable'             => $reviewNew->filterable,
                    'is_reject_to_conceptor' => $reviewNew->is_reject_to_conceptor,
                    'reviewed_at'            => $reviewNew->reviewed_at,
                    'created_at'             => $reviewNew->created_at,
                    'updated_at'             => $reviewNew->updated_at,
                    'deleted_at'             => $reviewNew->deleted_at,
                ];

                // ===============================
                // UPDATE / INSERT
                // ===============================
                if ($review) {
                    $review->update($payload);
                } else {
                    SuratTteReviews::create(array_merge([
                        'id' => $reviewNew->id,
                    ], $payload));
                }

                $count++;
            }
        });

        return $count;
    }

    /**
     * Sinkronisasi seluruh file surat TTE
     * dari DB tte_new_service → DB utama
     *
     * @return int
     */
    public static function syncByAllFiles(): int
    {
        $count = 0;

        // ===============================
        // AMBIL SEMUA TTE ID SEKALI
        // ===============================
        $tteIds = SuratTte::pluck('id')->flip();

        SuratTteFilesTteNew::chunk(500, function ($files) use (&$count, $tteIds) {

            foreach ($files as $fileNew) {

                // ===============================
                // VALIDASI TTE_ID (TANPA QUERY)
                // ===============================
                if (! isset($tteIds[$fileNew->tte_id])) {
                    continue;
                }

                // ===============================
                // CEK DATA SUDAH ADA
                // ===============================
                $file = SuratTteFiles::find($fileNew->id);

                $payload = [
                    'tte_id'       => $fileNew->tte_id,
                    'name'         => $fileNew->name,
                    'signed_at'    => $fileNew->signed_at,
                    'signed_link'  => $fileNew->signed_link,
                    'signed_path'  => $fileNew->signed_path,
                    'unique_code'  => $fileNew->unique_code,
                    'mimetype_id'  => $fileNew->mimetype_id,
                    'created_at'   => $fileNew->created_at,
                    'updated_at'   => $fileNew->updated_at,
                    'deleted_at'   => $fileNew->deleted_at,
                ];

                // ===============================
                // UPDATE / INSERT
                // ===============================
                if ($file) {
                    $file->update($payload);
                } else {
                    SuratTteFiles::create(array_merge([
                        'id' => $fileNew->id,
                    ], $payload));
                }

                $count++;
            }
        });

        return $count;
    }
}
