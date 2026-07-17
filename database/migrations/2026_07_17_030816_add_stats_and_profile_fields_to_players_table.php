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
        Schema::table('players', function (Blueprint $table) {
            // Profile & Bio Extensions
            if (!Schema::hasColumn('players', 'short_name')) {
                $table->string('short_name', 50)->nullable()->after('name');
            }
            if (!Schema::hasColumn('players', 'jersey_number')) {
                $table->string('jersey_number', 10)->nullable()->after('role');
            }
            if (!Schema::hasColumn('players', 'batting_style')) {
                $table->string('batting_style', 50)->nullable()->after('jersey_number');
            }
            if (!Schema::hasColumn('players', 'bowling_style')) {
                $table->string('bowling_style', 50)->nullable()->after('batting_style');
            }
            if (!Schema::hasColumn('players', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('bowling_style');
            }
            if (!Schema::hasColumn('players', 'nationality')) {
                $table->string('nationality', 100)->nullable()->after('birth_date');
            }
            if (!Schema::hasColumn('players', 'image_path')) {
                $table->string('image_path')->nullable()->after('nationality');
            }

            // General Stats
            if (!Schema::hasColumn('players', 'matches_played')) {
                $table->integer('matches_played')->default(0)->after('image_path');
            }
            
            // Batting Stats
            if (!Schema::hasColumn('players', 'total_runs')) {
                $table->integer('total_runs')->default(0)->after('matches_played');
            }
            if (!Schema::hasColumn('players', 'highest_score')) {
                $table->integer('highest_score')->default(0)->after('total_runs');
            }
            if (!Schema::hasColumn('players', 'batting_average')) {
                $table->decimal('batting_average', 5, 2)->default(0.00)->after('highest_score');
            }
            if (!Schema::hasColumn('players', 'batting_strike_rate')) {
                $table->decimal('batting_strike_rate', 5, 2)->default(0.00)->after('batting_average');
            }
            if (!Schema::hasColumn('players', 'fifties')) {
                $table->integer('fifties')->default(0)->after('batting_strike_rate');
            }
            if (!Schema::hasColumn('players', 'hundreds')) {
                $table->integer('hundreds')->default(0)->after('fifties');
            }

            // Bowling Stats
            if (!Schema::hasColumn('players', 'wickets_taken')) {
                $table->integer('wickets_taken')->default(0)->after('hundreds');
            }
            if (!Schema::hasColumn('players', 'best_bowling_figures')) {
                $table->string('best_bowling_figures', 20)->nullable()->after('wickets_taken');
            }
            if (!Schema::hasColumn('players', 'bowling_economy')) {
                $table->decimal('bowling_economy', 4, 2)->default(0.00)->after('best_bowling_figures');
            }
            if (!Schema::hasColumn('players', 'bowling_average')) {
                $table->decimal('bowling_average', 5, 2)->default(0.00)->after('bowling_economy');
            }
            if (!Schema::hasColumn('players', 'five_wicket_hauls')) {
                $table->integer('five_wicket_hauls')->default(0)->after('bowling_average');
            }

            // Fielding Stats
            if (!Schema::hasColumn('players', 'catches')) {
                $table->integer('catches')->default(0)->after('five_wicket_hauls');
            }
            if (!Schema::hasColumn('players', 'stumpings')) {
                $table->integer('stumpings')->default(0)->after('catches');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $columnsToDrop = [
                'short_name', 'jersey_number', 'batting_style', 'bowling_style',
                'birth_date', 'nationality', 'image_path', 'matches_played',
                'total_runs', 'highest_score', 'batting_average', 'batting_strike_rate',
                'fifties', 'hundreds', 'wickets_taken', 'best_bowling_figures',
                'bowling_economy', 'bowling_average', 'five_wicket_hauls', 'catches', 'stumpings'
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('players', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};