<?php

return [
    'max_files' => env('BACKUP_MAX_FILES', 5),
    'path' => storage_path('app/backups'),
    'mysql' => [
        'dump_binary_path' => env('BACKUP_MYSQL_DUMP_PATH', ''),
        'restore_binary_path' => env('BACKUP_MYSQL_RESTORE_PATH', ''),
        'use_single_transaction' => true,
        'timeout' => 300
    ],
    'daftra' => [
        'tables_map' => [
            'clients' => 'clients',
            'invoices' => 'invoices',
            'products' => 'services'
        ]
    ]
]; 