<?php

return [
    'secure' => env('COOKIE_SECURE', true),
    'http_only' => true,
    'same_site' => 'lax',
    'lifetime' => env('SESSION_LIFETIME', 120), // بالدقائق
    'path' => '/',
    'domain' => env('COOKIE_DOMAIN', null),
    'prefix' => env('COOKIE_PREFIX', 'phoenix_'),
    
    // إعدادات كوكيز الموافقة
    'consent' => [
        'enabled' => true,
        'duration' => 180, // مدة تخزين موافقة المستخدم بالأيام
        'cookie_name' => 'cookie_consent'
    ]
]; 