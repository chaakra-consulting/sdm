<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersTask extends Model
{
    protected $table = 'tb_users_tasks';
    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'task_id'];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function subtask()
    {
        return $this->hasMany(SubTask::class, 'task_id', 'task_id');
    }
}
