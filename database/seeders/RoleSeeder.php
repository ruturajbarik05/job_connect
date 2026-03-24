<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'System administrator with full access',
            ],
            [
                'name' => 'Recruiter',
                'slug' => 'recruiter',
                'description' => 'Employer/Recruiter who posts jobs and hires candidates',
            ],
            [
                'name' => 'Job Seeker',
                'slug' => 'jobseeker',
                'description' => 'Job seeker looking for employment opportunities',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
