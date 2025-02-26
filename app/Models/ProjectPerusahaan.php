<?php

namespace App\Models;

use App\Models\Perusahaan;
use Illuminate\Database\Eloquent\Model;

class ProjectPerusahaan extends Model
{
    protected $table = 'tb_project_perusahaans';
    protected $guarded = ['id'];

    public function perusahaan() 
    {
        return $this->belongsTo(Perusahaan::class,'perusahaan_id','id');
    }
    public function project_users()
    {
        return $this->hasMany(UsersProject::class, 'project_perusahaan_id');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_perusahaan_id');
    }
}