<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSeekerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'date_of_birth',
        'gender',
        'summary',
        'resume',
        'avatar',
        'linkedin_url',
        'portfolio_url',
        'skills',
        'languages',
        'experience_level',
        'expected_salary',
        'salary_currency',
        'employment_type_preference',
        'is_active',
        'views',
    ];

    protected $casts = [
        'skills' => 'array',
        'languages' => 'array',
        'date_of_birth' => 'date',
        'expected_salary' => 'decimal:2',
        'is_active' => 'boolean',
        'views' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getLocationAttribute(): string
    {
        $parts = array_filter([$this->city, $this->state, $this->country]);

        return implode(', ', $parts);
    }

    public function applications()
    {
        return $this->user->applications();
    }

    public function savedJobs()
    {
        return $this->user->savedJobs();
    }
}
