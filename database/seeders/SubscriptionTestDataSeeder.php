<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubscriptionTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('user_type', '!=', null)->take(5)->get();

        if ($users->isEmpty()) {
            $this->command->warn('No hay usuarios de prueba. Ejecuta primero: php artisan db:seed');
            return;
        }

        $premiumMonthly = SubscriptionPlan::where('slug', 'premium-monthly')->first();
        $premiumAnnual = SubscriptionPlan::where('slug', 'premium-annual')->first();

        // Usuario 1: Suscripción premium activa (mensual)
        Subscription::create([
            'user_id' => $users[0]->id,
            'plan_id' => $premiumMonthly->id,
            'mp_plan_id' => 'MP_PLAN_TEST_001',
            'mp_preapproval_id' => 'MP_PREAPPROVAL_TEST_001',
            'status' => 'active',
            'payment_method' => 'mercadopago',
            'starts_at' => now()->subDays(5),
            'ends_at' => now()->addDays(25),
            'next_billing_date' => now()->addDays(25),
        ]);

        // Usuario 2: Suscripción premium activa (anual)
        Subscription::create([
            'user_id' => $users[1]->id,
            'plan_id' => $premiumAnnual->id,
            'mp_plan_id' => 'MP_PLAN_TEST_002',
            'mp_preapproval_id' => 'MP_PREAPPROVAL_TEST_002',
            'status' => 'active',
            'payment_method' => 'mercadopago',
            'starts_at' => now()->subMonths(2),
            'ends_at' => now()->addMonths(10),
            'next_billing_date' => now()->addMonths(1),
        ]);

        // Usuario 3: Suscripción expirada
        Subscription::create([
            'user_id' => $users[2]->id,
            'plan_id' => $premiumMonthly->id,
            'mp_plan_id' => 'MP_PLAN_TEST_003',
            'mp_preapproval_id' => 'MP_PREAPPROVAL_TEST_003',
            'status' => 'expired',
            'payment_method' => 'mercadopago',
            'starts_at' => now()->subMonths(3),
            'ends_at' => now()->subDays(5),
        ]);

        // Usuario 4: Suscripción cancelada
        Subscription::create([
            'user_id' => $users[3]->id,
            'plan_id' => $premiumMonthly->id,
            'mp_plan_id' => 'MP_PLAN_TEST_004',
            'mp_preapproval_id' => 'MP_PREAPPROVAL_TEST_004',
            'status' => 'cancelled',
            'payment_method' => 'mercadopago',
            'starts_at' => now()->subMonths(2),
            'ends_at' => now()->subMonths(1),
            'cancelled_at' => now()->subMonths(1),
        ]);

        $this->command->info('Seeders de suscripciones creados exitosamente ✅');

    }
}
