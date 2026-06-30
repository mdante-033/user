<?php
declare(strict_types=1);

$env = require __DIR__ . '/env.php';

return [
    'name' => $env('APP_NAME', "Cheryne's"),
    'env' => $env('APP_ENV', 'production'),
    'debug' => filter_var($env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN),
    'url' => rtrim((string) $env('APP_URL', 'http://localhost:3000'), '/'),
    'timezone' => $env('APP_TIMEZONE', 'Africa/Nairobi'),
    'phone_display' => '0795 879797',
    'phone_tel' => '0795879797',
    'whatsapp_phone' => '254795879797',
    'whatsapp_order_url' => 'https://wa.me/254795879797?text=Hello%20I%27d%20like%20to%20order%20from%20Cheryne%27s%20menu',
];
