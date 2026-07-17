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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            
            // Core Team Relationships
            $table->foreignId('team1_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('team2_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('winner_id')->nullable()->constrained('teams')->onDelete('set null');
            
            // Performance / Award Relationship
            $table->foreignId('player_of_the_match_id')->nullable()->constrained('players')->onDelete('set null');
            
            // Match Identification & Metadata
            $table->string('match_number')->unique(); // e.g., "Match-01", "Final"
            $table->dateTime('match_date');
            $table->string('venue')->default('KUET Central Playground');
            $table->enum('status', ['Scheduled', 'Live', 'Completed', 'Abandoned'])->default('Scheduled');
            
            // Live / Final Match Scores Summary
            $table->string('team1_score')->nullable(); // e.g., "154/5 (20.0 ov)"
            $table->string('team2_score')->nullable(); // e.g., "155/3 (18.2 ov)"
            $table->string('result_description')->nullable(); // e.g., "ECE won by 7 wickets"
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};