<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Información física
            $table->integer('height')->nullable()->comment('Altura en cm');
            $table->string('body_type')->nullable(); // delgado, atlético, promedio, robusto, etc.

            // Información personal
            $table->string('relationship_status')->nullable(); // soltero, divorciado, etc.
            $table->string('children')->nullable(); // no tengo, tengo y viven conmigo, etc.
            $table->string('education')->nullable(); // secundaria, universitario, postgrado, etc.
            $table->string('occupation')->nullable(); // profesión u ocupación

            // Información financiera (principalmente para Sugar Daddies)
            $table->string('income_range')->nullable(); // rangos: <50k, 50k-100k, 100k-250k, etc.
            $table->string('net_worth')->nullable(); // patrimonio estimado

            // Información de estilo de vida (JSON)
            $table->json('interests')->nullable(); // array de intereses
            $table->json('languages')->nullable(); // array de idiomas
            $table->json('lifestyle')->nullable(); // fumador, bebedor, ejercicio, etc.

            // Expectativas y preferencias
            $table->text('looking_for')->nullable(); // Qué busca en la relación
            $table->string('availability')->nullable(); // disponibilidad de tiempo

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_details');
    }
};
