<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Campos de suscripción
            $table->timestamp('premium_until')->nullable()->after('is_premium')->comment('Hasta cuándo es premium');
            $table->string('premium_plan_type')->nullable()->after('premium_until')->comment('Plan actual: daddy, baby');

            // Campos de beneficios
            $table->unsignedInteger('super_likes_remaining')->default(0)->after('premium_plan_type')->comment('Super likes disponibles');
            $table->boolean('profile_boost_active')->default(false)->after('super_likes_remaining')->comment('Boost de perfil activo');
            $table->timestamp('boost_until')->nullable()->after('profile_boost_active')->comment('Hasta cuándo está en boost');
            $table->unsignedInteger('boost_count')->default(0)->after('boost_until')->comment('Cuántos boosts ha usado');

            // Campos de payment
            $table->string('mercado_pago_customer_id')->nullable()->unique()->after('boost_count')->comment('ID del cliente en MP');
            $table->string('primary_payment_method_id')->nullable()->after('mercado_pago_customer_id')->comment('Método de pago principal');

            // Preferencias
            $table->boolean('auto_renew')->default(true)->after('primary_payment_method_id')->comment('Renovación automática');
            $table->json('payment_preferences')->nullable()->after('auto_renew')->comment('Preferencias de pago');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'premium_until',
                'premium_plan_type',
                'super_likes_remaining',
                'profile_boost_active',
                'boost_until',
                'boost_count',
                'mercado_pago_customer_id',
                'primary_payment_method_id',
                'auto_renew',
                'payment_preferences',
            ]);
        });
    }
};
