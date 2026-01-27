<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'type',
        'last_four',
        'brand',
        'cardholder_name',
        'expiration_month',
        'expiration_year',
        'mercado_pago_token_id',
        'mercado_pago_customer_card_id',
        'is_active',
        'is_default',
        'metadata',
        'verified_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'metadata' => 'json',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELACIONES ====================

    /**
     * Pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Puede tener muchas transacciones
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // ==================== SCOPES ====================

    /**
     * Scope: obtener solo métodos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: obtener solo métodos por defecto
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope: obtener por usuario
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Scope: obtener tarjetas que expiran pronto (próximos 30 días)
     */
    public function scopeExpiringSoon($query)
    {
        $today = now();
        $thirtyDaysLater = now()->addDays(30);

        return $query->whereRaw('
            CASE
                WHEN expiration_month >= MONTH(?) THEN expiration_year = YEAR(?)
                ELSE expiration_year = YEAR(?) + 1
            END
        ', [$today, $today, $today])
            ->orderBy('expiration_year')
            ->orderBy('expiration_month');
    }

    // ==================== MÉTODOS HELPER ====================

    /**
     * Verificar si la tarjeta está verificada
     */
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    /**
     * Marcar como verificada
     */
    public function markAsVerified(): void
    {
        $this->update(['verified_at' => now()]);
    }

    /**
     * Obtener datos de la tarjeta en formato enmascarado
     */
    public function getMaskedCard(): string
    {
        return sprintf('%s ****%s', strtoupper($this->brand), $this->last_four);
    }

    /**
     * Obtener fecha de vencimiento en formato MM/YY
     */
    public function getExpirationFormatted(): string
    {
        return sprintf('%02d/%02d', $this->expiration_month, $this->expiration_year % 100);
    }

    /**
     * Verificar si la tarjeta está vencida
     */
    public function isExpired(): bool
    {
        $expiryDate = \Carbon\Carbon::createFromDate(
            $this->expiration_year,
            $this->expiration_month,
            1
        )->endOfMonth();

        return $expiryDate->isPast();
    }

    /**
     * Verificar si está próxima a vencer (próximos 30 días)
     */
    public function isExpiringWithinDays(int $days = 30): bool
    {
        $expiryDate = \Carbon\Carbon::createFromDate(
            $this->expiration_year,
            $this->expiration_month,
            1
        )->endOfMonth();

        return $expiryDate->isBetween(now(), now()->addDays($days));
    }

    /**
     * Desactivar este método de pago
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);

        // Si era el por defecto, cambiar a otro
        if ($this->is_default) {
            $this->update(['is_default' => false]);

            // Intentar asignar otro como por defecto
            $another = $this->user
                ->paymentMethods()
                ->where('is_active', true)
                ->where('id', '!=', $this->id)
                ->first();

            if ($another) {
                $another->update(['is_default' => true]);
            }
        }
    }

    /**
     * Establecer como método por defecto
     */
    public function setAsDefault(): void
    {
        // Desactivar otros como por defecto
        $this->user
            ->paymentMethods()
            ->where('is_default', true)
            ->update(['is_default' => false]);

        // Establecer este como por defecto
        $this->update(['is_default' => true, 'is_active' => true]);
    }
}
