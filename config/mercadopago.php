<?php

return [
    'public_key' => env('MERCADO_PAGO_PUBLIC_KEY'),
    'access_token' => env('MERCADO_PAGO_ACCESS_TOKEN'),
    'environment' => env('MERCADO_PAGO_ENV', 'sandbox'),
    'webhook_token' => env('MERCADO_PAGO_WEBHOOK_TOKEN', 'your-secure-webhook-token'),
];
