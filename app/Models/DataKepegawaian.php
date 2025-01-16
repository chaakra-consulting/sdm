<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataKepegawaian extends Model
{
    protected $table = 'data_kepegawaians';

    protected $fillable = [
        'user_id',
        'sub_jabatan_id',
        'status_pekerjaan_id',
        'tgl_masuk',
        'tgl_berakhir',
        'no_npwp'
    ];

    public function subJabatan()
    {
        return $this->belongsTo(SubJabatan::class, 'sub_jabatan_id', 'id');
    }

    public function statusPekerjaan()
    {
        return $this->belongsTo(DataStatusPekerjaan::class, 'status_pekerjaan_id', 'id');
    }
}
