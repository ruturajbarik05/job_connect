<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('industry')->nullable();
            $table->string('company_size')->nullable();
            $table->string('founded_year')->nullable();
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

