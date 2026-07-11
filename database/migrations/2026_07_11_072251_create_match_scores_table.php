<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Wrap with a check to prevent SQLSTATE[42S01] errors if it already exists
        if (!Schema::hasTable('match_scores')) {
            Schema::create('match_scores', function (Blueprint $table) {
                $table->id();
                $table->foreignId('fixture_id')->constrained()->onDelete('cascade');
                $table->integer('runs')->default(0);
                $table->integer('wickets')->default(0);
                $table->integer('balls_bowled')->default(0);
                $table->string('current_innings')->default('Innings 1');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('match_scores');
    }
};