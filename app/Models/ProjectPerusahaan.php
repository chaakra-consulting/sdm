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
        return $this->belongsTo(Perusahaan::class, 'id');
    }
}