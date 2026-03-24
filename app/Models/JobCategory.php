<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'category_id');
    }

    public function activeJobs()
    {
        return $this->hasMany(Job::class, 'category_id')->where('status', 'active');
    }

    public function getJobsCountAttribute(): int
    {
        return $this->activeJobs()->count();
    }
}
