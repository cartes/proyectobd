<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('set null');
            
            // Qué pasó
            $table->string('action')->comment('create_payment, webhook_received, refund_processed, etc');
            $table->string('status')->comment('success, error, warning');
            
            // Detalles
            $table->string('mercado_pago_event_id')->nullable();
            $table->json('payload')->comment('Datos del evento');
            $table->text('response')->nullable()->comment('Respuesta de MP');
            $table->text('error_message')->nullable();
            
            // Validación
            $table->boolean('signature_valid')->default(true);
            $table->timestamp('processed_at')->useCurrent();
            
            $table->timestamps();
            
            // Índices
            $table->index(['user_id', 'action']);
            $table->index(['transaction_id', 'status']);
            $table->index('mercado_pago_event_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_audit_logs');
    }
};
