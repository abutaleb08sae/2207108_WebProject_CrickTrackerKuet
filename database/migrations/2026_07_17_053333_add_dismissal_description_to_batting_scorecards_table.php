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
        Schema::table('batting_scorecards', function (Blueprint $table) {
            // Adds the column as nullable so existing records don't break
            $table->string('dismissal_description')->nullable()->after('out_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batting_scorecards', function (Blueprint $table) {
            // Drops the column if the migration is rolled back
            $table->dropColumn('dismissal_description');
        });
    }
};