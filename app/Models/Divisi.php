<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $table = 'divisis';

    protected $guarded = ['id'];
    
    protected $fillable =[
        'nama_divisi'
    ];
}
