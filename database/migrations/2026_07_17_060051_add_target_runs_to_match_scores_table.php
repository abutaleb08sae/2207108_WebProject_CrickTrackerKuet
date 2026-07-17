<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('match_scores', function (Blueprint $table) {
            // Adds target_runs as an integer that allows null values (useful during the 1st innings)
            $table->integer('target_runs')->nullable()->after('balls_bowled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_scores', function (Blueprint $table) {
            // Safely drops the column if you ever roll back the migration
            $table->dropColumn('target_runs');
        });
    }
};