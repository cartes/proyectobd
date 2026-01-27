<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profile_details', function (Blueprint $table) {
            // Campos específicos para Sugar Daddy
            $table->string('industry')->nullable()->comment('Industria/Sector profesional');
            $table->string('company_size')->nullable()->comment('Tamaño de empresa');
            $table->string('travel_frequency')->nullable()->comment('Frecuencia de viajes');
            $table->text('what_i_offer')->nullable()->comment('Qué puedo ofrecer');
            $table->json('mentorship_areas')->nullable()->comment('Áreas de mentoría');

            // Campos específicos para Sugar Baby
            $table->text('appearance_details')->nullable()->comment('Detalles de apariencia');
            $table->string('personal_style')->nullable()->comment('Estilo personal');
            $table->string('fitness_level')->nullable()->comment('Nivel de fitness');
            $table->text('aspirations')->nullable()->comment('Aspiraciones y metas');
            $table->text('ideal_daddy')->nullable()->comment('Daddy ideal');
        });
    }

    public function down(): void
    {
        Schema::table('profile_details', function (Blueprint $table) {
            $table->dropColumn([
                'industry',
                'company_size',
                'travel_frequency',
                'what_i_offer',
                'mentorship_areas',
                'appearance_details',
                'personal_style',
                'fitness_level',
                'aspirations',
                'ideal_daddy',
            ]);
        });
    }
};
