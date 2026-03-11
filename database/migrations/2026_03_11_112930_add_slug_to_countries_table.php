<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('iso_code');
        });

        // Populate slugs for existing countries
        \DB::table('countries')->get()->each(function ($country) {
            \DB::table('countries')
                ->where('id', $country->id)
                ->update(['slug' => Str::slug($country->name)]);
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
