<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_id',
        'cover_letter',
        'resume',
        'portfolio_url',
        'status',
        'notes',
        'applied_at',
        'reviewed_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public const STATUS_APPLIED = 'applied';

    public const STATUS_VIEWED = 'viewed';

    public const STATUS_SHORTLISTED = 'shortlisted';

    public const STATUS_INTERVIEW = 'interview';

    public const STATUS_OFFER = 'offer';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_WITHDRAWN = 'withdrawn';

    public static $statuses = [
        self::STATUS_APPLIED => 'Applied',
        self::STATUS_VIEWED => 'Viewed',
        self::STATUS_SHORTLISTED => 'Shortlisted',
        self::STATUS_INTERVIEW => 'Interview',
        self::STATUS_OFFER => 'Offer',
        self::STATUS_REJECTED => 'Rejected',
        self::STATUS_WITHDRAWN => 'Withdrawn',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function getCandidateAttribute()
    {
        return $this->user;
    }

    public function getCandidateProfileAttribute()
    {
        return $this->user->jobSeekerProfile;
    }

    public function markAsViewed(): void
    {
        if ($this->status === self::STATUS_APPLIED) {
            $this->update(['status' => self::STATUS_VIEWED]);
        }
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_APPLIED => 'badge-primary',
            self::STATUS_VIEWED => 'badge-info',
            self::STATUS_SHORTLISTED => 'badge-success',
            self::STATUS_INTERVIEW => 'badge-warning',
            self::STATUS_OFFER => 'badge-success',
            self::STATUS_REJECTED => 'badge-danger',
            self::STATUS_WITHDRAWN => 'badge-secondary',
            default => 'badge-secondary',
        };
    }
}
