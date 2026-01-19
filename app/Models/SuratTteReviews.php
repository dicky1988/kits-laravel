<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratTteReviews extends Model
{
    use SoftDeletes;

    /**
     * Nama tabel
     */
    protected $table = 'surat_tte_reviews';

    /**
     * Primary Key
     */
    protected $primaryKey = 'id';

    /**
     * PK bukan auto increment
     */
    public $incrementing = false;

    /**
     * Tipe PK (char(26))
     */
    protected $keyType = 'string';

    /**
     * Timestamp aktif
     */
    public $timestamps = true;

    /**
     * Kolom yang boleh di-mass assign
     */
    protected $fillable = [
        'id',
        'tte_id',
        'review_number',
        'note',
        'stat',
        'review_by',
        'type',
        'filterable',
        'is_reject_to_conceptor',
        'reviewed_at',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'review_number'             => 'integer',
        'stat'                      => 'integer',
        'review_by'                 => 'integer',
        'type'                      => 'integer',
        'filterable'                => 'boolean',
        'is_reject_to_conceptor'    => 'boolean',
        'reviewed_at'               => 'datetime',
        'created_at'                => 'datetime',
        'updated_at'                => 'datetime',
        'deleted_at'                => 'datetime',
    ];

    /**
     * Relasi ke Surat TTE
     */
    public function suratTte()
    {
        return $this->belongsTo(SuratTte::class, 'tte_id', 'id');
    }
}
