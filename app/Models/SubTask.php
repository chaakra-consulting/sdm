<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    protected $table = 'sub_tasks';
    protected $guarded = ['id'];
    protected $fillable = [
        'id',
        'task_id',
        'user_id',
        'user_task_id',
        'nama_subtask',
        'tgl_sub_task',
        'tgl_selesai',
        'deadline',
        'status',
        'revisi_id',
    ]; 
    protected $casts = [
        'upload' => 'array',
    ];
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function lampiran()
    {
        return $this->hasMany(LampiranSubTask::class, 'sub_task_id', 'id');
    }
    public function revisi()
    {
        return $this->belongsTo(RevisiLaporan::class, 'revisi_laporan_id');
    }
    public function users_task()
    {
        return $this->belongsTo(UsersTask::class, 'user_task_id', 'id');
    }
    public function detail_sub_task()
    {
        return $this->hasMany(DetailSubTask::class, 'sub_task_id', 'id');
    }
}
