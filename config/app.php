<?php

use Illuminate\Support\Facades\Vite;

return [
    'name' => env('APP_NAME', 'ExpiryDesk'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'frontend_url' => env('FRONTEND_URL', env('APP_URL', 'http://localhost')),
    'asset_url' => env('ASSET_URL'),
    'timezone' => env('APP_TIMEZONE', 'Asia/Dubai'),
    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],
    'providers' => [],
    'demo_credentials' => [
        [
            'role' => 'admin',
            'email' => env('DEMO_ADMIN_EMAIL', 'admin@expirydesk.test'),
            'password' => env('DEMO_PASSWORD', 'password'),
        ],
        [
            'role' => 'hr',
            'email' => env('DEMO_HR_EMAIL', 'hr@expirydesk.test'),
            'password' => env('DEMO_PASSWORD', 'password'),
        ],
        [
            'role' => 'manager',
            'email' => env('DEMO_MANAGER_EMAIL', 'manager@expirydesk.test'),
            'password' => env('DEMO_PASSWORD', 'password'),
        ],
    ],
];
