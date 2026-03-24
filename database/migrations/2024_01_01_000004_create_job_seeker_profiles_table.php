<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_seeker_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('summary')->nullable();
            $table->string('resume')->nullable();
            $table->string('avatar')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->json('skills')->nullable();
            $table->json('languages')->nullable();
            $table->enum('experience_level', ['fresher', 'mid', 'senior', 'lead', 'executive'])->default('fresher');
            $table->decimal('expected_salary', 12, 2)->nullable();
            $table->string('salary_currency', 3)->default('USD');
            $table->enum('employment_type_preference', ['full-time', 'part-time', 'contract', 'internship', 'freelance'])->default('full-time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfConstraints('job_seeker_profiles');
        Schema::dropIfExists('job_seeker_profiles');
    }
};
