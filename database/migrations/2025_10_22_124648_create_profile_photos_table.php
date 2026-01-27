<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('photo_path'); // Ruta de la foto
            $table->boolean('is_primary')->default(false); // Foto principal
            $table->boolean('is_verified')->default(false); // Verificada por admin
            $table->enum('moderation_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('order')->default(0); // Orden de visualización
            $table->text('rejection_reason')->nullable(); // Razón de rechazo
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['user_id', 'is_primary']);
            $table->index(['user_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_photos');
    }
};
