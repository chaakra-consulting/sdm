<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPelatihan extends Model
{
    //

    protected $fillable = [
        'user_id',
        'nama_pelatihan',
        'tujuan_pelatihan',
        'tahun_pelatihan',
        'nomor_sertifikat',
        'upload_sertifikat'
    ];
}
