<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LampiranSubTask extends Model
{
    protected $table = 'lampiran_sub_tasks';
    protected $guarded = ['id'];
    protected $fillable = [
        'id',
        'sub_task_id',
        'lampiran',
    ];
    public function sub_task()
    {
        return $this->belongsTo(SubTask::class, 'sub_task_id', 'id');
    }
}
