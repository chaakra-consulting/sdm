<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPerusahaanEksternal extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $connection = 'db_drive';
    protected $table = 'data_project';
    protected $guarded = [];
}
