<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratTteFiles extends Model
{
    use SoftDeletes;

    /**
     * Nama tabel
     */
    protected $table = 'surat_tte_files';

    /**
     * Primary Key
     */
    protected $primaryKey = 'id';

    /**
     * Auto increment aktif
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
        'name',
        'signed_at',
        'signed_link',
        'signed_path',
        'unique_code',
        'mimetype_id',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'id'          => 'integer',
        'signed_at'   => 'datetime',
        'mimetype_id' => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    /**
     * Relasi ke Surat TTE
     */
    public function suratTte()
    {
        return $this->belongsTo(SuratTte::class, 'tte_id', 'id');
    }

    /**
     * Relasi ke Mimetype (opsional)
     */
    /*public function mimetype()
    {
        return $this->belongsTo(Mimetype::class, 'mimetype_id');
    }*/
}
