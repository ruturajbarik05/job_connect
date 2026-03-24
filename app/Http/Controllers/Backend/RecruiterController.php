<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Requests\JobStoreRequest;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RecruiterController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $company = $user->company;

        if (! $company) {
            return redirect()->route('recruiter.company.create')
                ->with('warning', 'Please complete your company profile first.');
        }

        $stats = [
            'totalJobs' => $user->jobs()->count(),
            'activeJobs' => $user->jobs()->where('status', 'active')->count(),
            'totalApplications' => Application::whereIn('job_id', $user->jobs()->pluck('id'))->count(),
            'newApplications' => Application::whereIn('job_id', $user->jobs()->pluck('id'))
                ->where('status', 'applied')
                ->count(),
        ];

        $recentApplications = Application::whereIn('job_id', $user->jobs()->pluck('id'))
            ->with(['user.jobSeekerProfile', 'job'])
            ->latest()
            ->take(5)
            ->get();

        $recentJobs = $user->jobs()
            ->withCount('applications')
            ->latest()
            ->take(5)
            ->get();

        $notifications = $user->notifications()
            ->unread()
            ->latest()
            ->take(5)
            ->get();

        return view('backend.recruiter.dashboard', compact(
            'stats',
            'recentApplications',
            'recentJobs',
            'notifications',
            'company'
        ));
    }

    public function companyProfile()
    {
        $user = auth()->user();
        $company = $user->company;

        if (! $company) {
            $company = new Company(['user_id' => $user->id]);
        }

        $categories = JobCategory::all();

        return view('backend.recruiter.company.profile', compact('company', 'categories'));
    }

    public function updateCompany(CompanyUpdateRequest $request)
    {
        $user = auth()->user();
        $company = $user->company;

        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($company && $company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $data['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($company && $company->banner) {
                Storage::disk('public')->delete($company->banner);
            }
            $data['banner'] = $request->file('banner')->store('companies/banners', 'public');
        }

        if (empty($company)) {
            $data['user_id'] = $user->id;
            $data['status'] = 'pending';
            $data['slug'] = Str::slug($request->name).'-'.Str::random(5);
            Company::create($data);
        } else {
            $company->update($data);
        }

        return redirect()->back()->with('success', 'Company profile updated successfully.');
    }

    public function jobs(Request $request)
    {
        $query = auth()->user()->jobs()->with('company');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $jobs = $query->withCount('applications')->latest()->paginate(10);

        return view('backend.recruiter.jobs.index', compact('jobs'));
    }

    public function createJob()
    {
        $categories = JobCategory::where('is_active', true)->get();

        return view('backend.recruiter.jobs.create', compact('categories'));
    }

    public function storeJob(JobStoreRequest $request)
    {
        $user = auth()->user();
        $company = $user->company;

        if (! $company || $company->status !== 'approved') {
            return redirect()->back()->withErrors([
                'company' => 'Your company must be approved before posting jobs.',
            ]);
        }

        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['company_id'] = $company->id;
        $data['slug'] = Str::slug($request->title).'-'.Str::random(5);
        $data['is_verified'] = $company->is_verified;

        if ($request->has('skills')) {
            $data['skills_required'] = is_array($request->skills)
                ? $request->skills
                : array_map('trim', explode(',', $request->skills));
        }

        $job = Job::create($data);

        return redirect()->route('recruiter.jobs.index')
            ->with('success', 'Job posted successfully.');
    }

    public function editJob(Job $job)
    {
        $this->authorize('update', $job);

        $categories = JobCategory::where('is_active', true)->get();

        return view('backend.recruiter.jobs.edit', compact('job', 'categories'));
    }

    public function updateJob(JobStoreRequest $request, Job $job)
    {
        $this->authorize('update', $job);

        $data = $request->validated();

        if ($request->has('skills')) {
            $data['skills_required'] = is_array($request->skills)
                ? $request->skills
                : array_map('trim', explode(',', $request->skills));
        }

        $job->update($data);

        return redirect()->route('recruiter.jobs.index')
            ->with('success', 'Job updated successfully.');
    }

    public function destroyJob(Job $job)
    {
        $this->authorize('delete', $job);

        $job->delete();

        return redirect()->route('recruiter.jobs.index')
            ->with('success', 'Job deleted successfully.');
    }

    public function applications(Request $request, ?Job $job = null)
    {
        $user = auth()->user();

        if ($job) {
            $this->authorize('view', $job);
            $applications = $job->applications();
        } else {
            $applicationIds = $user->jobs()->pluck('id');
            $applications = Application::whereIn('job_id', $applicationIds);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $applications->where('status', $request->status);
        }

        $applications = $applications
            ->with(['user.jobSeekerProfile', 'job'])
            ->latest()
            ->paginate(15);

        $jobs = $user->jobs()->where('status', 'active')->get();

        return view('backend.recruiter.applications.index', compact('applications', 'jobs', 'job'));
    }

    public function viewApplication(Application $application)
    {
        $user = auth()->user();

        if (! $user->jobs()->where('id', $application->job_id)->exists()) {
            abort(403);
        }

        if ($application->status === 'applied') {
            $application->update(['status' => 'viewed']);
        }

        return view('backend.recruiter.applications.show', compact('application'));
    }

    public function updateApplicationStatus(Request $request, Application $application)
    {
        $user = auth()->user();

        if (! $user->jobs()->where('id', $application->job_id)->exists()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:shortlisted,interview,offer,rejected',
            'notes' => 'nullable|string',
        ]);

        $application->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'reviewed_at' => now(),
        ]);

        Notification::send(
            $application->user_id,
            'application_status',
            'Application Status Updated',
            "Your application for {$application->job->title} has been {$request->status}.",
            route('jobseeker.applications.show', $application->id)
        );

        return redirect()->back()->with('success', 'Application status updated.');
    }

    public function downloadResume(Application $application)
    {
        $user = auth()->user();

        if (! $user->jobs()->where('id', $application->job_id)->exists()) {
            abort(403);
        }

        $profile = $application->user->jobSeekerProfile;

        if (! $profile || ! $profile->resume) {
            return redirect()->back()->withErrors(['resume' => 'Resume not found.']);
        }

        $path = storage_path('app/public/'.$profile->resume);

        if (! file_exists($path)) {
            return redirect()->back()->withErrors(['resume' => 'Resume file not found.']);
        }

        return response()->download($path);
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->paginate(20);

        return view('backend.recruiter.notifications.index', compact('notifications'));
    }
}
