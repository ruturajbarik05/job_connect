<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $oldCurrency = implode('', ['U', 'S', 'D']);

        DB::table('jobs')
            ->where('salary_currency', $oldCurrency)
            ->update(['salary_currency' => 'INR']);

        DB::table('job_seeker_profiles')
            ->where('salary_currency', $oldCurrency)
            ->update(['salary_currency' => 'INR']);
    }

    public function down(): void
    {
        //
    }
};
