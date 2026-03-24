<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = JobCategory::withCount(['activeJobs' => function ($query) {
            $query->where('status', 'active');
        }])->get();

        return view('frontend.categories.index', compact('categories'));
    }

    public function show($slug)
    {
        $category = JobCategory::where('slug', $slug)->firstOrFail();

        $jobs = $category->activeJobs()
            ->with(['company', 'category'])
            ->latest()
            ->paginate(12);

        return view('frontend.categories.show', compact('category', 'jobs'));
    }
}
