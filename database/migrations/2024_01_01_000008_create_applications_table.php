<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->string('cover_letter')->nullable();
            $table->string('resume')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->enum('status', ['applied', 'viewed', 'shortlisted', 'interview', 'offer', 'rejected', 'withdrawn'])->default('applied');
            $table->text('notes')->nullable();
            $table->timestamp('applied_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'job_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfConstraints('applications');
        Schema::dropIfExists('applications');
    }
};
