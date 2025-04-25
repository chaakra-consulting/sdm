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
        'keterangan',
        'upload',
        'is_done'
    ];
    protected $casts = [
        'upload' => 'array',
    ];
    public function project_perusahaan()
    {
        return $this->belongsTo(ProjectPerusahaan::class, 'project_perusahaan_id', 'id');
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
        return $this->belongsTo(TipeTask::class, 'tipe_tasks_id', 'id');
    }
    public function status_pengerjaan()
    {
        return $this->belongsTo(StatusPengerjaan::class, 'status_pengerjaans_id', 'id');
    }
}
