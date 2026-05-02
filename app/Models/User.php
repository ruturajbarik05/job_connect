<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'avatar',
        'is_active',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function jobSeekerProfile()
    {
        return $this->hasOne(JobSeekerProfile::class);
    }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function savedJobs()
    {
        return $this->belongsToMany(Job::class, 'saved_jobs');
    }

    /**
     * App notifications (renamed to avoid conflict with Laravel Notifications).
     */
    public function appNotifications()
    {
        return $this->hasMany(AppNotification::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function postedJobs()
    {
        return $this->jobs();
    }

    public function isAdmin(): bool
    {
        return $this->role && $this->role->slug === 'admin';
    }

    public function isRecruiter(): bool
    {
        return $this->role && $this->role->slug === 'recruiter';
    }

    public function isJobSeeker(): bool
    {
        return $this->role && $this->role->slug === 'jobseeker';
    }

    public function isVerified(): bool
    {
        if ($this->isJobSeeker()) {
            return true;
        }

        return $this->company && $this->company->is_verified;
    }
}
