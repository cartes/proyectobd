<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Premium Mensual", "Premium Trimestral", "Premium Anual"
            $table->string('slug')->unique(); // "premium-monthly", "premium-quarterly", "premium-annual"
            $table->decimal('amount', 10, 2); // 9.99, 24.99, 89.99
            $table->string('currency')->default('USD'); // ARS, USD
            $table->integer('frequency'); // 1, 3, 12
            $table->string('frequency_type'); // "months", "days"
            $table->text('description'); // Descripción de features
            $table->json('features')->nullable(); // ["unlimited_likes", "super_likes", "no_ads", etc]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
