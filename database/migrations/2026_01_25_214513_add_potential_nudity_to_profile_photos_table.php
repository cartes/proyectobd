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
        Schema::table('profile_photos', function (Blueprint $table) {
            $table->boolean('potential_nudity')->default(false)->after('rejection_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_photos', function (Blueprint $table) {
            $table->dropColumn('potential_nudity');
        });
    }
};
