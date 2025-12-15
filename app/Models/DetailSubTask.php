<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSubTask extends Model
{
    protected $table = 'detail_sub_tasks';
    protected $guarded = ['id'];
    protected $fillable = [
        'sub_task_id',
        'user_id',
        'tanggal',
        'keterangan',
        'durasi',
        'status',
        'is_active',
        'approved_by',
        'approved_at',
        'approval_notes',
        'submitted_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function subtask()
    {
        return $this->belongsTo(SubTask::class, 'sub_task_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}
