<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataKesehatan extends Model
{
    protected $table = 'data_kesehatans';

    protected $fillable = [
        'user_id',
        'golongan_darah',
        'riwayat_alergi',
        'riwayat_penyakit',
        'riwayat_penyakit_lain'
    ];
}
