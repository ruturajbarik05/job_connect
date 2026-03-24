<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Company $company): bool
    {
        return $company->status === 'approved' || $user->id === $company->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isRecruiter();
    }

    public function update(User $user, Company $company): bool
    {
        return $user->id === $company->user_id;
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->id === $company->user_id || $user->isAdmin();
    }
}
