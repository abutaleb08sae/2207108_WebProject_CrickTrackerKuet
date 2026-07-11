<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fixture_id')->unique()->constrained('fixtures')->onDelete('cascade');
            $table->integer('runs')->default(0);
            $table->integer('wickets')->default(0);
            $table->integer('balls_bowled')->default(0);
            $table->string('current_innings')->default('Innings 1');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_scores');
    }
};