<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('fixtures', function (Blueprint $table) {
            $table->foreignId('player_of_the_match_id')->nullable()->constrained('players');
        });
    }
    public function down(): void {
        Schema::table('fixtures', function (Blueprint $table) {
            $table->dropForeign(['player_of_the_match_id']);
            $table->dropColumn('player_of_the_match_id');
        });
    }
};