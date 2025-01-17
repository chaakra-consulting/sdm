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
        'data_kepegawaian_id',
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

    public function kepegawaian()
    {
        return $this->belongsTo(DataKepegawaian::class, 'user_id', 'user_id');
    }

    public function kesehatan()
    {
        return $this->belongsTo(DataKesehatan::class, 'user_id', 'user_id');
    }

    public function pendidikan()
    {
        return $this->belongsTo(PendidikanUser::class, 'user_id', 'user_id');
    }

    public function pengalamanKerjas()
    {
        return $this->hasMany(PengalamanKerja::class, 'user_id', 'user_id');
    }

    public function pelatihans()
    {
        return $this->hasMany(DataPelatihan::class, 'user_id', 'user_id');
    }

    public function socialMedias()
    {
        return $this->hasMany(SocialMedia::class, 'user_id', 'user_id');
    }
}
