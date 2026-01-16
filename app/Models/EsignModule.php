<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EsignModule extends Model
{
    protected $connection = 'tte_new_service';
    protected $table = 'esignmoduls'; // sesuaikan nama tabel
    protected $primaryKey = 'id';
    public $timestamps = false;
}
