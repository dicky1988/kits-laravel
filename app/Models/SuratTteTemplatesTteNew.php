<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratTteTemplatesTteNew extends Model
{
    use SoftDeletes;

    protected $connection = 'tte_new';

    /**
     * Nama tabel
     */
    protected $table = 'surat_tte_templates';

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
        'name',
        'stat',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'id'         => 'integer',
        'stat'       => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Scope template aktif
     */
    public function scopeActive($query)
    {
        return $query->where('stat', 1);
    }
}
