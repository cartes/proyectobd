<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Estos campos probablemente ya existen, úsalos como referencia
            if (! Schema::hasColumn('transactions', 'payment_method_id')) {
                $table->foreignId('payment_method_id')
                    ->nullable()
                    ->constrained('payment_methods')
                    ->onDelete('set null')
                    ->after('subscription_id');
            }

            if (! Schema::hasColumn('transactions', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }

            if (! Schema::hasColumn('transactions', 'failed_reason')) {
                $table->text('failed_reason')->nullable()->after('approved_at');
            }

            if (! Schema::hasColumn('transactions', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('failed_reason');
            }

            if (! Schema::hasColumn('transactions', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_method_id');
            $table->dropColumn([
                'approved_at',
                'failed_reason',
                'ip_address',
                'user_agent',
            ]);
        });
    }
};
