<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengalamanKerja extends Model
{
    //
    protected $table = 'tb_data_pengalaman_kerjas';


    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'tgl_mulai',
        'tgl_selesai',
        'jabatan_akhir',
        'alasan_keluar',
        'no_hp_referensi',
        'upload_surat_referensi'
    ];
    
}
