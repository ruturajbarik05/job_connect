<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('jobs');

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('job_categories')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->text('responsibilities')->nullable();
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->enum('job_type', ['full-time', 'part-time', 'contract', 'internship', 'freelance'])->default('full-time');
            $table->enum('work_mode', ['onsite', 'remote', 'hybrid'])->default('onsite');
            $table->string('experience_level')->nullable();
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->string('salary_currency', 3)->default('USD');
            $table->string('salary_type')->nullable();
            $table->json('skills_required')->nullable();
            $table->json('keywords')->nullable();
            $table->integer('vacancies')->default(1);
            $table->date('application_deadline')->nullable();
            $table->enum('status', ['active', 'pending', 'closed', 'draft'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->integer('views')->default(0);
            $table->integer('applications_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
