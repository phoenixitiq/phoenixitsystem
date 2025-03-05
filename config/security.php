<?php

return [
    'password_rules' => [
        'min' => 8,
        'numbers' => true,
        'symbols' => true,
        'uppercase' => true,
        'lowercase' => true,
    ],
    
    'login_attempts' => [
        'max_attempts' => 5,
        'decay_minutes' => 30,
    ],
    
    'session' => [
        'regenerate' => true,
        'expire_on_close' => true,
    ],
    
    'headers' => [
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-XSS-Protection' => '1; mode=block',
        'X-Content-Type-Options' => 'nosniff',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Content-Security-Policy' => "default-src 'self'",
    ],
]; 