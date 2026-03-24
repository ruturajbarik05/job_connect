<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::where('status', 'approved')
            ->where('is_active', true);

        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        if ($request->has('industry')) {
            $query->where('industry', $request->industry);
        }

        $companies = $query->withCount('activeJobs')
            ->latest()
            ->paginate(12);

        $industries = Company::where('status', 'approved')
            ->whereNotNull('industry')
            ->distinct()
            ->pluck('industry');

        return view('frontend.companies.index', compact('companies', 'industries'));
    }

    public function show($slug)
    {
        $company = Company::where('slug', $slug)
            ->where('status', 'approved')
            ->with('user')
            ->firstOrFail();

        $jobs = $company->activeJobs()
            ->with('category')
            ->latest()
            ->take(10)
            ->get();

        return view('frontend.companies.show', compact('company', 'jobs'));
    }
}
