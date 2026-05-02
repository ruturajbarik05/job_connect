<?php

namespace App\Services;

use App\Enums\JobStatus;
use App\Models\Job;
use Illuminate\Support\Str;

class JobService
{
    /**
     * Create a new job posting.
     */
    public function createJob(array $data, int $userId, int $companyId, bool $isVerified): Job
    {
        $data['user_id'] = $userId;
        $data['company_id'] = $companyId;
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);
        $data['is_verified'] = $isVerified;

        if (isset($data['skills'])) {
            $data['skills_required'] = is_array($data['skills'])
                ? $data['skills']
                : array_map('trim', explode(',', $data['skills']));
            unset($data['skills']);
        }

        return Job::create($data);
    }

    /**
     * Update an existing job.
     */
    public function updateJob(Job $job, array $data): Job
    {
        if (isset($data['skills'])) {
            $data['skills_required'] = is_array($data['skills'])
                ? $data['skills']
                : array_map('trim', explode(',', $data['skills']));
            unset($data['skills']);
        }

        $job->update($data);

        return $job->fresh();
    }

    /**
     * Deactivate expired jobs.
     */
    public function deactivateExpiredJobs(): int
    {
        return Job::where('status', JobStatus::Active->value)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->update(['status' => JobStatus::Closed->value]);
    }
}
