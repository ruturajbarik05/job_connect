<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\SavedJob;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function __construct(
        private ApplicationService $applicationService
    ) {}

    public function store(Request $request, Job $job)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to apply for this job.');
        }

        $user = Auth::user();

        if (! $user->isJobSeeker()) {
            return redirect()->back()->withErrors(['error' => 'Only job seekers can apply for jobs.']);
        }

        $existingApplication = Application::where('user_id', $user->id)
            ->where('job_id', $job->id)
            ->first();

        if ($existingApplication) {
            return redirect()->back()->withErrors(['error' => 'You have already applied for this job.']);
        }

        if ($job->status !== 'active') {
            return redirect()->back()->withErrors(['error' => 'This job is no longer accepting applications.']);
        }

        if ($job->isDeadlinePassed()) {
            return redirect()->back()->withErrors(['error' => 'The application deadline has passed.']);
        }

        $profile = $user->jobSeekerProfile;

        if (! $profile || ! $profile->resume) {
            return redirect()->back()->withErrors([
                'resume' => 'Please upload your resume before applying for jobs.',
            ]);
        }

        $this->applicationService->applyForJob(
            $user->id,
            $job,
            $request->cover_letter,
            $profile->resume
        );

        return redirect()->back()->with('success', 'Application submitted successfully!');
    }

    public function withdraw(Application $application)
    {
        $this->authorize('withdraw', $application);

        if ($application->status === 'withdrawn') {
            return redirect()->back()->withErrors(['error' => 'Application already withdrawn.']);
        }

        $this->applicationService->withdraw($application);

        return redirect()->back()->with('success', 'Application withdrawn successfully.');
    }

    public function saveJob(Job $job)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $exists = SavedJob::where('user_id', $user->id)
            ->where('job_id', $job->id)
            ->exists();

        if ($exists) {
            SavedJob::where('user_id', $user->id)
                ->where('job_id', $job->id)
                ->delete();

            return redirect()->back()->with('success', 'Job removed from saved list.');
        }

        SavedJob::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
        ]);

        return redirect()->back()->with('success', 'Job saved successfully!');
    }
}
