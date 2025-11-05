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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('subscription_plans')->onDelete('restrict');

            $table->string('mp_plan_id')->nullable(); // ID del plan en Mercado Pago
            $table->string('mp_preapproval_id')->nullable(); // ID de preaprobación (suscripción activa)

            $table->enum('status', ['active', 'cancelled', 'expired', 'pending', 'failed'])->default('pending');
            $table->string('payment_method')->default('mercadopago'); // mercadopago

            $table->timestamp('starts_at')->nullable(); // Cuándo inicia la suscripción
            $table->timestamp('ends_at')->nullable(); // Cuándo expira
            $table->timestamp('next_billing_date')->nullable(); // Próximo cobro
            $table->timestamp('cancelled_at')->nullable(); // Cuándo se canceló

            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index(['user_id', 'status']);
            $table->index('mp_preapproval_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
