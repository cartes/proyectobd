<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Descubrir / archivos SEO: filtran por tipo + activo y ordenan por fecha
            $table->index(['user_type', 'is_active', 'created_at'], 'users_discovery_idx');
            $table->index('birth_date', 'users_birth_date_idx');
            $table->index('city', 'users_city_idx');
        });

        Schema::table('profile_details', function (Blueprint $table) {
            $table->index(['user_id', 'is_private'], 'profile_details_user_private_idx');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_discovery_idx');
            $table->dropIndex('users_birth_date_idx');
            $table->dropIndex('users_city_idx');
        });

        Schema::table('profile_details', function (Blueprint $table) {
            $table->dropIndex('profile_details_user_private_idx');
        });
    }
};
