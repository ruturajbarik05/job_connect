<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Applied = 'applied';
    case Viewed = 'viewed';
    case Shortlisted = 'shortlisted';
    case Interview = 'interview';
    case Offer = 'offer';
    case Rejected = 'rejected';
    case Withdrawn = 'withdrawn';

    public function label(): string
    {
        return match ($this) {
            self::Applied => 'Applied',
            self::Viewed => 'Viewed',
            self::Shortlisted => 'Shortlisted',
            self::Interview => 'Interview',
            self::Offer => 'Offer',
            self::Rejected => 'Rejected',
            self::Withdrawn => 'Withdrawn',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Applied => 'badge-primary',
            self::Viewed => 'badge-info',
            self::Shortlisted => 'badge-success',
            self::Interview => 'badge-warning',
            self::Offer => 'badge-success',
            self::Rejected => 'badge-danger',
            self::Withdrawn => 'badge-secondary',
        };
    }
}
