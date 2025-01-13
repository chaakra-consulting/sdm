<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatadiriUser extends Model
{
    use HasFactory;


    protected $table = 'tb_datadiris';


    protected $fillable = [
        'nik',
        'nama_lengkap',
        'nip',
        'user_id',
        'foto_user',
        'foto_ktp',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_ktp',
        'email_nonchaakra',
        'alamat_domisili',
        'agama',
        'jenis_kelamin',
        'no_hp',
        'hubungan_emergency',
        'nama_emergency',
        'no_emergency',
        'status_pernikahan'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
