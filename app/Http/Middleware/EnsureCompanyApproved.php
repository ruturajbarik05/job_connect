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
            if (! $user->company && ! $request->routeIs('recruiter.company.*')) {
                return redirect()->route('recruiter.company.profile')
                    ->with('warning', 'Please complete your company profile first.');
            }
        }

        return $next($request);
    }
}
