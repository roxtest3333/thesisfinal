<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Schedule;
use Spatie\Permission\Traits\HasRoles;



class User extends Authenticatable
{
    use HasRoles;

    
    protected $fillable = [
        'name',
        'faculty_id',
        'email',
        'password',
        'is_admin',
        'role',
    ];
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
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
    public function schedules()
    {
    return $this->hasMany(Schedule::class, 'student_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

}