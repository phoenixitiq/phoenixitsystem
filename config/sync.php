<?php

return [
    'tables' => [
        'users',
        'settings',
        'currencies',
        'payments',
        'subscriptions'
    ],
    
    'directories' => [
        'uploads',
        'documents',
        'reports'
    ],
    
    'backup' => [
        'enabled' => true,
        'max_backups' => 5,
        'path' => storage_path('backups'),
        'cloud' => [
            'enabled' => true,
            'disk' => 's3',
            'path' => 'backups'
        ]
    ],
    
    'intervals' => [
        'sync' => env('SYNC_INTERVAL', 300), // 5 minutes
        'backup' => env('BACKUP_INTERVAL', 86400) // 24 hours
    ]
]; 