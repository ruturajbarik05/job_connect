<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Requests\JobStoreRequest;
use App\Models\AppNotification;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Services\ApplicationService;
use App\Services\JobService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RecruiterController extends Controller
{
    public function __construct(
        private JobService $jobService,
        private ApplicationService $applicationService,
        private NotificationService $notificationService
    ) {}

    public function dashboard()
    {
        $user = auth()->user();
        $company = $user->company;

        if (! $company) {
            return redirect()->route('recruiter.company.profile')
                ->with('warning', 'Please complete your company profile first.');
        }

        $jobIds = $user->jobs()->pluck('id');

        $stats = [
            'totalJobs' => $user->jobs()->count(),
            'activeJobs' => $user->jobs()->where('status', 'active')->count(),
            'totalApplications' => Application::whereIn('job_id', $jobIds)->count(),
            'newApplications' => Application::whereIn('job_id', $jobIds)
                ->where('status', 'applied')
                ->count(),
        ];

        $recentApplications = Application::whereIn('job_id', $jobIds)
            ->with(['user.jobSeekerProfile', 'job'])
            ->latest()
            ->take(5)
            ->get();

        $recentJobs = $user->jobs()
            ->withCount('applications')
            ->latest()
            ->take(5)
            ->get();

        $notifications = $user->appNotifications()
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

        if (empty($company) || ! $company->exists) {
            $data['user_id'] = $user->id;
            $data['status'] = 'pending';
            $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);
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
        $this->authorize('create', Job::class);

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

        $this->jobService->createJob(
            $request->validated(),
            $user->id,
            $company->id,
            $company->is_verified
        );

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

        $this->jobService->updateJob($job, $request->validated());

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

    public function applications(Request $request)
    {
        $user = auth()->user();
        $job = null;

        if ($request->filled('job')) {
            $job = $user->jobs()->findOrFail($request->integer('job'));
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
        $this->authorize('update', $application);

        $application->markAsViewed();

        return view('backend.recruiter.applications.show', compact('application'));
    }

    public function updateApplicationStatus(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        $request->validate([
            'status' => 'required|in:shortlisted,interview,offer,rejected',
            'notes' => 'nullable|string',
        ]);

        $this->applicationService->updateStatus(
            $application,
            $request->status,
            $request->notes
        );

        return redirect()->back()->with('success', 'Application status updated.');
    }

    public function downloadResume(Application $application)
    {
        $this->authorize('update', $application);

        $profile = $application->user->jobSeekerProfile;

        if (! $profile || ! $profile->resume) {
            return redirect()->back()->withErrors(['resume' => 'Resume not found.']);
        }

        // Support both local (private) and public storage
        if (Storage::disk('local')->exists($profile->resume)) {
            return Storage::disk('local')->download($profile->resume);
        }

        $path = storage_path('app/public/' . $profile->resume);

        if (! file_exists($path)) {
            return redirect()->back()->withErrors(['resume' => 'Resume file not found.']);
        }

        return response()->download($path);
    }

    public function notifications()
    {
        $notifications = auth()->user()->appNotifications()
            ->latest()
            ->paginate(20);

        return view('backend.recruiter.notifications.index', compact('notifications'));
    }

    public function markNotificationRead($id)
    {
        $notification = AppNotification::where('user_id', auth()->id())->findOrFail($id);
        $this->notificationService->markAsRead($notification);

        if ($notification->link) {
            return redirect($notification->link);
        }

        return redirect()->back();
    }

    public function markAllNotificationsRead()
    {
        $this->notificationService->markAllAsRead(auth()->id());

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
