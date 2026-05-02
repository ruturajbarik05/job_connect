<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Job seeker can view their own application.
     * Recruiter can view applications for their posted jobs.
     * Admin can view all.
     */
    public function view(User $user, Application $application): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->id === $application->user_id) {
            return true;
        }

        // Recruiter owns the job
        return $user->jobs()->where('id', $application->job_id)->exists();
    }

    /**
     * Only the recruiter who owns the job can update application status.
     */
    public function update(User $user, Application $application): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->jobs()->where('id', $application->job_id)->exists();
    }

    /**
     * Only the applicant can withdraw their application.
     */
    public function withdraw(User $user, Application $application): bool
    {
        return $user->id === $application->user_id;
    }
}
