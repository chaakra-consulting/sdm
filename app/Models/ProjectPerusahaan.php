<?php

namespace App\Models;

use App\Models\Perusahaan;
use Illuminate\Database\Eloquent\Model;

class ProjectPerusahaan extends Model
{
    protected $table = 'tb_project_perusahaans';
    protected $guarded = ['id'];
    protected $fillable = [
        'perusahaan_id',
        'nama_project',
        'status',
        'waktu_mulai',
        'waktu_berakhir',
        'deadline',
        'progres'
    ];
    protected $casts = [
        'status' => 'string',
        'waktu_mulai' => 'date',
        'waktu_berakhir' => 'date',
        'deadline' => 'date',
        'progres' => 'double',
    ];
    protected $attributes = [
        'status' => 'belum',
        'progres' => 0,
    ];

    public function calculateProgress()
    {
        $total = $this->tasks()->count();
        if($total == 0) return 0;
        
        $completed = $this->tasks()
            ->where('status', 'selesai')
            ->count();
            
        return round(($completed / $total) * 100, 2);
    }

    public function getProgressAttribute($value)
    {
        $calculated = $this->calculateProgress();

        if ($value != $calculated) {
            $this->update(['progres' => $calculated]);
        }
        return $calculated;
    }

    public function perusahaan() 
    {
        return $this->belongsTo(Perusahaan::class,'perusahaan_id','id');
    }
    public function project_users()
    {
        return $this->hasMany(UsersProject::class, 'project_perusahaan_id');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_perusahaan_id', 'id');
    }
}