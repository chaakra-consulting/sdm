<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPengerjaan extends Model
{
    protected $id = ['id'];
    protected $table = 'status_pengerjaans';

    public function task()
    {
        return $this->hasMany(Task::class, 'status_pengerjaans_id', 'id');
    }
    public function project_perusahaan()
    {
        return $this->hasMany(ProjectPerusahaan::class, 'status_pengerjaans_id', 'id');
    }
}
