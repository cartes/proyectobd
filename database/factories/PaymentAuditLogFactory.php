<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentAuditLog>
 */
class PaymentAuditLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'transaction_id' => \App\Models\Transaction::factory(),
            'action' => 'webhook_received',
            'status' => 'success',
            'processed_at' => now(),
        ];
    }
}
