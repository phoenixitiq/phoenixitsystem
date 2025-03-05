<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SecurityService
{
    public function generateSecurePassword()
    {
        return Str::random(16);
    }

    public function validatePasswordStrength($password)
    {
        $rules = config('security.password_rules');
        
        return strlen($password) >= $rules['min'] &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[0-9]/', $password) &&
            preg_match('/[^A-Za-z0-9]/', $password);
    }

    public function sanitizeInput($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        
        return htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
    }

    public function validateApiKey($key)
    {
        return hash_equals(config('app.api_key'), $key);
    }

    public function logSecurityEvent($type, $description)
    {
        \Log::channel('security')->info($type, [
            'description' => $description,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
} 