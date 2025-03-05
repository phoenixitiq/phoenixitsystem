<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiSecurity
{
    public function handle(Request $request, Closure $next)
    {
        // التحقق من API key
        if (!$request->hasHeader('X-API-KEY') || 
            $request->header('X-API-KEY') !== config('app.api_key')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // التحقق من rate limiting
        if ($this->isRateLimitExceeded($request)) {
            return response()->json(['error' => 'Too Many Requests'], 429);
        }

        return $next($request);
    }

    private function isRateLimitExceeded(Request $request)
    {
        $key = 'api:' . $request->ip();
        $maxAttempts = 60;
        $decayMinutes = 1;

        return app('cache')->remember($key, $decayMinutes * 60, function () use ($maxAttempts) {
            return 0;
        }) >= $maxAttempts;
    }
} 