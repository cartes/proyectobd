<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Información de tarjeta
            $table->string('type')->default('credit_card'); // credit_card, debit_card
            $table->string('last_four')->comment('Últimos 4 dígitos');
            $table->string('brand')->comment('Visa, Mastercard, etc');
            $table->string('cardholder_name');

            // Fechas de tarjeta
            $table->unsignedSmallInteger('expiration_month');
            $table->unsignedSmallInteger('expiration_year');

            // IDs de Mercado Pago
            $table->string('mercado_pago_token_id')->nullable()->unique()->comment('Token del método en MP');
            $table->string('mercado_pago_customer_card_id')->nullable()->unique()->comment('ID de tarjeta en MP');

            // Estado
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);

            // Metadata
            $table->json('metadata')->nullable();
            $table->timestamp('verified_at')->nullable()->comment('Cuándo se verificó');

            $table->timestamps();

            // Índices
            $table->index(['user_id', 'is_active']);
            $table->index('is_default');
            $table->index('mercado_pago_token_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
