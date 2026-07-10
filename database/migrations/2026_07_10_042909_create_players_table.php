<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('role', ['Batsman', 'Bowler', 'All-rounder', 'Wicketkeeper']);
            $table->string('student_id')->unique();
            $table->integer('matches_played')->default(0);
            $table->integer('total_runs')->default(0);
            $table->integer('total_wickets')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};