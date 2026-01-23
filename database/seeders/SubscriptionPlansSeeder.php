<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Actualizar planes existentes para Sugar Daddies
        SubscriptionPlan::whereIn('slug', ['premium-monthly', 'premium-quarterly', 'premium-annual'])
            ->update(['target_user_type' => 'sugar_daddy']);

        // Planes para Sugar Babies
        $sbPlans = [
            [
                'name' => 'Sugar Baby Gratis',
                'slug' => 'sb-free',
                'amount' => 0.00,
                'currency' => 'CLP',
                'frequency' => 1,
                'frequency_type' => 'months',
                'description' => 'Plan básico para Sugar Babies. Empieza a conocer Sugar Daddies hoy.',
                'features' => [
                    'unlimited_likes',
                    'standard_photos', // Límite normal (ej: 6)
                ],
                'is_active' => true,
                'target_user_type' => 'sugar_baby',
            ],
            [
                'name' => 'Sugar Baby PRO',
                'slug' => 'sb-pro',
                'amount' => 4990.00,
                'currency' => 'CLP',
                'frequency' => 1,
                'frequency_type' => 'months',
                'description' => 'Destaca positivamente y mantén tu privacidad. El plan ideal para SBs serias.',
                'features' => [
                    'unlimited_likes',
                    'super_likes_3_daily',
                    'private_profiles', // Perfil privado
                    'extended_photos', // Más fotos (ej: 12)
                    'share_data', // Compartir datos/redes
                    'see_who_liked_you',
                    'no_ads',
                ],
                'is_active' => true,
                'target_user_type' => 'sugar_baby',
            ],
        ];

        foreach ($sbPlans as $planData) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
        }
    }
}
