<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersProject extends Model
{
    protected $table = 'tb_users_projects';
    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'project_perusahaan_id'];

    public function project_perusahaan()
    {
        return $this->belongsTo(ProjectPerusahaan::class, 'project_perusahaan_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
