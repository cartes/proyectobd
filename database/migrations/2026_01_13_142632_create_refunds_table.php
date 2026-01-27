<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('restrict');

            // Info del reembolso
            $table->enum('type', ['full', 'partial'])->default('full');
            $table->decimal('amount', 10, 2);
            $table->string('reason')->comment('Razón del reembolso');

            // IDs de Mercado Pago
            $table->string('mercado_pago_refund_id')->nullable()->unique();
            $table->string('mercado_pago_original_payment_id')->nullable();

            // Estado
            $table->enum('status', ['requested', 'approved', 'rejected', 'completed'])->default('requested');

            // Timestamps
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();

            // Metadata
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Índices
            $table->index(['user_id', 'status']);
            $table->index(['transaction_id', 'status']);
            $table->index('mercado_pago_refund_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
