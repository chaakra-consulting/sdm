<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeTask extends Model
{
    protected $table = 'tb_tipe_tasks';
    protected $guarded = ['id'];
    protected $fillable = [
        'id', 
        'nama_tipe',
        'slug',
    ];

    public function task()
    {
        return $this->hasMany(Task::class);
    }
}
