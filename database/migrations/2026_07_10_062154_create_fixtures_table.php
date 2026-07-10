<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_one_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('team_two_id')->constrained('teams')->onDelete('cascade');
            $table->dateTime('match_datetime');
            $table->string('venue')->default('KUET Main Playground');
            $table->enum('status', ['Upcoming', 'Live', 'Completed'])->default('Upcoming');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};