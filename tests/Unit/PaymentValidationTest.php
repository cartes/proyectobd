<?php

namespace Tests\Unit;

use App\Models\Transaction;
use Tests\TestCase;

class PaymentValidationTest extends TestCase
{
    /**
     * TEST 25: Validación de monto positivo
     */
    public function test_transaction_amount_must_be_positive()
    {
        $this->expectException(\Exception::class);

        Transaction::factory()->create([
            'amount' => -50.00,
        ]);
    }
}
