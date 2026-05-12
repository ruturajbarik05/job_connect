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

    public function test_job_search_ignores_blank_filter_values(): void
    {
        $recruiter = $this->userWithRole('recruiter');
        $company = Company::create([
            'user_id' => $recruiter->id,
            'name' => 'Search Co',
            'status' => 'approved',
            'is_active' => true,
        ]);

        $this->jobFor($recruiter, $company, 'Full Time Search Result');

        $this->get(route('jobs.search', [
            'job_type' => 'full-time',
            'work_mode' => '',
        ]))
            ->assertOk()
            ->assertSee('Search Results (1)')
            ->assertSee('Full Time Search Result');
    }

    public function test_job_search_page_keeps_keyword_and_location_fields_visible(): void
    {
        $this->get(route('jobs.search', [
            'search' => 'seo specialist',
            'location' => 'New York',
            'job_type' => 'full-time',
        ]))
            ->assertOk()
            ->assertSee('name="search"', false)
            ->assertSee('value="seo specialist"', false)
            ->assertSee('name="location"', false)
            ->assertSee('value="New York"', false);
    }

    public function test_company_detail_page_renders_with_open_positions(): void
    {
        $recruiter = $this->userWithRole('recruiter');
        $company = Company::create([
            'user_id' => $recruiter->id,
            'name' => 'TechCorp Solutions',
            'slug' => 'techcorp-solutions',
            'status' => 'approved',
            'is_active' => true,
        ]);

        $this->jobFor($recruiter, $company, 'Company Detail Engineer');

        $this->get(route('companies.show', $company->slug))
            ->assertOk()
            ->assertSee('TechCorp Solutions')
            ->assertSee('1 Open Positions')
            ->assertSee('Company Detail Engineer');
    }

    public function test_legal_category_has_visible_bootstrap_icon(): void
    {
        \App\Models\JobCategory::create([
            'name' => 'Legal',
            'slug' => 'legal',
            'icon' => 'bi-bank',
            'is_active' => true,
        ]);

        $this->get(route('categories.index'))
            ->assertOk()
            ->assertSee('Legal')
            ->assertSee('bi-bank');
    }

    public function test_jobseeker_profile_form_updates_profile_without_name_field(): void
    {
        $jobseeker = $this->userWithRole('jobseeker');
        JobSeekerProfile::create(['user_id' => $jobseeker->id]);

        $this->actingAs($jobseeker)
            ->post(route('jobseeker.profile.update'), [
                'first_name' => 'Ruturaj',
                'last_name' => 'Barik',
                'phone' => '8144727956',
                'city' => 'Bhubaneswar',
                'state' => 'Odisha',
                'country' => 'India',
                'experience_level' => 'fresher',
                'expected_salary' => 5,
                'summary' => 'Laravel developer profile.',
                'skills' => 'PHP, Laravel, JavaScript',
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Profile updated successfully.');

        $this->assertDatabaseHas('users', [
            'id' => $jobseeker->id,
            'name' => 'Ruturaj Barik',
        ]);

        $this->assertDatabaseHas('job_seeker_profiles', [
            'user_id' => $jobseeker->id,
            'first_name' => 'Ruturaj',
            'last_name' => 'Barik',
            'phone' => '8144727956',
            'city' => 'Bhubaneswar',
        ]);

        $this->actingAs($jobseeker)
            ->get(route('jobseeker.profile.index'))
            ->assertOk()
            ->assertSee('Ruturaj Barik')
            ->assertSee('8144727956')
            ->assertSee('Bhubaneswar')
            ->assertSee('PHP');
    }

    public function test_jobseeker_can_add_education_and_work_experience(): void
    {
        $jobseeker = $this->userWithRole('jobseeker');
        JobSeekerProfile::create(['user_id' => $jobseeker->id]);

        $this->actingAs($jobseeker)
            ->post(route('jobseeker.education.store'), [
                'institution' => 'Utkal University',
                'degree' => 'B.Tech',
                'field_of_study' => 'Computer Science',
                'start_date' => '2020-01-01',
                'end_date' => '2024-01-01',
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Education added successfully.');

        $this->actingAs($jobseeker)
            ->post(route('jobseeker.experience.store'), [
                'job_title' => 'Laravel Developer',
                'company_name' => 'JobConnect',
                'location' => 'Bhubaneswar',
                'start_date' => '2024-02-01',
                'end_date' => '2026-01-01',
                'description' => 'Built Laravel features.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Experience added successfully.');

        $this->assertDatabaseHas('education', [
            'user_id' => $jobseeker->id,
            'institution' => 'Utkal University',
            'degree' => 'B.Tech',
        ]);

        $this->assertDatabaseHas('experiences', [
            'user_id' => $jobseeker->id,
            'job_title' => 'Laravel Developer',
            'company_name' => 'JobConnect',
        ]);

        $this->actingAs($jobseeker)
            ->get(route('jobseeker.profile.index'))
            ->assertOk()
            ->assertSee('Utkal University')
            ->assertSee('Laravel Developer');
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
