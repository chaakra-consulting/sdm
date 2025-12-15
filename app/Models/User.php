<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Gaji;
use App\Models\Role;
use App\Models\DatadiriUser;
use App\Models\UsersProject;
use App\Models\PendidikanUser;
use App\Models\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNotAdminRole($query,$roleSlug)
    {
        return $query->whereHas('role', function ($query) use ($roleSlug) {
            $query->where('slug', $roleSlug);
        });
    }

    // Relasi many-to-many dengan Role
    public function gaji()
    {
        return $this->belongsTo(Gaji::class, 'role_id', 'id'); // 'role_id' adalah foreign key di tabel users
    }

    // Relasi many-to-many dengan Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id'); // 'role_id' adalah foreign key di tabel users
    }

    public function dataDiri()
    {
        return $this->hasOne(DatadiriUser::class, 'user_id', 'id');
    }

    public function pendidikan()
    {
        return $this->hasOne(PendidikanUser::class, 'user_id', 'id');
    }

    public function users_project()
    {
        return $this->hasMany(UsersProject::class, 'user_id', 'id');
    }

    public function socialMedias()
    {
        return $this->hasMany(SocialMedia::class, 'user_id', 'id');
    }

    public function users_task()
    {
        return $this->hasMany(UsersTask::class, 'user_id', 'id');
    }

    public function subtask()
    {
        return $this->hasMany(SubTask::class, 'user_id', 'id');
    }

    public function approveReports()
    {
        return $this->hasMany(DetailSubTask::class, 'approved_by', 'id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }
}
