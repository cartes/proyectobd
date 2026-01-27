<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Plan Premium Mensual
        SubscriptionPlan::create([
            'name' => 'Premium Mensual',
            'slug' => 'premium-monthly',
            'amount' => 9.99,
            'currency' => 'USD',
            'frequency' => 1,
            'frequency_type' => 'months',
            'description' => 'Acceso completo a todas las features premium durante 1 mes',
            'features' => [
                'unlimited_likes',
                'super_likes_5_daily',
                'see_who_liked_you',
                'rewind',
                'boost_monthly',
                'advanced_filters',
                'no_ads',
                'priority_verification',
            ],
            'is_active' => true,
        ]);

        // Plan Premium Trimestral
        SubscriptionPlan::create([
            'name' => 'Premium Trimestral',
            'slug' => 'premium-quarterly',
            'amount' => 24.99,
            'currency' => 'USD',
            'frequency' => 3,
            'frequency_type' => 'months',
            'description' => 'Acceso completo a todas las features premium durante 3 meses (17% de descuento)',
            'features' => [
                'unlimited_likes',
                'super_likes_5_daily',
                'see_who_liked_you',
                'rewind',
                'boost_monthly',
                'advanced_filters',
                'no_ads',
                'priority_verification',
            ],
            'is_active' => true,
        ]);

        // Plan Premium Anual
        SubscriptionPlan::create([
            'name' => 'Premium Anual',
            'slug' => 'premium-annual',
            'amount' => 89.99,
            'currency' => 'ARS',
            'frequency' => 12,
            'frequency_type' => 'months',
            'description' => 'Acceso completo a todas las features premium durante 12 meses (25% de descuento)',
            'features' => [
                'unlimited_likes',
                'super_likes_5_daily',
                'see_who_liked_you',
                'rewind',
                'boost_monthly',
                'advanced_filters',
                'no_ads',
                'priority_verification',
            ],
            'is_active' => true,
        ]);
    }
}
