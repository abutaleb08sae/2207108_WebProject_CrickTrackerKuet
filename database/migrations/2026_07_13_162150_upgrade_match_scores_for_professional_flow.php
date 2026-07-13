<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('match_scores', function (Blueprint $table) {
            // Toss tracking
            if (!Schema::hasColumn('match_scores', 'toss_winner_id')) {
                $table->unsignedBigInteger('toss_winner_id')->nullable();
            }
            if (!Schema::hasColumn('match_scores', 'toss_decision')) {
                $table->string('toss_decision')->nullable();
            }
            
            // Explicit team associations per innings
            if (!Schema::hasColumn('match_scores', 'innings_one_batting_team_id')) {
                $table->unsignedBigInteger('innings_one_batting_team_id')->nullable();
            }
            if (!Schema::hasColumn('match_scores', 'innings_two_batting_team_id')) {
                $table->unsignedBigInteger('innings_two_batting_team_id')->nullable();
            }
            
            // Innings 1 metrics
            if (!Schema::hasColumn('match_scores', 'innings_one_runs')) {
                $table->integer('innings_one_runs')->default(0);
            }
            if (!Schema::hasColumn('match_scores', 'innings_one_wickets')) {
                $table->integer('innings_one_wickets')->default(0);
            }
            if (!Schema::hasColumn('match_scores', 'innings_one_balls')) {
                $table->integer('innings_one_balls')->default(0);
            }
            
            // Innings 2 metrics
            if (!Schema::hasColumn('match_scores', 'innings_two_runs')) {
                $table->integer('innings_two_runs')->default(0);
            }
            if (!Schema::hasColumn('match_scores', 'innings_two_wickets')) {
                $table->integer('innings_two_wickets')->default(0);
            }
            if (!Schema::hasColumn('match_scores', 'innings_two_balls')) {
                $table->integer('innings_two_balls')->default(0);
            }

            // Result text description
            if (!Schema::hasColumn('match_scores', 'match_result_string')) {
                $table->string('match_result_string')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('match_scores', function (Blueprint $table) {
            $table->dropColumn([
                'toss_winner_id', 'toss_decision', 
                'innings_one_batting_team_id', 'innings_two_batting_team_id',
                'innings_one_runs', 'innings_one_wickets', 'innings_one_balls',
                'innings_two_runs', 'innings_two_wickets', 'innings_two_balls',
                'match_result_string'
            ]);
        });
    }
};