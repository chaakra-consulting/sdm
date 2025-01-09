<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendidikanUser extends Model
{

    use HasFactory;
    protected $table = 'tb_data_pendidikans';
    protected $fillable = [
        'user_id',
        'nama_sekolah',
        'jurusan_sekolah',
        'alamat_sekolah',
        'tahun_mulai',
        'tahun_lulus',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}


