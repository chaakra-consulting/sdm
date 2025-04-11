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
        'tgl_sub_task',
        'durasi',
        'keterangan',
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
}
