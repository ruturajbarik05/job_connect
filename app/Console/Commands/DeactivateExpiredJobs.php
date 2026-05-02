<?php

namespace App\Console\Commands;

use App\Services\JobService;
use Illuminate\Console\Command;

class DeactivateExpiredJobs extends Command
{
    protected $signature = 'jobs:deactivate-expired';

    protected $description = 'Deactivate jobs that have passed their expiry date';

    public function handle(JobService $jobService): int
    {
        $count = $jobService->deactivateExpiredJobs();

        $this->info("Deactivated {$count} expired job(s).");

        return Command::SUCCESS;
    }
}
