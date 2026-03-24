<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Job;
use App\Policies\CompanyPolicy;
use App\Policies\JobPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Job::class => JobPolicy::class,
        Company::class => CompanyPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}
