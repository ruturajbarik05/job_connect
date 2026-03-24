<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isRecruiter()) {
            $company = $user->company;

            if (! $company || $company->status !== 'approved') {
                if (! $request->routeIs('recruiter.company.*')) {
                    return redirect()->route('recruiter.company.profile')
                        ->with('warning', 'Your company profile must be approved before you can post jobs.');
                }
            }
        }

        return $next($request);
    }
}
