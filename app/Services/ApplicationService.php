<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Mail\ApplicationReceived;
use App\Mail\ApplicationStatusUpdated;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApplicationService
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    /**
     * Apply for a job with DB transaction.
     */
    public function applyForJob(int $userId, Job $job, ?string $coverLetter, string $resumePath): Application
    {
        return DB::transaction(function () use ($userId, $job, $coverLetter, $resumePath) {
            $application = Application::create([
                'user_id' => $userId,
                'job_id' => $job->id,
                'status' => ApplicationStatus::Applied->value,
                'applied_at' => now(),
                'resume' => $resumePath,
                'cover_letter' => $coverLetter,
            ]);

            $job->increment('applications_count');

            // Notify the recruiter
            $this->notificationService->send(
                $job->user_id,
                'new_application',
                'New Application Received',
                auth()->user()->name . " has applied for {$job->title}.",
                route('recruiter.applications.show', $application->id)
            );

            if ($job->user?->email) {
                Mail::to($job->user->email)->queue(new ApplicationReceived($application));
            }

            return $application;
        });
    }

    /**
     * Withdraw an application.
     */
    public function withdraw(Application $application): void
    {
        $application->update(['status' => ApplicationStatus::Withdrawn->value]);
    }

    /**
     * Update application status.
     */
    public function updateStatus(Application $application, string $status, ?string $notes = null): void
    {
        $application->update([
            'status' => $status,
            'notes' => $notes,
            'reviewed_at' => now(),
        ]);

        $this->notificationService->send(
            $application->user_id,
            'application_status',
            'Application Status Updated',
            "Your application for {$application->job->title} has been {$status}.",
            route('jobseeker.applications.show', $application->id)
        );

        if ($application->user?->email) {
            Mail::to($application->user->email)->queue(new ApplicationStatusUpdated($application, $status));
        }
    }
}
