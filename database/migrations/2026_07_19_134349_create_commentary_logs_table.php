<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('commentary_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fixture_id')->constrained()->onDelete('cascade');
            $table->integer('innings_number');
            $table->decimal('over_number', 4, 1);
            $table->string('ball_type')->default('Normal'); // Wide, NoBall, Wicket
            $table->integer('runs_scored')->default(0);
            $table->text('description'); // e.g., "Four runs through extra cover!"
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('commentary_logs');
    }
};