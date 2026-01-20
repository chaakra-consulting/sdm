<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CRMTicket extends Model
{
    protected $connection = 'db_crm';
    protected $table = 'tickets';
    protected $guarded = [];
}
