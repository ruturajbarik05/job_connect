<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobSeekerProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'Recruiter', 'slug' => 'recruiter']);
        Role::create(['name' => 'Job Seeker', 'slug' => 'jobseeker']);
    }

    public function test_jobseeker_can_register_land_on_dashboard_logout_and_login_again(): void
    {
        $this->post(route('register'), [
            'name' => 'Jane Candidate',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'jobseeker',
        ])->assertRedirect(route('jobseeker.dashboard'));

        $user = User::where('email', 'jane@example.com')->firstOrFail()->refresh();

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->email_verified_at);
        $this->assertDatabaseHas('job_seeker_profiles', ['user_id' => $user->id]);

        $this->followingRedirects()
            ->actingAs($user)
            ->get(route('jobseeker.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard');

        $this->post(route('logout'))->assertRedirect('/');
        $this->assertGuest();

        $this->post(route('login'), [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('jobseeker.dashboard'));
    }

    public function test_recruiter_can_register_with_company_and_login_to_company_profile_until_approved(): void
    {
        $this->get(route('register', ['role' => 'recruiter']))
            ->assertOk()
            ->assertSee('id="recruiter" value="recruiter" checked', false)
            ->assertSee('Company Name');

        $this->post(route('register'), [
            'name' => 'Rick Recruiter',
            'email' => 'rick@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'recruiter',
            'company_name' => 'Hiring Labs',
        ])->assertRedirect(route('recruiter.company.profile'));

        $user = User::where('email', 'rick@example.com')->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('companies', [
            'user_id' => $user->id,
            'name' => 'Hiring Labs',
            'status' => 'pending',
        ]);

        $this->post(route('logout'))->assertRedirect('/');

        $this->post(route('login'), [
            'email' => 'rick@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('recruiter.dashboard'));

        $this->actingAs($user)
            ->get(route('recruiter.dashboard'))
            ->assertRedirect(route('recruiter.company.profile'));
    }

    public function test_approved_recruiter_can_reach_dashboard_after_login(): void
    {
        $recruiterRole = Role::where('slug', 'recruiter')->firstOrFail();
        $user = User::factory()->create([
            'role_id' => $recruiterRole->id,
            'email' => 'approved@example.com',
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        Company::create([
            'user_id' => $user->id,
            'name' => 'Approved Hiring',
            'slug' => 'approved-hiring',
            'status' => 'approved',
            'is_active' => true,
        ]);

        $this->post(route('login'), [
            'email' => 'approved@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('recruiter.dashboard'));

        $this->actingAs($user)
            ->get(route('recruiter.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard');
    }
}
