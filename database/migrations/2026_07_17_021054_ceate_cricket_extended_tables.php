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
        // 1. EXTEND TEAMS TABLE FIELDS
        Schema::table('teams', function (Blueprint $table) {
            if (!Schema::hasColumn('teams', 'short_name')) {
                $table->string('short_name', 10)->nullable()->after('name');
                $table->string('country')->nullable();
                $table->string('logo_path')->nullable();
                $table->unsignedBigInteger('captain_id')->nullable();
                $table->string('coach')->nullable();
                $table->text('description')->nullable();
                $table->integer('ranking')->nullable();
                $table->integer('founded_year')->nullable();
                $table->string('home_ground')->nullable();
                $table->string('website')->nullable();
            }
        });

        // 2. PLAYERS TABLE (With safety guard check)
        if (!Schema::hasTable('players')) {
            Schema::create('players', function (Blueprint $table) {
                $table->id();
                $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null');
                $table->string('name');
                $table->string('photo_path')->nullable();
                $table->string('role');
                $table->string('nationality')->nullable();
                $table->string('batting_style')->nullable();
                $table->string('bowling_style')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('height', 15)->nullable();
                $table->integer('jersey_number')->nullable();
                $table->date('debut_date')->nullable();
                $table->text('biography')->nullable();
                $table->timestamps();
            });
        }

        // 3. PLAYER CAREER STATISTICS
        if (!Schema::hasTable('player_stats')) {
            Schema::create('player_stats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('player_id')->constrained()->onDelete('cascade');
                $table->string('match_type'); 
                $table->integer('matches_played')->default(0);
                $table->integer('runs_scored')->default(0);
                $table->decimal('batting_average', 5, 2)->default(0.00);
                $table->decimal('batting_strike_rate', 6, 2)->default(0.00);
                $table->integer('hundreds')->default(0);
                $table->integer('fifties')->default(0);
                $table->integer('highest_score')->default(0);
                $table->integer('wickets_taken')->default(0);
                $table->decimal('bowling_economy', 4, 2)->default(0.00);
                $table->string('best_bowling', 10)->nullable();
                $table->timestamps();
            });
        }

        // 4. FIXTURE EXTENSIONS
        Schema::table('fixtures', function (Blueprint $table) {
            if (!Schema::hasColumn('fixtures', 'toss_winner_id')) {
                $table->foreignId('toss_winner_id')->nullable()->constrained('teams');
                $table->enum('toss_decision', ['BAT', 'FIELD'])->nullable();
                $table->integer('target_runs')->nullable();
                $table->unsignedBigInteger('current_striker_id')->nullable();
                $table->unsignedBigInteger('current_non_striker_id')->nullable();
                $table->unsignedBigInteger('current_bowler_id')->nullable();
            }
        });

        // 5. MATCH SQUADS
        if (!Schema::hasTable('match_squads')) {
            Schema::create('match_squads', function (Blueprint $table) {
                $table->id();
                $table->foreignId('fixture_id')->constrained()->onDelete('cascade');
                $table->foreignId('team_id')->constrained()->onDelete('cascade');
                $table->foreignId('player_id')->constrained()->onDelete('cascade');
                $table->boolean('is_playing_xi')->default(false);
                $table->boolean('is_captain')->default(false);
                $table->boolean('is_vice_captain')->default(false);
                $table->boolean('is_wicket_keeper')->default(false);
                $table->boolean('is_substitute')->default(false);
                $table->timestamps();
            });
        }

        // 6. INNINGS TABLE
        if (!Schema::hasTable('innings')) {
            Schema::create('innings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('fixture_id')->constrained()->onDelete('cascade');
                $table->foreignId('batting_team_id')->constrained('teams')->onDelete('cascade');
                $table->foreignId('bowling_team_id')->constrained('teams')->onDelete('cascade');
                $table->integer('innings_number'); 
                $table->integer('total_runs')->default(0);
                $table->integer('total_wickets')->default(0);
                $table->integer('overs_bowled_balls')->default(0); 
                $table->integer('bye_extras')->default(0);
                $table->integer('leg_bye_extras')->default(0);
                $table->integer('wide_extras')->default(0);
                $table->integer('no_ball_extras')->default(0);
                $table->boolean('is_declared')->default(false);
                $table->boolean('is_completed')->default(false);
                $table->timestamps();
            });
        }

        // 7. BATTING SCORECARDS ROWS
        if (!Schema::hasTable('batting_scorecards')) {
            Schema::create('batting_scorecards', function (Blueprint $table) {
                $table->id();
                $table->foreignId('innings_id')->constrained()->onDelete('cascade');
                $table->foreignId('player_id')->constrained()->onDelete('cascade');
                $table->string('dismissal_description')->default('not out');
                $table->foreignId('bowler_id')->nullable()->constrained('players');
                $table->foreignId('fielder_id')->nullable()->constrained('players');
                $table->integer('runs')->default(0);
                $table->integer('balls_faced')->default(0);
                $table->integer('fours')->default(0);
                $table->integer('sixes')->default(0);
                $table->timestamps();
            });
        }

        // 8. BOWLING SCORECARDS ROWS
        if (!Schema::hasTable('bowling_scorecards')) {
            Schema::create('bowling_scorecards', function (Blueprint $table) {
                $table->id();
                $table->foreignId('innings_id')->constrained()->onDelete('cascade');
                $table->foreignId('player_id')->constrained()->onDelete('cascade'); 
                $table->integer('balls_thrown')->default(0); 
                $table->integer('maidens')->default(0);
                $table->integer('runs_conceded')->default(0);
                $table->integer('wickets_taken')->default(0);
                $table->integer('wides_conceded')->default(0);
                $table->integer('no_balls_conceded')->default(0);
                $table->timestamps();
            });
        }

        // 9. GRANULAR BALL BY BALL LOGS
        if (!Schema::hasTable('ball_by_ball_logs')) {
            Schema::create('ball_by_ball_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('innings_id')->constrained()->onDelete('cascade');
                $table->integer('over_number');
                $table->integer('ball_number');
                $table->foreignId('batsman_id')->constrained('players');
                $table->foreignId('bowler_id')->constrained('players');
                $table->integer('runs_batter')->default(0);
                $table->integer('runs_extras')->default(0);
                $table->string('extra_type')->nullable(); 
                $table->boolean('is_wicket')->default(false);
                $table->string('wicket_type')->nullable(); 
                $table->foreignId('dismissed_player_id')->nullable()->constrained('players');
                $table->text('commentary_text')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ball_by_ball_logs');
        Schema::dropIfExists('bowling_scorecards');
        Schema::dropIfExists('batting_scorecards');
        Schema::dropIfExists('innings');
        Schema::dropIfExists('match_squads');
        Schema::dropIfExists('player_stats');
        
        // Only drop if it wasn't pre-existing
        // Schema::dropIfExists('players');
    }
};