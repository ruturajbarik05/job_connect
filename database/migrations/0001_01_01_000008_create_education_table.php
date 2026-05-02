<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('institution');
            $table->string('degree');
            $table->string('field_of_study');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
