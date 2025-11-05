<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');

            $table->enum('type', ['subscription', 'purchase']); // tipo de transacción
            $table->string('mp_payment_id')->unique(); // ID del pago en Mercado Pago

            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('ARS');

            $table->enum('status', ['approved', 'pending', 'rejected', 'refunded'])->default('pending');
            $table->string('description');
            $table->string('external_reference')->unique(); // Referencia para Mercado Pago

            $table->json('metadata')->nullable(); // Datos adicionales

            $table->timestamps();

            // Índices
            $table->index(['user_id', 'status']);
            $table->index('mp_payment_id');
            $table->index('external_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
