<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratTteReviewers extends Model
{
    use SoftDeletes;

    /**
     * Nama tabel
     */
    protected $table = 'surat_tte_reviewers';

    /**
     * Primary Key
     */
    protected $primaryKey = 'id';

    /**
     * PK auto increment
     */
    public $incrementing = true;

    /**
     * Tipe PK
     */
    protected $keyType = 'int';

    /**
     * Timestamp aktif
     */
    public $timestamps = true;

    /**
     * Kolom yang boleh di-mass assign
     */
    protected $fillable = [
        'tte_id',
        'eselon',
        'review_by',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'id'        => 'integer',
        'eselon'    => 'integer',
        'review_by' => 'integer',
        'created_at'=> 'datetime',
        'updated_at'=> 'datetime',
        'deleted_at'=> 'datetime',
    ];

    /**
     * Relasi ke Surat TTE
     */
    public function suratTte()
    {
        return $this->belongsTo(SuratTte::class, 'tte_id', 'id');
    }
}
