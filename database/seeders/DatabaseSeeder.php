<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\JobSeekerProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            JobCategorySeeder::class,
        ]);

        $adminRole = Role::where('slug', 'admin')->first();
        $recruiterRole = Role::where('slug', 'recruiter')->first();
        $jobseekerRole = Role::where('slug', 'jobseeker')->first();

        $admin = User::updateOrCreate(
            ['email' => 'admin@jobportal.com'],
            [
                'name' => 'Admin User',
                'password' => 'password',
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );

        $recruiter1 = User::updateOrCreate(
            ['email' => 'recruiter@example.com'],
            [
                'name' => 'John Smith',
                'password' => 'password',
                'role_id' => $recruiterRole->id,
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );

        $company1 = Company::firstOrCreate(
            ['user_id' => $recruiter1->id],
            [
                'name' => 'TechCorp Solutions',
                'slug' => 'techcorp-solutions',
                'description' => 'A leading technology company specializing in web development, mobile apps, and cloud solutions.',
                'website' => 'https://techcorp.example.com',
                'industry' => 'Information Technology',
                'company_size' => '100-500',
                'founded_year' => '2015',
                'location' => 'San Francisco, CA',
                'address' => '123 Tech Street, San Francisco, CA 94102',
                'phone' => '+1 (555) 123-4567',
                'email' => 'hr@techcorp.example.com',
                'status' => 'approved',
                'is_verified' => true,
                'is_active' => true,
            ]
        );

        $recruiter2 = User::firstOrCreate(
            ['email' => 'recruiter2@example.com'],
            [
                'name' => 'Sarah Johnson',
                'password' => 'password',
                'role_id' => $recruiterRole->id,
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );

        $company2 = Company::firstOrCreate(
            ['user_id' => $recruiter2->id],
            [
                'name' => 'Digital Marketing Pro',
                'slug' => 'digital-marketing-pro',
                'description' => 'A boutique digital marketing agency helping brands grow through SEO, content marketing, and social media strategies.',
                'website' => 'https://digitalmarketingpro.example.com',
                'industry' => 'Marketing & Advertising',
                'company_size' => '10-50',
                'founded_year' => '2018',
                'location' => 'New York, NY',
                'address' => '456 Marketing Ave, New York, NY 10001',
                'phone' => '+1 (555) 987-6543',
                'email' => 'careers@digitalmarketingpro.example.com',
                'status' => 'approved',
                'is_verified' => true,
                'is_active' => true,
            ]
        );

        $jobseeker1 = User::firstOrCreate(
            ['email' => 'jobseeker@example.com'],
            [
                'name' => 'Michael Chen',
                'password' => 'password',
                'role_id' => $jobseekerRole->id,
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );

        JobSeekerProfile::firstOrCreate(
            ['user_id' => $jobseeker1->id],
            [
                'first_name' => 'Michael',
                'last_name' => 'Chen',
                'phone' => '+1 (555) 234-5678',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
                'summary' => 'Passionate full-stack developer with 5 years of experience in building web applications.',
                'skills' => ['PHP', 'Laravel', 'JavaScript', 'React', 'Vue.js', 'MySQL', 'Git'],
                'experience_level' => 'senior',
                'expected_salary' => 120000,
                'salary_currency' => 'USD',
                'employment_type_preference' => 'full-time',
            ]
        );

        $jobseeker2 = User::firstOrCreate(
            ['email' => 'jobseeker2@example.com'],
            [
                'name' => 'Emily Davis',
                'password' => 'password',
                'role_id' => $jobseekerRole->id,
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );

        JobSeekerProfile::firstOrCreate(
            ['user_id' => $jobseeker2->id],
            [
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'phone' => '+1 (555) 345-6789',
                'city' => 'Seattle',
                'state' => 'WA',
                'country' => 'USA',
                'summary' => 'Creative digital marketer with expertise in SEO and content strategy.',
                'skills' => ['SEO', 'Google Analytics', 'Content Marketing', 'Social Media'],
                'experience_level' => 'mid',
                'expected_salary' => 85000,
                'salary_currency' => 'USD',
                'employment_type_preference' => 'full-time',
            ]
        );

        $categories = JobCategory::all();
        $itCategory = $categories->where('slug', 'it')->first();
        $marketingCategory = $categories->where('slug', 'marketing-sales')->first();

        if ($itCategory && $company1 && $recruiter1) {
            Job::firstOrCreate(
                ['title' => 'Senior Full Stack Developer', 'company_id' => $company1->id],
                [
                    'user_id' => $recruiter1->id,
                    'category_id' => $itCategory->id,
                    'description' => 'We are looking for a Senior Full Stack Developer to join our growing team.',
                    'requirements' => "5+ years experience in PHP/Laravel\n3+ years in React\nStrong knowledge of MySQL",
                    'benefits' => "Competitive salary\nHealth insurance\nRemote work options",
                    'location' => 'San Francisco, CA',
                    'job_type' => 'full-time',
                    'work_mode' => 'hybrid',
                    'experience_level' => 'senior',
                    'salary_min' => 100000,
                    'salary_max' => 140000,
                    'salary_currency' => 'USD',
                    'skills_required' => ['PHP', 'Laravel', 'React', 'MySQL', 'Docker', 'AWS'],
                    'vacancies' => 2,
                    'status' => 'active',
                    'is_verified' => true,
                    'is_featured' => true,
                ]
            );

            Job::firstOrCreate(
                ['title' => 'Junior Web Developer', 'company_id' => $company1->id],
                [
                    'user_id' => $recruiter1->id,
                    'category_id' => $itCategory->id,
                    'description' => 'Join our team as a Junior Web Developer and grow your career.',
                    'requirements' => "Basic PHP/JavaScript knowledge\nUnderstanding of HTML/CSS",
                    'location' => 'San Francisco, CA',
                    'job_type' => 'full-time',
                    'work_mode' => 'onsite',
                    'experience_level' => 'fresher',
                    'salary_min' => 50000,
                    'salary_max' => 70000,
                    'salary_currency' => 'USD',
                    'skills_required' => ['PHP', 'JavaScript', 'HTML', 'CSS'],
                    'vacancies' => 3,
                    'status' => 'active',
                    'is_verified' => true,
                ]
            );
        }

        if ($marketingCategory && $company2 && $recruiter2) {
            Job::firstOrCreate(
                ['title' => 'SEO Specialist', 'company_id' => $company2->id],
                [
                    'user_id' => $recruiter2->id,
                    'category_id' => $marketingCategory->id,
                    'description' => 'We need an SEO Specialist to help improve client search rankings.',
                    'requirements' => "3+ years SEO experience\nProficiency in SEO tools",
                    'location' => 'New York, NY',
                    'job_type' => 'full-time',
                    'work_mode' => 'remote',
                    'experience_level' => 'mid',
                    'salary_min' => 65000,
                    'salary_max' => 90000,
                    'salary_currency' => 'USD',
                    'skills_required' => ['SEO', 'Google Analytics', 'Ahrefs'],
                    'vacancies' => 1,
                    'status' => 'active',
                    'is_verified' => true,
                    'is_featured' => true,
                ]
            );

            Job::firstOrCreate(
                ['title' => 'Content Marketing Manager', 'company_id' => $company2->id],
                [
                    'user_id' => $recruiter2->id,
                    'category_id' => $marketingCategory->id,
                    'description' => 'Lead our content marketing efforts.',
                    'requirements' => "5+ years in content marketing\nExcellent writing skills",
                    'location' => 'New York, NY',
                    'job_type' => 'full-time',
                    'work_mode' => 'hybrid',
                    'experience_level' => 'senior',
                    'salary_min' => 85000,
                    'salary_max' => 110000,
                    'salary_currency' => 'USD',
                    'skills_required' => ['Content Strategy', 'Copywriting', 'SEO', 'Social Media'],
                    'vacancies' => 1,
                    'status' => 'active',
                    'is_verified' => true,
                ]
            );
        }

        $firstJob = Job::first();
        if ($firstJob && $jobseeker1) {
            Application::firstOrCreate(
                ['user_id' => $jobseeker1->id, 'job_id' => $firstJob->id],
                [
                    'status' => 'shortlisted',
                    'applied_at' => now()->subDays(3),
                    'reviewed_at' => now()->subDays(1),
                ]
            );
        }
    }
}
