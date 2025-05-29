<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisiLaporan extends Model
{
    protected $table = 'revisi_laporans';
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'pesan'
    ];
}
