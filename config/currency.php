<?php

return [
    'default' => env('DEFAULT_CURRENCY', 'IQD'),
    
    'api' => [
        'update_interval' => env('CURRENCY_UPDATE_INTERVAL', 3600), // بالثواني
        'provider' => env('CURRENCY_PROVIDER', 'local'),
    ],
    
    'format' => [
        'decimal_places' => 2,
        'decimal_separator' => '.',
        'thousand_separator' => ','
    ],
    
    'cache' => [
        'enabled' => true,
        'duration' => 3600 // بالثواني
    ],
    
    'supported_currencies' => [
        'IQD' => [
            'name' => 'دينار عراقي',
            'symbol' => 'د.ع',
            'position' => 'after'
        ],
        'USD' => [
            'name' => 'دولار أمريكي',
            'symbol' => '$',
            'position' => 'before'
        ],
        'EUR' => [
            'name' => 'يورو',
            'symbol' => '€',
            'position' => 'before'
        ]
    ]
]; 