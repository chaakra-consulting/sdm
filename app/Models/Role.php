<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi
    protected $fillable = ['name'];

    // Relasi many-to-many dengan User
    public function users()
    {
        return $this->hasMany(User::class, 'role'); // 'role_id' adalah foreign key di tabel users
    }
}
