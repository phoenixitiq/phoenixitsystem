<?php

return [
    'php' => [
        'version' => '8.1.0',
        'extensions' => [
            'bcmath',
            'ctype',
            'fileinfo',
            'json',
            'mbstring',
            'openssl',
            'pdo',
            'pdo_mysql',
            'tokenizer',
            'xml',
            'curl',
            'gd',
            'zip'
        ]
    ],
    'mysql' => [
        'min_version' => '5.7.0',
        'recommended_settings' => [
            'max_connections' => 100,
            'max_allowed_packet' => '16M'
        ]
    ],
    'apache' => [
        'modules' => [
            'mod_rewrite',
            'mod_headers'
        ]
    ],
    'directories' => [
        'storage/app' => '755',
        'storage/framework' => '755',
        'storage/logs' => '755',
        'bootstrap/cache' => '755',
        'public/uploads' => '755'
    ]
];