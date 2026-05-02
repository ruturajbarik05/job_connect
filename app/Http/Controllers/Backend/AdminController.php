<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\CompanyApproved;
use App\Models\AdminActivityLog;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\User;
use App\Services\AdminLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function __construct(
        private AdminLogService $adminLogService
    ) {}

    public function dashboard()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalRecruiters' => User::whereHas('role', fn ($q) => $q->where('slug', 'recruiter'))->count(),
            'totalJobSeekers' => User::whereHas('role', fn ($q) => $q->where('slug', 'jobseeker'))->count(),
            'totalJobs' => Job::count(),
            'activeJobs' => Job::where('status', 'active')->count(),
            'totalApplications' => Application::count(),
            'totalCompanies' => Company::count(),
            'pendingCompanies' => Company::where('status', 'pending')->count(),
        ];

        $recentUsers = User::with('role')
            ->latest()
            ->take(5)
            ->get();

        $recentJobs = Job::with('company')
            ->latest()
            ->take(5)
            ->get();

        $recentApplications = Application::with(['user', 'job'])
            ->latest()
            ->take(5)
            ->get();

        return view('backend.admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentJobs',
            'recentApplications'
        ));
    }

    public function users(Request $request)
    {
        $query = User::with('role');

        if ($request->has('role') && $request->role !== 'all') {
            $query->whereHas('role', fn ($q) => $q->where('slug', $request->role));
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%'.str_replace(['%', '_'], ['\\%', '\\_'], $search).'%')
                    ->orWhere('email', 'LIKE', '%'.str_replace(['%', '_'], ['\\%', '\\_'], $search).'%');
            });
        }

        $users = $query->latest()->paginate(20);

        return view('backend.admin.users.index', compact('users'));
    }

    public function showUser(User $user)
    {
        $user->load(['role', 'company', 'jobSeekerProfile', 'applications']);

        return view('backend.admin.users.show', compact('user'));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,suspended,banned',
        ]);

        $user->update(['status' => $request->status]);

        $this->adminLogService->log(
            auth()->id(),
            'user_status_update',
            "Updated user {$user->name} status to {$request->status}",
            User::class,
            $user->id,
            ['old_status' => $user->getOriginal('status'), 'new_status' => $request->status]
        );

        return redirect()->back()->with('success', 'User status updated.');
    }

    /**
     * Delete a user (soft delete).
     */
    public function deleteUser(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->back()->withErrors(['error' => 'Cannot delete an admin user.']);
        }

        $this->adminLogService->log(
            auth()->id(),
            'user_deleted',
            "Deleted user {$user->name} ({$user->email})",
            User::class,
            $user->id
        );

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function companies(Request $request)
    {
        $query = Company::with('user');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->search);
            $query->where('name', 'LIKE', '%'.$search.'%');
        }

        $companies = $query->latest()->paginate(15);

        return view('backend.admin.companies.index', compact('companies'));
    }

    public function approveCompany(Company $company)
    {
        $company->update([
            'status' => 'approved',
            'is_active' => true,
        ]);

        $this->adminLogService->log(
            auth()->id(),
            'company_approved',
            "Approved company {$company->name}",
            Company::class,
            $company->id
        );

        // Send email notification to the recruiter
        if ($company->user && $company->user->email) {
            Mail::to($company->user->email)->queue(new CompanyApproved($company));
        }

        return redirect()->back()->with('success', 'Company approved successfully.');
    }

    public function rejectCompany(Request $request, Company $company)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $company->update([
            'status' => 'rejected',
            'is_active' => false,
        ]);

        $this->adminLogService->log(
            auth()->id(),
            'company_rejected',
            "Rejected company {$company->name}".($request->reason ? ": {$request->reason}" : ''),
            Company::class,
            $company->id
        );

        return redirect()->back()->with('success', 'Company rejected.');
    }

    public function verifyCompany(Company $company)
    {
        $company->update(['is_verified' => true]);

        $this->adminLogService->log(
            auth()->id(),
            'company_verified',
            "Verified company {$company->name}",
            Company::class,
            $company->id
        );

        return redirect()->back()->with('success', 'Company verified.');
    }

    public function unverifyCompany(Company $company)
    {
        $company->update(['is_verified' => false]);

        $this->adminLogService->log(
            auth()->id(),
            'company_unverified',
            "Removed verification for company {$company->name}",
            Company::class,
            $company->id
        );

        return redirect()->back()->with('success', 'Company verification removed.');
    }

    public function jobs(Request $request)
    {
        $query = Job::with(['company', 'category']);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('company', fn ($company) => $company->where('name', 'LIKE', '%'.$search.'%'));
            });
        }

        $jobs = $query->withCount('applications')->latest()->paginate(20);
        $categories = JobCategory::all();

        return view('backend.admin.jobs.index', compact('jobs', 'categories'));
    }

    public function showJob(Job $job)
    {
        $job->load(['company', 'category', 'user', 'applications.user.jobSeekerProfile']);

        return view('backend.admin.jobs.show', compact('job'));
    }

    public function updateJobStatus(Request $request, Job $job)
    {
        $request->validate([
            'status' => 'required|in:active,pending,closed,draft',
        ]);

        $oldStatus = $job->status;
        $job->update(['status' => $request->status]);

        $this->adminLogService->log(
            auth()->id(),
            'job_status_update',
            "Updated job '{$job->title}' status from {$oldStatus} to {$request->status}",
            Job::class,
            $job->id
        );

        return redirect()->back()->with('success', 'Job status updated.');
    }

    public function destroyJob(Job $job)
    {
        $this->adminLogService->log(
            auth()->id(),
            'job_deleted',
            "Deleted job '{$job->title}' from company {$job->company->name}",
            Job::class,
            $job->id
        );

        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job deleted successfully.');
    }

    public function applications(Request $request)
    {
        $query = Application::with(['user', 'job']);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(20);

        return view('backend.admin.applications.index', compact('applications'));
    }

    public function categories(Request $request)
    {
        $query = JobCategory::query();

        if ($request->has('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->search);
            $query->where('name', 'LIKE', '%'.$search.'%');
        }

        $categories = $query->withCount('activeJobs')->latest()->paginate(15);

        return view('backend.admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:job_categories,name',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
        ]);

        JobCategory::create($request->only(['name', 'description', 'icon']));

        return redirect()->back()->with('success', 'Category created.');
    }

    public function updateCategory(Request $request, JobCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:job_categories,name,'.$category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $category->update($request->only(['name', 'description', 'icon', 'is_active']));

        return redirect()->back()->with('success', 'Category updated.');
    }

    public function destroyCategory(JobCategory $category)
    {
        if ($category->jobs()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'Cannot delete category with existing jobs.']);
        }

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted.');
    }

    public function settings()
    {
        return view('backend.admin.settings');
    }

    /**
     * View admin activity log.
     */
    public function activityLog(Request $request)
    {
        $logs = AdminActivityLog::with('admin')
            ->latest()
            ->paginate(20);

        return view('backend.admin.activity-log', compact('logs'));
    }
}
