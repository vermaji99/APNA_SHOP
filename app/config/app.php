<?php
/**
 * Application Configuration
 */

return [
    'name' => 'APNA-MENS',
    'version' => '1.0.0',
    'env' => 'development', // development, production
    'debug' => true,
    'url' => 'http://localhost',
    'timezone' => 'Asia/Kolkata',
    'locale' => 'en',
    'session' => [
        'lifetime' => 7200, // 2 hours
        'name' => 'apna_mens_session',
        'secure' => false, // Set to true in production with HTTPS
        'httponly' => true,
        'same_site' => 'Lax'
    ],
    'upload' => [
        'max_size' => 5242880, // 5MB
        'allowed_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
        'path' => __DIR__ . '/../../public/uploads/'
    ],
    'pagination' => [
        'per_page' => 12
    ]
];




