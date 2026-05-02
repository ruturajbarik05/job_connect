<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $featuredJobs = Job::active()
            ->featured()
            ->with(['company', 'category'])
            ->latest()
            ->take(6)
            ->get();

        $recentJobs = Job::active()
            ->with(['company', 'category'])
            ->latest()
            ->take(8)
            ->get();

        $categories = Cache::remember('homepage_categories', 600, function () {
            return JobCategory::withCount(['activeJobs' => function ($query) {
                $query->where('status', 'active');
            }])->get();
        });

        $totalJobs = Cache::remember('homepage_total_jobs', 300, function () {
            return Job::active()->count();
        });

        $totalCompanies = Cache::remember('homepage_total_companies', 300, function () {
            return Company::where('status', 'approved')->count();
        });

        return view('frontend.home.index', compact(
            'featuredJobs',
            'recentJobs',
            'categories',
            'totalJobs',
            'totalCompanies'
        ));
    }

    public function search(Request $request)
    {
        $query = Job::active()->with(['company', 'category']);

        if ($request->has('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        if ($request->has('work_mode')) {
            $query->where('work_mode', $request->work_mode);
        }

        if ($request->has('experience_level')) {
            $query->where('experience_level', $request->experience_level);
        }

        if ($request->has('location')) {
            $location = str_replace(['%', '_'], ['\\%', '\\_'], $request->location);
            $query->where('location', 'LIKE', "%{$location}%");
        }

        if ($request->has('min_salary')) {
            $query->where('salary_max', '>=', $request->min_salary);
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->latest();
                    break;
                case 'salary_high':
                    $query->orderBy('salary_max', 'desc');
                    break;
                case 'salary_low':
                    $query->orderBy('salary_min', 'asc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $jobs = $query->paginate(12)->withQueryString();
        $categories = JobCategory::all();

        return view('frontend.jobs.search', compact('jobs', 'categories'));
    }
}
