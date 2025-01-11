<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataKepegawaian extends Model
{
    //
    protected $fillable = [
        'user_id',
        'sub_jabatan_id',
        'status_pekerjaan_id',
        'tgl_masuk',
        'tgl_berakhir',
        'no_npwp'
    ];
}
