<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAuditLog extends Model
{
    use HasFactory;

    protected $table = 'payment_audit_logs';

    protected $fillable = [
        'user_id',
        'transaction_id',
        'action',
        'status',
        'mercado_pago_event_id',
        'payload',
        'response',
        'error_message',
        'signature_valid',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'json',
        'signature_valid' => 'boolean',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELACIONES ====================

    /**
     * Pertenece a un usuario (nullable)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Pertenece a una transacción (nullable)
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    // ==================== SCOPES ====================

    /**
     * Scope: obtener logs exitosos
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope: obtener logs con error
     */
    public function scopeError($query)
    {
        return $query->where('status', 'error');
    }

    /**
     * Scope: obtener logs de advertencia
     */
    public function scopeWarning($query)
    {
        return $query->where('status', 'warning');
    }

    /**
     * Scope: obtener logs de acción específica
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: obtener logs de usuario
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Scope: obtener logs con firma inválida
     */
    public function scopeInvalidSignature($query)
    {
        return $query->where('signature_valid', false);
    }

    /**
     * Scope: obtener logs de los últimos N días
     */
    public function scopeRecentDays($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: obtener logs de Mercado Pago específico
     */
    public function scopeForMercadoPagoEvent($query, string $eventId)
    {
        return $query->where('mercado_pago_event_id', $eventId);
    }

    // ==================== MÉTODOS HELPER ====================

    /**
     * Verificar si fue exitoso
     */
    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Verificar si tuvo error
     */
    public function isError(): bool
    {
        return $this->status === 'error';
    }

    /**
     * Verificar si es advertencia
     */
    public function isWarning(): bool
    {
        return $this->status === 'warning';
    }

    /**
     * Verificar si firma es válida
     */
    public function hasValidSignature(): bool
    {
        return $this->signature_valid;
    }

    /**
     * Obtener descripción de la acción
     */
    public function getActionDescription(): string
    {
        return match ($this->action) {
            'create_payment' => 'Crear Pago',
            'webhook_received' => 'Webhook Recibido',
            'payment_approved' => 'Pago Aprobado',
            'payment_rejected' => 'Pago Rechazado',
            'refund_processed' => 'Reembolso Procesado',
            'subscription_created' => 'Suscripción Creada',
            'subscription_cancelled' => 'Suscripción Cancelada',
            'signature_validation' => 'Validación de Firma',
            default => $this->action,
        };
    }

    /**
     * Obtener descripción del estado
     */
    public function getStatusDescription(): string
    {
        return match ($this->status) {
            'success' => 'Exitoso',
            'error' => 'Error',
            'warning' => 'Advertencia',
            default => 'Desconocido',
        };
    }

    /**
     * Obtener color/badge para UI
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'success' => 'bg-green-100 text-green-800',
            'error' => 'bg-red-100 text-red-800',
            'warning' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Registrar evento de pago
     */
    public static function logPaymentEvent(
        string $action,
        string $status,
        ?User $user = null,
        ?Transaction $transaction = null,
        array $payload = [],
        ?string $response = null,
        ?string $errorMessage = null,
        bool $signatureValid = true
    ): self {
        return self::create([
            'user_id' => $user?->id,
            'transaction_id' => $transaction?->id,
            'action' => $action,
            'status' => $status,
            'mercado_pago_event_id' => $payload['id'] ?? null,
            'payload' => $payload,
            'response' => $response,
            'error_message' => $errorMessage,
            'signature_valid' => $signatureValid,
            'processed_at' => now(),
        ]);
    }

    /**
     * Obtener últimos eventos de usuario
     */
    public static function getUserPaymentHistory(User $user, int $limit = 20)
    {
        return self::forUser($user)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Obtener eventos fallidos pendientes de revisión
     */
    public static function getFailedEvents(int $days = 7)
    {
        return self::error()
            ->recentDays($days)
            ->latest()
            ->get();
    }

    /**
     * Obtener eventos con firma inválida
     */
    public static function getInvalidSignatureEvents(int $days = 7)
    {
        return self::invalidSignature()
            ->recentDays($days)
            ->latest()
            ->get();
    }
}
