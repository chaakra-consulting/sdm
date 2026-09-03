<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCRM extends Model
{
    protected $connection = 'db_crm';
    protected $table = 'projects';
    protected $guarded = [];
}
