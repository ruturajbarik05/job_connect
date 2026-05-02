<?php

namespace Tests\Feature;

use App\Models\AdminActivityLog;
use App\Models\AppNotification;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\JobSeekerProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectCompletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_activity_log_page_renders(): void
    {
        $admin = $this->userWithRole('admin');

        AdminActivityLog::create([
            'admin_id' => $admin->id,
            'action' => 'user_status_update',
            'description' => 'Updated a user status',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.activity-log'))
            ->assertOk()
            ->assertSee('Activity Log')
            ->assertSee('Updated a user status');
    }

    public function test_admin_jobs_search_uses_title_and_company_name(): void
    {
        $admin = $this->userWithRole('admin');
        $recruiter = $this->userWithRole('recruiter');
        $category = JobCategory::create(['name' => 'Engineering']);
        $company = Company::create([
            'user_id' => $recruiter->id,
            'name' => 'Acme Hiring',
            'status' => 'approved',
        ]);

        Job::create([
            'user_id' => $recruiter->id,
            'company_id' => $company->id,
            'category_id' => $category->id,
            'title' => 'Laravel Architect',
            'description' => 'Build Laravel systems.',
            'job_type' => 'full-time',
            'work_mode' => 'remote',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.jobs.index', ['search' => 'Laravel']))
            ->assertOk()
            ->assertSee('Laravel Architect');
    }

    public function test_recruiter_can_filter_applications_by_job_query_parameter(): void
    {
        $recruiter = $this->userWithRole('recruiter');
        $jobseeker = $this->userWithRole('jobseeker');
        JobSeekerProfile::create(['user_id' => $jobseeker->id]);

        $company = Company::create([
            'user_id' => $recruiter->id,
            'name' => 'Approved Co',
            'status' => 'approved',
        ]);

        $firstJob = $this->jobFor($recruiter, $company, 'Backend Engineer');
        $secondJob = $this->jobFor($recruiter, $company, 'Frontend Engineer');

        Application::create(['user_id' => $jobseeker->id, 'job_id' => $firstJob->id, 'status' => 'applied', 'applied_at' => now()]);
        Application::create(['user_id' => $jobseeker->id, 'job_id' => $secondJob->id, 'status' => 'applied', 'applied_at' => now()]);

        $this->actingAs($recruiter)
            ->get(route('recruiter.applications.index', ['job' => $firstJob->id]))
            ->assertOk()
            ->assertSee('Backend Engineer')
            ->assertDontSee(route('recruiter.applications.show', $secondJob->applications()->first()), false);
    }

    public function test_jobseeker_downloads_private_resume(): void
    {
        Storage::fake('local');

        $jobseeker = $this->userWithRole('jobseeker');
        Storage::disk('local')->put('resumes/test-resume.pdf', 'resume content');

        JobSeekerProfile::create([
            'user_id' => $jobseeker->id,
            'resume' => 'resumes/test-resume.pdf',
        ]);

        $this->actingAs($jobseeker)
            ->get(route('jobseeker.profile.resume.download'))
            ->assertOk();
    }

    public function test_recruiter_notifications_page_uses_custom_notifications_table(): void
    {
        $recruiter = $this->userWithRole('recruiter');

        Company::create([
            'user_id' => $recruiter->id,
            'name' => 'Approved Co',
            'status' => 'approved',
            'is_active' => true,
        ]);

        AppNotification::create([
            'user_id' => $recruiter->id,
            'type' => 'test',
            'title' => 'New Candidate',
            'message' => 'A candidate applied to your job.',
        ]);

        $this->actingAs($recruiter)
            ->get(route('recruiter.notifications.index'))
            ->assertOk()
            ->assertSee('New Candidate')
            ->assertSee('A candidate applied to your job.');
    }

    private function userWithRole(string $slug): User
    {
        $role = Role::firstOrCreate(
            ['slug' => $slug],
            ['name' => ucfirst($slug), 'description' => ucfirst($slug)]
        );

        return User::factory()->create([
            'role_id' => $role->id,
            'status' => 'active',
        ]);
    }

    private function jobFor(User $recruiter, Company $company, string $title): Job
    {
        return Job::create([
            'user_id' => $recruiter->id,
            'company_id' => $company->id,
            'title' => $title,
            'description' => $title.' description.',
            'job_type' => 'full-time',
            'work_mode' => 'remote',
            'status' => 'active',
        ]);
    }
}
