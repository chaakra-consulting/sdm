<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'tb_m_perusahaans';

    protected $guarded = ['id'];

    public function projects()
    {
        return $this->hasMany(ProjectPerusahaan::class, 'perusahaan_id');
    }
}
