<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add expiry_date and soft deletes to jobs
        Schema::table('jobs', function (Blueprint $table) {
            $table->date('expiry_date')->nullable()->after('application_deadline');
            $table->softDeletes();
            $table->index('status');
            $table->index('category_id');
            $table->index('company_id');
            $table->index('user_id');
        });

        // Add soft deletes to companies
        Schema::table('companies', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('status');
            $table->index('user_id');
        });

        // Add soft deletes to applications
        Schema::table('applications', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('status');
            $table->index('job_id');
            $table->index('user_id');
        });

        // Add soft deletes to users
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add indexes to notifications table
        Schema::table('notifications', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('is_read');
        });

        // Create admin activity logs table
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('action');
            $table->text('description');
            $table->string('target_type')->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['admin_id', 'created_at']);
            $table->index(['target_type', 'target_id']);
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('expiry_date');
            $table->dropSoftDeletes();
            $table->dropIndex(['status']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['status']);
            $table->dropIndex(['job_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['is_read']);
        });

        Schema::dropIfExists('admin_activity_logs');
    }
};
