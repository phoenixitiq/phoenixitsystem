<?php

return [
    'hostname' => env('CPANEL_HOST', 'https://phoenixitiq.com:2083'),
    'username' => env('CPANEL_USERNAME'),
    'token' => env('CPANEL_TOKEN'),
    'api_url' => env('CPANEL_API_URL', '/execute'),
    
    'backup' => [
        'enabled' => true,
        'path' => '/backup',
        'retention' => 7, // عدد أيام الاحتفاظ بالنسخ
        'notifications' => true
    ],
    
    'email' => [
        'enabled' => true,
        'max_accounts' => 50,
        'default_quota' => 1024 // MB
    ],
    
    'databases' => [
        'enabled' => true,
        'prefix' => 'phoenix_',
        'max_databases' => 20
    ]
]; 