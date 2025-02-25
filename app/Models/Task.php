<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tb_tasks';
    protected $guarded = ['id'];
    protected $fillable = [
        'id', 
        'project_perusahaan_id', 
        'user_id', 
        'nama_task',
        'tgl_task',
        'keterangan',
        'upload'
    ];
    protected $casts = [
        'upload' => 'array',
    ];
}
