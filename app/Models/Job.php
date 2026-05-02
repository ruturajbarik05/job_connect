<?php

namespace App\Models;

use App\Enums\JobStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'category_id',
        'title',
        'slug',
        'description',
        'requirements',
        'benefits',
        'responsibilities',
        'location',
        'address',
        'job_type',
        'work_mode',
        'experience_level',
        'salary_min',
        'salary_max',
        'salary_currency',
        'salary_type',
        'skills_required',
        'keywords',
        'vacancies',
        'application_deadline',
        'expiry_date',
        'status',
        'is_featured',
        'is_verified',
        'views',
        'applications_count',
    ];

    protected $casts = [
        'skills_required' => 'array',
        'keywords' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'views' => 'integer',
        'applications_count' => 'integer',
        'vacancies' => 'integer',
        'application_deadline' => 'date',
        'expiry_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title) . '-' . Str::random(5);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_jobs');
    }

    public function scopeActive($query)
    {
        return $query->where('status', JobStatus::Active->value);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getSalaryRangeAttribute(): string
    {
        if (! $this->salary_min && ! $this->salary_max) {
            return 'Salary not specified';
        }

        $min = $this->salary_min ? number_format($this->salary_min, 0) : '';
        $max = $this->salary_max ? number_format($this->salary_max, 0) : '';

        if ($min && $max) {
            return "{$this->salary_currency} {$min} - {$max}";
        }

        return $this->salary_currency . ' ' . ($min ?: $max);
    }

    public function getDaysAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Increment views with session-based deduplication.
     */
    public function incrementViewsOnce(): void
    {
        $sessionKey = 'job_viewed_' . $this->id;

        if (! session()->has($sessionKey)) {
            $this->increment('views');
            session()->put($sessionKey, true);
        }
    }

    public function incrementApplications(): void
    {
        $this->increment('applications_count');
    }

    public function isDeadlinePassed(): bool
    {
        return $this->application_deadline && $this->application_deadline->isPast();
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
