<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Force drop conflicting tables if they already exist from previous setups
        Schema::dropIfExists('bowling_scorecards');
        Schema::dropIfExists('batting_scorecards');
        Schema::dropIfExists('live_match_states');

        // Now safely create them clean
        Schema::create('live_match_states', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixture_id')->unique(); 
            $table->foreignId('batsman_on_strike_id')->nullable()->constrained('players')->onDelete('set null');
            $table->foreignId('batsman_off_strike_id')->nullable()->constrained('players')->onDelete('set null');
            $table->foreignId('current_bowler_id')->nullable()->constrained('players')->onDelete('set null');
            $table->integer('current_over_balls_count')->default(0);
            $table->string('this_over_runslog')->nullable()->default('');
            $table->timestamps();
        });

        Schema::create('batting_scorecards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fixture_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->integer('runs_scored')->default(0);
            $table->integer('balls_faced')->default(0);
            $table->integer('fours_hit')->default(0);
            $table->integer('sixes_hit')->default(0);
            $table->string('out_status')->default('Not Out');
            $table->timestamps();
            
            $table->unique(['fixture_id', 'player_id']);
        });

        Schema::create('bowling_scorecards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fixture_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->float('overs_bowled')->default(0.0);
            $table->integer('balls_bowled')->default(0);
            $table->integer('maidens_bowled')->default(0);
            $table->integer('runs_conceded')->default(0);
            $table->integer('wickets_taken')->default(0);
            $table->timestamps();

            $table->unique(['fixture_id', 'player_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bowling_scorecards');
        Schema::dropIfExists('batting_scorecards');
        Schema::dropIfExists('live_match_states');
    }
};