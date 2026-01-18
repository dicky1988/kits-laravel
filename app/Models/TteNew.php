<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TteNew extends Model
{
    use SoftDeletes;

    protected $connection = 'tte_new_service';

    /**
     * Nama tabel
     */
    protected $table = 'ttes';

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
        'tte_template_id',
        'tte_draft_id',
        'esignmoduls_id',
        'number',
        'name',
        'unitCode',
        'upload_date',
        'signed_by',
        'unique_code',
        'qr_location_stat',
        'qr_location_page',
        'created_by',
        'phone_number',
        'stat',
        'reviu_last',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'tte_template_id'   => 'integer',
        'tte_draft_id'      => 'integer',
        'esignmoduls_id'    => 'integer',
        'upload_date'       => 'datetime',
        'signed_by'         => 'integer',
        'qr_location_stat'  => 'integer',
        'qr_location_page'  => 'integer',
        'created_by'        => 'integer',
        'stat'              => 'integer',
        'reviu_last'        => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];
}
