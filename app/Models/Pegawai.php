<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    /**
     * Primary Key
     */
    protected $primaryKey = 'pegawaiID';

    /**
     * PK bukan auto increment
     */
    public $incrementing = false;

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
        'pegawaiID',
        'nik',
        'pegawaiName',
        'pegawaiNIP',
        'pegawaiNIPLama',
        'pegawaiUnit',
        'pegawaiUnitName',
        's_kd_instansiunitorg',
        's_nama_instansiunitorg',
        's_kd_instansiunitkerjal1',
        's_nama_instansiunitkerjal1',
        's_kd_instansiunitkerjal2',
        's_nama_instansiunitkerjal2',
        's_kd_instansiunitkerjal3',
        's_nama_instansiunitkerjal3',
        's_kd_jabdetail',
        'jabatan',
        'eselon',
        'isPusdiklatwas',
        'isPusbinjfa',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'pegawaiID'       => 'integer',
        'eselon'          => 'integer',
        'isPusdiklatwas'  => 'boolean',
        'isPusbinjfa'     => 'boolean',
    ];
}
