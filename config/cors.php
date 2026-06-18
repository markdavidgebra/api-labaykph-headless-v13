<?php

$extraOrigins = array_map('trim', explode(',', (string) env('CORS_ALLOWED_ORIGINS', '')));

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => array_values(array_unique(array_filter(array_merge(
        [
            'http://localhost:5173',
            'http://127.0.0.1:5173',
            'https://labaykph.com',
            'https://www.labaykph.com',
            'https://test.labaykph.com',
        ],
        [
            env('FRONTEND_URL'),
            env('APP_URL'),
        ],
        $extraOrigins,
    )))),

    'allowed_origins_patterns' => [
        '#^https://([a-z0-9-]+\.)?labaykph\.com$#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
