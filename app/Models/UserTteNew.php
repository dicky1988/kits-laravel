<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTteNew extends Model
{
    protected $connection = 'tte_new';
    protected $table = 'users'; // sesuaikan nama tabel
    protected $primaryKey = 'id';
    public $timestamps = false;
}
