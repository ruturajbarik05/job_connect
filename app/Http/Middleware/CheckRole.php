<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        $roleMap = [
            'admin' => 'isAdmin',
            'recruiter' => 'isRecruiter',
            'jobseeker' => 'isJobSeeker',
        ];

        $method = $roleMap[$role] ?? null;

        if (! $method || ! $user->$method()) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
