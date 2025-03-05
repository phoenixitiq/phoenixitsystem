<?php
return [
    'app' => [
        'name' => 'Phoenix IT System',
        'version' => '1.0.0',
        'debug' => true,
        'timezone' => 'Asia/Baghdad',
        'url' => 'https://phoenixitiq.com',
        'charset' => 'UTF-8',
        'locale' => 'ar_IQ',
        'fallback_locale' => 'en'
    ],
    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'phoenix_db',
        'username' => 'db_user',
        'password' => 'db_password',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci'
    ],
    'security' => [
        'session_lifetime' => 120,
        'csrf_protection' => true,
        'encryption_key' => 'your-secret-key'
    ]
]; 