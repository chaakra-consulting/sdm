<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tb_tasks';
    protected $guarded = ['id'];
    protected $fillable = [
        'id',
        'tipe_tasks_id',
        'project_perusahaan_id',
        'user_id',
        'nama_task',
        'tgl_task',
        'deadline',
        'tgl_selesai',
        'status',
        'keterangan',
        'upload',
        'is_done'
    ];
    protected $casts = [
        'upload' => 'array',
        'status' => 'string',
    ];
    public function project_perusahaan()
    {
        return $this->belongsTo(ProjectPerusahaan::class, 'project_perusahaan_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function users_task()
    {
        return $this->hasMany(UsersTask::class, 'task_id', 'id');
    }
    public function tipe_task()
    {
        return $this->belongsTo(TipeTask::class, 'tipe_tasks_id');
    }
    public function sub_task()
    {
        return $this->hasMany(SubTask::class, 'task_id', 'id');
    }
}
