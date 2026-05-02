<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::active()->with(['company', 'category']);

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('type')) {
            $query->where('job_type', $request->type);
        }

        if ($request->has('mode')) {
            $query->where('work_mode', $request->mode);
        }

        $jobs = $query->latest()->paginate(12);

        return view('frontend.jobs.index', compact('jobs'));
    }

    public function show($slug)
    {
        $job = Job::where('slug', $slug)
            ->with(['company', 'category', 'user'])
            ->firstOrFail();

        if ($job->status !== 'active' && ! auth()->check()) {
            abort(404);
        }

        $job->incrementViewsOnce();

        $relatedJobs = Job::active()
            ->where('id', '!=', $job->id)
            ->where(function ($query) use ($job) {
                $query->where('category_id', $job->category_id)
                    ->orWhere('job_type', $job->job_type)
                    ->orWhere('location', $job->location);
            })
            ->take(4)
            ->get();

        $hasApplied = auth()->check()
            ? $job->applications()->where('user_id', auth()->id())->exists()
            : false;

        $isSaved = auth()->check()
            ? $job->savedByUsers()->where('user_id', auth()->id())->exists()
            : false;

        return view('frontend.jobs.show', compact('job', 'relatedJobs', 'hasApplied', 'isSaved'));
    }

    public function byCompany($slug)
    {
        $company = Company::where('slug', $slug)
            ->with('activeJobs')
            ->firstOrFail();

        $jobs = $company->activeJobs()
            ->with(['category'])
            ->latest()
            ->paginate(12);

        return view('frontend.jobs.by-company', compact('company', 'jobs'));
    }

    public function byCategory($slug)
    {
        $category = JobCategory::where('slug', $slug)->firstOrFail();

        $jobs = Job::active()
            ->where('category_id', $category->id)
            ->with(['company', 'category'])
            ->latest()
            ->paginate(12);

        return view('frontend.jobs.by-category', compact('category', 'jobs'));
    }
}
