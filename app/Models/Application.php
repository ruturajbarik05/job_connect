<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

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
        if ($this->status === ApplicationStatus::Applied->value) {
            $this->update(['status' => ApplicationStatus::Viewed->value]);
        }
    }

    public function getStatusBadgeClassAttribute(): string
    {
        $status = ApplicationStatus::tryFrom($this->status);

        return $status ? $status->badgeClass() : 'badge-secondary';
    }

    public function getStatusLabelAttribute(): string
    {
        $status = ApplicationStatus::tryFrom($this->status);

        return $status ? $status->label() : ucfirst($this->status);
    }
}
