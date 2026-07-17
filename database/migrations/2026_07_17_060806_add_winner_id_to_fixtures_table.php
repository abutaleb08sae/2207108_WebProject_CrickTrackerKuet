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
        Schema::table('fixtures', function (Blueprint $table) {
            // Adds winner_id as a nullable unsigned big integer to match standard ID columns
            $table->unsignedBigInteger('winner_id')->nullable()->after('status');

            // Optional: If you want to enforce database integrity with a foreign key constraint
            // $table->foreign('winner_id')->references('id')->on('teams')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fixtures', function (Blueprint $table) {
            $table->dropColumn('winner_id');
        });
    }
};