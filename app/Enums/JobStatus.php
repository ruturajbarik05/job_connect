<?php

namespace App\Enums;

enum JobStatus: string
{
    case Active = 'active';
    case Pending = 'pending';
    case Closed = 'closed';
    case Draft = 'draft';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Pending => 'Pending',
            self::Closed => 'Closed',
            self::Draft => 'Draft',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Active => 'badge-success',
            self::Pending => 'badge-warning',
            self::Closed => 'badge-danger',
            self::Draft => 'badge-secondary',
        };
    }
}
