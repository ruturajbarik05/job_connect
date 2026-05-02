<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Policies\ApplicationPolicy;
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
        Application::class => ApplicationPolicy::class,
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

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
