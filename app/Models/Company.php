<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'website',
        'logo',
        'banner',
        'industry',
        'company_size',
        'founded_year',
        'location',
        'address',
        'phone',
        'email',
        'is_verified',
        'is_active',
        'status',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (empty($company->slug) && ! empty($company->name)) {
                $company->slug = Str::slug($company->name) . '-' . Str::random(5);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function activeJobs()
    {
        return $this->hasMany(Job::class)->where('status', 'active');
    }

    public function getJobsCountAttribute(): int
    {
        return $this->jobs()->count();
    }

    /**
     * Fixed: properly count total applicants across all company jobs.
     */
    public function getTotalApplicantsAttribute(): int
    {
        return Application::whereIn('job_id', $this->jobs()->pluck('id'))->count();
    }
}
