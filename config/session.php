<?php
declare(strict_types=1);

$env = require __DIR__ . '/env.php';

return [
    // 1. Force a strict 120-minute expiration if .env is missing it
    'lifetime' => (int) $env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,

    // 2. Force 'true' as the default safety net so cookies NEVER travel over HTTP
    'secure' => (bool) $env('SESSION_SECURE_COOKIE', true),
    
    'http_only' => true,
    'same_site' => 'lax',

    // 3. Force session payload encryption
    'encrypt' => true, 

    // 4. Force Laravel to store sessions in your PostgreSQL database instead of loose files
    'driver' => $env('SESSION_DRIVER', 'database'),
];
