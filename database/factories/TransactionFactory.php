<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
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
            'type' => 'boost',
            'amount' => 99.99,
            'currency' => 'ARS',
            'status' => 'pending',
            'description' => 'boost',
            'mp_payment_id' => $this->faker->unique()->numerify('##########'),
            'external_reference' => $this->faker->uuid,
        ];
    }
}
