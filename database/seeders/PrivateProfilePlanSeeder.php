<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class PrivateProfilePlanSeeder extends Seeder
{
    public function run(): void
    {
        // Perfil Privado (General)
        SubscriptionPlan::updateOrCreate(
            ['slug' => 'private-profile'],
            [
                'name' => 'Perfil Privado',
                'description' => 'Tu perfil solo será visible para las personas que tú decidas. Máxima discreción.',
                'amount' => 5000.00,
                'currency' => 'CLP',
                'frequency' => 1,
                'frequency_type' => 'months',
                'features' => ['private_profiles'],
                'is_active' => true,
                'target_user_type' => null, // Ambos pueden usarlo
            ]
        );
    }
}
