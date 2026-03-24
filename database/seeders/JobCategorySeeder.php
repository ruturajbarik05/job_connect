<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Seeder;

class JobCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Information Technology', 'slug' => 'it', 'icon' => 'bi-laptop', 'description' => 'Software development, IT support, and system administration'],
            ['name' => 'Marketing & Sales', 'slug' => 'marketing-sales', 'icon' => 'bi-graph-up', 'description' => 'Digital marketing, sales, and business development'],
            ['name' => 'Finance & Accounting', 'slug' => 'finance', 'icon' => 'bi-currency-dollar', 'description' => 'Accounting, banking, and financial services'],
            ['name' => 'Healthcare', 'slug' => 'healthcare', 'icon' => 'bi-heart-pulse', 'description' => 'Medical, nursing, and healthcare services'],
            ['name' => 'Engineering', 'slug' => 'engineering', 'icon' => 'bi-tools', 'description' => 'Civil, mechanical, and electrical engineering'],
            ['name' => 'Education', 'slug' => 'education', 'icon' => 'bi-mortarboard', 'description' => 'Teaching, training, and academic positions'],
            ['name' => 'Human Resources', 'slug' => 'hr', 'icon' => 'bi-people', 'description' => 'Recruitment, training, and employee relations'],
            ['name' => 'Design & Creative', 'slug' => 'design-creative', 'icon' => 'bi-palette', 'description' => 'Graphic design, UI/UX, and creative arts'],
            ['name' => 'Administration', 'slug' => 'administration', 'icon' => 'bi-briefcase', 'description' => 'Office management and administrative support'],
            ['name' => 'Legal', 'slug' => 'legal', 'icon' => 'bi-scales', 'description' => 'Lawyers, paralegals, and legal services'],
            ['name' => 'Manufacturing', 'slug' => 'manufacturing', 'icon' => 'bi-gear', 'description' => 'Production, quality control, and operations'],
            ['name' => 'Hospitality', 'slug' => 'hospitality', 'icon' => 'bi-building', 'description' => 'Hotels, restaurants, and tourism'],
        ];

        foreach ($categories as $category) {
            JobCategory::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
