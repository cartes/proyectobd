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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->enum('product_type', ['boost', 'super_likes', 'verification', 'gift']); // tipo de compra
            $table->integer('quantity')->default(1); // para super likes: cantidad

            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('ARS');

            $table->string('mp_payment_id')->unique(); // ID del pago en Mercado Pago
            $table->enum('status', ['completed', 'pending', 'failed', 'refunded'])->default('pending');

            $table->json('metadata')->nullable(); // {recipient_user_id, gift_message, etc}

            $table->timestamps();

            // Índices
            $table->index(['user_id', 'product_type']);
            $table->index('mp_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
