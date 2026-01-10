<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataStatusPekerjaan extends Model
{
    protected $table = 'data_status_pekerjaans';
    protected $fillable = [
        'nama_status_pekerjaan',
        'slug',
    ];
}
