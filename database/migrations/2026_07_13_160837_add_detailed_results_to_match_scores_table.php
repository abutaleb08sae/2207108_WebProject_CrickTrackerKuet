<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('match_scores', function (Blueprint $table) {
            $table->integer('innings_two_runs')->nullable();
            $table->integer('innings_two_wickets')->nullable();
            $table->integer('innings_one_wickets')->nullable();
            $table->string('match_result_string')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('match_scores', function (Blueprint $table) {
            $table->dropColumn(['innings_two_runs', 'innings_two_wickets', 'innings_one_wickets', 'match_result_string']);
        });
    }
};