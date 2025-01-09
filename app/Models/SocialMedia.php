<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    //
    protected $fillable = [
        'user_id',
        'nama_social_media',
        'link',
    ];
}
