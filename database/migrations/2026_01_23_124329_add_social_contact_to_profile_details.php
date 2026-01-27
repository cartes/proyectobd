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
        Schema::table('profile_details', function (Blueprint $table) {
            $table->string('social_instagram')->nullable()->after('is_private');
            $table->string('social_whatsapp')->nullable()->after('social_instagram');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_details', function (Blueprint $table) {
            $table->dropColumn(['social_instagram', 'social_whatsapp']);
        });
    }
};
