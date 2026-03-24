<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\Notification;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
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

        $application = Application::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'status' => 'applied',
            'applied_at' => now(),
            'resume' => $profile->resume,
            'cover_letter' => $request->cover_letter,
        ]);

        $job->incrementApplications();

        Notification::send(
            $job->user_id,
            'new_application',
            'New Application Received',
            "{$user->name} has applied for {$job->title}.",
            route('recruiter.applications.show', $application->id)
        );

        return redirect()->back()->with('success', 'Application submitted successfully!');
    }

    public function withdraw(Application $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        if ($application->status === 'withdrawn') {
            return redirect()->back()->withErrors(['error' => 'Application already withdrawn.']);
        }

        $application->update(['status' => 'withdrawn']);

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
