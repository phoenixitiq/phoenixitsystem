<?php

return [
    'name' => 'Phoenix IT',
    'version' => '1.0.0',
    'description' => 'نظام إدارة الأعمال المتكامل',
    
    'requirements' => [
        'php' => '8.1.0',
        'extensions' => [
            'pdo_mysql' => 'PDO MySQL',
            'openssl' => 'OpenSSL',
            'mbstring' => 'Mbstring',
            'tokenizer' => 'Tokenizer',
            'xml' => 'XML',
            'ctype' => 'Ctype',
            'json' => 'JSON',
            'curl' => 'cURL',
            'fileinfo' => 'Fileinfo',
            'zip' => 'ZIP'
        ],
        'directories' => [
            'storage' => '755',
            'storage/app' => '755',
            'storage/framework' => '755',
            'storage/logs' => '755',
            'bootstrap/cache' => '755'
        ]
    ]
];